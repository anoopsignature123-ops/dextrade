<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserPackage;
use App\Services\Incomes\DirectIncomeService;
use App\Services\Incomes\MatchingIncomeService;
use App\Services\Incomes\MatchingRoiIncomeService;
use App\Services\Incomes\ReferralRoiIncomeService;
use App\Services\Incomes\RoiIncomeService;
use App\Services\Incomes\SalaryIncomeService;
use App\Services\Incomes\UplineMatchingIncomeService;

/**
 * Class IncomeEngineService
 *
 * MASTER FINANCIAL INCOME ENGINE (Dex Trade Official 7-Income Business Architecture)
 * ----------------------------------------------------------------------------------
 * 1. RoiIncomeService            - 0.5% Daily ROI Yield (400 Days / 2X Non-Working Cap)
 * 2. DirectIncomeService         - 10% Direct Referral Commission (8X Working Cap)
 * 3. MatchingIncomeService       - 10% Binary Matching Commission (2:1 / 1:2 Direct Req / 8X Working Cap)
 * 4. ReferralRoiIncomeService    - 0.5% Daily of Direct Members Investment (150 Days / 8X Working Cap)
 * 5. MatchingRoiIncomeService    - 0.5% Daily of Matching Bonus (150 Days / 8X Working Cap)
 * 6. UplineMatchingIncomeService - 10% Shared Sponsor Matching Pool Distribution (8X Working Cap)
 * 7. SalaryIncomeService         - 17-Level Milestone Salary Plan (8X Working Cap)
 */
class IncomeEngineService
{
    public function __construct(
        public RoiIncomeService $roiService,
        public DirectIncomeService $directService,
        public MatchingIncomeService $matchingService,
        public ReferralRoiIncomeService $referralRoiService,
        public MatchingRoiIncomeService $matchingRoiService,
        public UplineMatchingIncomeService $uplineMatchingService,
        public SalaryIncomeService $salaryService
    ) {}

    /**
     * Trigger 10% Direct Commission upon package purchase.
     */
    public function triggerDirectCommission(User $purchaser, UserPackage $userPackage, float $investedAmount): float
    {
        return $this->directService->distributeDirectCommission($purchaser, $userPackage, $investedAmount);
    }

    /**
     * Run system-wide daily ROI yield distribution (0.5% for 400 days).
     */
    public function runDailyRoiDistribution(): array
    {
        return $this->roiService->processAllDailyRoi();
    }

    /**
     * Run system-wide daily Referral ROI distribution (0.5% for 150 days).
     */
    public function runDailyReferralRoiDistribution(): array
    {
        return $this->referralRoiService->processAllReferralRoi();
    }

    /**
     * Run system-wide daily Matching ROI contract payouts (0.5% for 150 days).
     */
    public function runDailyMatchingRoiDistribution(): array
    {
        return $this->matchingRoiService->processAllMatchingRoi();
    }

    /**
     * Process 10% binary matching income for a member.
     */
    public function calculateMatching(User $user, float $powerLeg, float $weakerLeg): float
    {
        return $this->matchingService->processUserMatching($user, $powerLeg, $weakerLeg);
    }

    /**
     * Process 17-Level Salary Income payouts for a member.
     */
    public function processSalary(User $user): float
    {
        return $this->salaryService->processUserSalaryPayout($user);
    }

    /**
     * Run all system-wide daily income distributions.
     */
    public function runAllDailyIncomes(): array
    {
        $roiResult = $this->runDailyRoiDistribution();
        $referralRoiResult = $this->runDailyReferralRoiDistribution();
        $matchingRoiResult = $this->runDailyMatchingRoiDistribution();

        return [
            'daily_roi' => $roiResult,
            'referral_roi' => $referralRoiResult,
            'matching_roi' => $matchingRoiResult,
        ];
    }
}
