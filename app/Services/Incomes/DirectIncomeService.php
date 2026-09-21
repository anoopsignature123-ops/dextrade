<?php

namespace App\Services\Incomes;

use App\Models\Transaction;
use App\Models\User;
use App\Models\UserPackage;
use App\Services\IncomeCapService;
use Illuminate\Support\Facades\DB;

/**
 * Class DirectIncomeService
 *
 * INCOME RULE 2: DIRECT INCOME (10%) (Dex Trade PDF Slide 11 & 17)
 * -------------------------------------------------------------------------
 * Description:
 * Instant 10% referral commission earned by direct sponsors whenever a direct
 * member purchases an investment package. Subject to 8X Working Income Cap limit.
 */
class DirectIncomeService
{
    public const DIRECT_COMMISSION_PERCENTAGE = 10.0;

    public function __construct(
        protected IncomeCapService $capService
    ) {}

    /**
     * Calculate and distribute 10% Direct Referral Commission to purchaser's sponsor.
     */
    public function distributeDirectCommission(User $purchaser, UserPackage $userPackage, float $investedAmount): float
    {
        if (! $purchaser->sponsor_code) {
            return 0.00;
        }

        $sponsor = User::where('referral_code', $purchaser->sponsor_code)->first();

        if (! $sponsor) {
            return 0.00;
        }

        $rawCommission = ($investedAmount * self::DIRECT_COMMISSION_PERCENTAGE) / 100;
        $commissionAmount = $this->capService->checkAndCapWorking($sponsor, $rawCommission);

        if ($commissionAmount <= 0) {
            return 0.00;
        }

        DB::transaction(function () use ($sponsor, $purchaser, $userPackage, $investedAmount, $commissionAmount) {
            // 1. Credit Sponsor Earning Wallet
            $sponsor->increment('earning_wallet', $commissionAmount);

            // 2. Create Audit Transaction Record
            Transaction::create([
                'user_id' => $sponsor->id,
                'txn_number' => 'TXN-'.rand(10000000, 99999999),
                'wallet_type' => 'earning_wallet',
                'amount' => $commissionAmount,
                'charge' => 0.00,
                'post_balance' => $sponsor->fresh()->earning_wallet,
                'trx_type' => '+',
                'type' => 'direct_commission',
                'description' => 'Received 10% Direct Commission of $'.number_format($commissionAmount, 2)." from direct member {$purchaser->name} ({$purchaser->referral_code}) package investment of \$".number_format($investedAmount, 2),
                'reference_id' => $userPackage->id,
                'status' => 'completed',
            ]);
        });

        return $commissionAmount;
    }
}
