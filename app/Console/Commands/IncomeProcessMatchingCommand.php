<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\UserPackage;
use App\Services\Incomes\MatchingIncomeService;
use Illuminate\Console\Command;

class IncomeProcessMatchingCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'income:process-matching {user? : Optional User ID, Email, or Referral Code to process}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Manually process Option B 10% Binary Matching Income and update Carry Forward Volume tracking';

    /**
     * Execute the console command.
     */
    public function handle(MatchingIncomeService $matchingService): int
    {
        $userInput = $this->argument('user');

        if ($userInput) {
            $users = User::where('id', $userInput)
                ->orWhere('email', $userInput)
                ->orWhere('referral_code', $userInput)
                ->get();
        } else {
            $users = User::where('status', 'active')->get();
        }

        if ($users->isEmpty()) {
            $this->error('No active users found matching your selection criteria.');

            return Command::FAILURE;
        }

        $this->info('Starting Manual Option B Matching & Carry Forward Calculation for '.$users->count().' user(s)...');

        $rows = [];

        foreach ($users as $user) {
            $left = $user->leftChild();
            $right = $user->rightChild();

            $leftIds = $left ? $left->getBranchUserIds() : [];
            $rightIds = $right ? $right->getBranchUserIds() : [];

            $leftLegVolume = ! empty($leftIds)
                ? (float) UserPackage::whereIn('user_id', $leftIds)->where('status', 'active')->sum('invested_amount')
                : (float) $user->left_bv;

            $rightLegVolume = ! empty($rightIds)
                ? (float) UserPackage::whereIn('user_id', $rightIds)->where('status', 'active')->sum('invested_amount')
                : (float) $user->right_bv;

            // Execute matching payout with Option B rules & carry forward tracking
            $matchingPaid = $matchingService->processUserMatching($user, $leftLegVolume, $rightLegVolume);
            $freshUser = $user->fresh();

            $leftCarry = max(0.00, (float) $freshUser->left_bv - (float) $freshUser->left_matched_bv);
            $rightCarry = max(0.00, (float) $freshUser->right_bv - (float) $freshUser->right_matched_bv);

            $rows[] = [
                'user' => $freshUser->name.' ('.$freshUser->referral_code.')',
                '1st_pair' => $freshUser->is_first_pair_matched ? 'Completed (1:1)' : 'Pending (1:1 Req)',
                'left_carry' => '$'.number_format($leftCarry, 2),
                'right_carry' => '$'.number_format($rightCarry, 2),
                'matching_paid' => '$'.number_format($matchingPaid, 2),
                'earning_wallet' => '$'.number_format((float) $freshUser->earning_wallet, 2),
            ];
        }

        $this->table(
            ['User / Referral', 'First Pair Status', 'Left Carry', 'Right Carry', 'Matching Paid', 'Earning Wallet'],
            $rows
        );

        $this->info('Option B Matching & Carry Forward Processed Successfully!');

        return Command::SUCCESS;
    }
}
