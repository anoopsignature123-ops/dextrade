<?php

namespace App\Services\Incomes;

use App\Models\Transaction;
use App\Models\User;
use App\Models\UserPackage;
use App\Services\IncomeCapService;
use Illuminate\Support\Facades\DB;

/**
 * Class MatchingIncomeService
 *
 * INCOME RULE 3: MATCHING INCOME (10%) (Dex Trade PDF Slide 12 & 17)
 * -------------------------------------------------------------------------
 * Description:
 * 10% Binary Matching Income on matched business volume between Left and Right legs.
 * Requirement: Must have 2:1 or 1:2 active direct referrals (at least 2 on Left & 1 on Right, or 1 on Left & 2 on Right).
 * Note: 10% of Sponsor Matching Income is deducted and distributed as Upline Matching Income.
 * Subject to 8X Working Income Cap limit.
 */
class MatchingIncomeService
{
    public const MATCHING_PERCENTAGE = 10.0;

    public function __construct(
        protected IncomeCapService $capService
    ) {}

    /**
     * Check if user meets matching qualification requirement under Option B:
     * - 1st Pair: Requires 1:1 ratio (1 active direct on Left & 1 on Right).
     * - Subsequent Pairs: Requires 2:1 or 1:2 ratio (2 on Left & 1 on Right, or 1 on Left & 2 on Right).
     */
    public function meetsMatchingRequirement(User $user): bool
    {
        $directs = User::where('sponsor_code', $user->referral_code)->where('status', 'active')->get();

        $leftCount = $directs->where('position', 'left')->count();
        $rightCount = $directs->where('position', 'right')->count();

        $hasLeft = $leftCount > 0 || ($user->leftChild() && $user->leftChild()->status === 'active');
        $hasRight = $rightCount > 0 || ($user->rightChild() && $user->rightChild()->status === 'active');

        // 1st Pair requires 1:1 ratio
        if (! $user->is_first_pair_matched) {
            return $hasLeft && $hasRight;
        }

        // Subsequent Pairs require 2:1 or 1:2 ratio
        if ($leftCount > 0 && $rightCount > 0) {
            return ($leftCount >= 2 && $rightCount >= 1) || ($leftCount >= 1 && $rightCount >= 2);
        }

        return $hasLeft && $hasRight && $directs->count() >= 3;
    }

    /**
     * Process 10% Binary Matching Income for a user under Option B with Carry Forward.
     */
    public function processUserMatching(User $user, float $leftLegVolume = 0.0, float $rightLegVolume = 0.0): float
    {
        if ($user->status !== 'active') {
            return 0.00;
        }

        if (! $this->meetsMatchingRequirement($user)) {
            return 0.00;
        }

        // Calculate total leg volumes (from parameters or direct user attributes)
        $leftTotal = $leftLegVolume > 0 ? $leftLegVolume : (float) $user->left_bv;
        $rightTotal = $rightLegVolume > 0 ? $rightLegVolume : (float) $user->right_bv;

        // Fallback to dynamic subtree calculation if 0
        if ($leftTotal == 0 || $rightTotal == 0) {
            $left = $user->leftChild();
            $right = $user->rightChild();

            if ($leftTotal == 0 && $left) {
                $leftIds = $left->getBranchUserIds();
                $leftTotal = (float) UserPackage::whereIn('user_id', $leftIds)->where('status', 'active')->sum('invested_amount');
            }

            if ($rightTotal == 0 && $right) {
                $rightIds = $right->getBranchUserIds();
                $rightTotal = (float) UserPackage::whereIn('user_id', $rightIds)->where('status', 'active')->sum('invested_amount');
            }
        }

        // Update cumulative leg BVs if changed
        if ($leftTotal > (float) $user->left_bv) {
            $user->left_bv = $leftTotal;
        }
        if ($rightTotal > (float) $user->right_bv) {
            $user->right_bv = $rightTotal;
        }

        // Carry Forward Calculation
        $leftCarry = max(0.00, $leftTotal - (float) $user->left_matched_bv);
        $rightCarry = max(0.00, $rightTotal - (float) $user->right_matched_bv);

        $matchedVolume = min($leftCarry, $rightCarry);

        if ($matchedVolume <= 0) {
            $user->save();

            return 0.00;
        }

        $rawMatching = ($matchedVolume * self::MATCHING_PERCENTAGE) / 100;
        $totalMatching = $this->capService->checkAndCapWorking($user, $rawMatching);

        if ($totalMatching <= 0) {
            $user->save();

            return 0.00;
        }

        // 10% is deducted for Upline Matching Income distribution, 90% credited to user
        $uplineShare = ($totalMatching * 10.0) / 100;
        $netMatchingToUser = $totalMatching - $uplineShare;

        DB::transaction(function () use ($user, $netMatchingToUser, $matchedVolume) {
            $user->left_matched_bv = (float) $user->left_matched_bv + $matchedVolume;
            $user->right_matched_bv = (float) $user->right_matched_bv + $matchedVolume;
            $user->is_first_pair_matched = true;
            $user->earning_wallet = (float) $user->earning_wallet + $netMatchingToUser;
            $user->save();

            Transaction::create([
                'user_id' => $user->id,
                'txn_number' => 'TXN-'.rand(10000000, 99999999),
                'wallet_type' => 'earning_wallet',
                'amount' => $netMatchingToUser,
                'charge' => 0.00,
                'post_balance' => $user->earning_wallet,
                'trx_type' => '+',
                'type' => 'matching_income',
                'description' => 'Received 10% Matching Income (Net $'.number_format($netMatchingToUser, 2).' after 10% Upline pool deduction) on matched volume of $'.number_format($matchedVolume, 2),
                'status' => 'completed',
            ]);
        });

        // Trigger Upline Matching Income Pool Distribution to Direct Referrals
        if ($uplineShare > 0) {
            app(UplineMatchingIncomeService::class)->distributeUplineMatchingPool($user, $uplineShare);
        }

        // Trigger Matching ROI Contract (0.5% daily for 150 days)
        app(MatchingRoiIncomeService::class)->createContract($user, $totalMatching);

        return $netMatchingToUser;
    }
}
