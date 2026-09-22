<?php

namespace App\Services\Incomes;

use App\Models\Transaction;
use App\Models\User;
use App\Models\UserPackage;
use App\Services\IncomeCapService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Class RoiIncomeService
 *
 * INCOME RULE 1: DAILY ROI INCOME (Dex Trade PDF Slide 10 & 17)
 * -------------------------------------------------------------------------
 * Description:
 * Every active package investor receives 0.5% daily Return on Investment (ROI) yield
 * for a period of 400 days, subject to the 2X Non-Working Income Cap limit.
 */
class RoiIncomeService
{
    public function __construct(
        protected IncomeCapService $capService
    ) {}

    /**
     * Process Daily ROI Payout for a single active UserPackage contract with optional custom transaction timestamp.
     */
    public function processSinglePackageRoi(UserPackage $userPkg, ?Carbon $customDate = null): float
    {
        if ($userPkg->status !== 'active' || ! $userPkg->user || ! $userPkg->user->is_bot_active) {
            return 0.00;
        }

        $creditedAmount = 0.00;

        DB::transaction(function () use ($userPkg, $customDate, &$creditedAmount) {
            $user = $userPkg->user;
            $rawDailyYield = ($userPkg->invested_amount * 0.50) / 100;

            // Apply 2X Non-Working Income Cap
            $cappedYield = $this->capService->checkAndCapNonWorking($user, $rawDailyYield);

            // Also check contract total return cap
            $remainingContractCap = (float) $userPkg->total_return_amount - (float) $userPkg->paid_roi_amount;
            $finalYield = min($cappedYield, $remainingContractCap);

            if ($finalYield <= 0) {
                $userPkg->update(['status' => 'completed']);

                return;
            }

            // 1. Credit Earning Wallet
            $user->increment('earning_wallet', $finalYield);

            // 2. Update Contract Paid ROI and Status
            $newPaidRoi = (float) $userPkg->paid_roi_amount + $finalYield;
            $newStatus = ($newPaidRoi >= (float) $userPkg->total_return_amount) ? 'completed' : 'active';

            $userPkg->update([
                'paid_roi_amount' => $newPaidRoi,
                'status' => $newStatus,
            ]);

            // 3. Log Audit Transaction with exact timestamp
            $txnDate = $customDate ?? now();

            $txn = Transaction::create([
                'user_id' => $user->id,
                'txn_number' => 'TXN-'.rand(10000000, 99999999),
                'wallet_type' => 'earning_wallet',
                'amount' => $finalYield,
                'charge' => 0.00,
                'post_balance' => $user->fresh()->earning_wallet,
                'trx_type' => '+',
                'type' => 'daily_roi',
                'description' => 'Daily ROI Yield of 0.5% ($'.number_format($finalYield, 2).") credited from investment contract (\${$userPkg->invested_amount})",
                'reference_id' => $userPkg->id,
                'status' => 'completed',
            ]);

            if ($customDate) {
                $txn->timestamps = false;
                $txn->created_at = $customDate;
                $txn->updated_at = $customDate;
                $txn->save(['timestamps' => false]);
            }

            $creditedAmount = $finalYield;
        });

        return $creditedAmount;
    }

    /**
     * Process Backdated Daily ROI for all missing days from package activation date up to today.
     */
    public function processBackdatedUserRoi(User $user): float
    {
        // Ensure user account & bot status are active
        if ($user->status !== 'active') {
            $user->update([
                'status' => 'active',
                'is_bot_active' => true,
                'bot_activated_at' => $user->bot_activated_at ?? ($user->activated_at ?? now()),
            ]);
        } elseif (! $user->is_bot_active) {
            $user->update([
                'is_bot_active' => true,
                'bot_activated_at' => $user->bot_activated_at ?? ($user->activated_at ?? now()),
            ]);
        }

        $activePackages = UserPackage::where('user_id', $user->id)
            ->where('status', 'active')
            ->whereColumn('paid_roi_amount', '<', 'total_return_amount')
            ->get();

        $totalCredited = 0.00;

        foreach ($activePackages as $pkg) {
            $startDate = Carbon::parse($pkg->purchased_at ?? ($user->activated_at ?? $pkg->created_at))->startOfDay();
            $today = now()->startOfDay();

            $currentDate = clone $startDate;

            while ($currentDate->lte($today)) {
                // Check if daily_roi transaction already exists for this package on this specific date
                $alreadyPaid = Transaction::where('user_id', $user->id)
                    ->where('type', 'daily_roi')
                    ->where('reference_id', $pkg->id)
                    ->whereDate('created_at', $currentDate->toDateString())
                    ->exists();

                if (! $alreadyPaid) {
                    $txnTimestamp = clone $currentDate;
                    $txnTimestamp->setTimeFrom(Carbon::parse($pkg->purchased_at ?? $pkg->created_at));

                    $amount = $this->processSinglePackageRoi($pkg->fresh(), $txnTimestamp);
                    $totalCredited += $amount;
                }

                $currentDate->addDay();
            }
        }

        return $totalCredited;
    }

    /**
     * Process Daily ROI Payouts for a specific target user across all active contracts.
     */
    public function processUserDailyRoi(User $user): float
    {
        return $this->processBackdatedUserRoi($user);
    }

    /**
     * Process Daily ROI Payouts for all active contracts in system.
     */
    public function processAllDailyRoi(): array
    {
        $activePackages = UserPackage::with(['user', 'package'])
            ->where('status', 'active')
            ->whereHas('user', function ($query) {
                $query->where('is_bot_active', true);
            })
            ->whereColumn('paid_roi_amount', '<', 'total_return_amount')
            ->get();

        $processedCount = 0;
        $totalAmountCredited = 0.00;

        foreach ($activePackages as $pkg) {
            $amount = $this->processSinglePackageRoi($pkg);
            if ($amount > 0) {
                $processedCount++;
                $totalAmountCredited += $amount;
            }
        }

        return [
            'processed_contracts' => $processedCount,
            'total_roi_amount' => $totalAmountCredited,
        ];
    }
}
