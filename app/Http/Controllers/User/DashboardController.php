<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Deposit;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserPackage;
use App\Models\Withdrawal;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $user = Auth::user();
        $today = now()->startOfDay();

        // 1. Personal Investment & Capping Stats
        $totalInvested = UserPackage::where('user_id', $user->id)->sum('invested_amount');
        $activeInvestmentsCount = UserPackage::where('user_id', $user->id)->where('status', 'active')->count();

        $activeInvestmentAmount = $user->total_active_investment;
        $workingCap = $user->working_income_cap;
        $nonWorkingCap = $user->non_working_income_cap;

        $workingEarned = $user->total_working_earned;
        $nonWorkingEarned = $user->total_non_working_earned;

        $remainingWorkingCap = $user->remaining_working_cap;
        $remainingNonWorkingCap = $user->remaining_non_working_cap;

        // 2. Personal Income Summaries Across All 7 Dex Trade Incomes
        $totalRoiEarned = Transaction::where('user_id', $user->id)->where('type', 'daily_roi')->sum('amount');
        $todayRoiEarned = Transaction::where('user_id', $user->id)->where('type', 'daily_roi')->where('created_at', '>=', $today)->sum('amount');

        $totalDirectEarned = Transaction::where('user_id', $user->id)->where('type', 'direct_commission')->sum('amount');
        $todayDirectEarned = Transaction::where('user_id', $user->id)->where('type', 'direct_commission')->where('created_at', '>=', $today)->sum('amount');

        $totalMatchingEarned = Transaction::where('user_id', $user->id)->where('type', 'matching_income')->sum('amount');
        $todayMatchingEarned = Transaction::where('user_id', $user->id)->where('type', 'matching_income')->where('created_at', '>=', $today)->sum('amount');

        $totalReferralRoiEarned = Transaction::where('user_id', $user->id)->where('type', 'referral_roi')->sum('amount');
        $todayReferralRoiEarned = Transaction::where('user_id', $user->id)->where('type', 'referral_roi')->where('created_at', '>=', $today)->sum('amount');

        $totalMatchingRoiEarned = Transaction::where('user_id', $user->id)->where('type', 'matching_roi')->sum('amount');
        $todayMatchingRoiEarned = Transaction::where('user_id', $user->id)->where('type', 'matching_roi')->where('created_at', '>=', $today)->sum('amount');

        $totalUplineMatchingEarned = Transaction::where('user_id', $user->id)->where('type', 'upline_matching')->sum('amount');
        $todayUplineMatchingEarned = Transaction::where('user_id', $user->id)->where('type', 'upline_matching')->where('created_at', '>=', $today)->sum('amount');

        $totalSalaryEarned = Transaction::where('user_id', $user->id)->where('type', 'salary_income')->sum('amount');
        $todaySalaryEarned = Transaction::where('user_id', $user->id)->where('type', 'salary_income')->where('created_at', '>=', $today)->sum('amount');

        $totalIncomeEarned = $totalRoiEarned + $totalDirectEarned + $totalMatchingEarned + $totalReferralRoiEarned + $totalMatchingRoiEarned + $totalUplineMatchingEarned + $totalSalaryEarned;

        // 3. Withdrawal & Wallet Stats
        $totalWithdrawn = Withdrawal::where('user_id', $user->id)->whereIn('status', ['approved', 'completed'])->sum('net_amount');

        // 4. Direct Network & Leg Volume Stats
        $directMembersCount = User::where('sponsor_code', $user->referral_code)->count();
        $activeDirectMembersCount = User::where('sponsor_code', $user->referral_code)->where('status', 'active')->count();
        $legStats = $user->leg_volume_stats;

        // 5. Full Team & Network Overview Stats (Left & Right Leg Breakdown)
        $leftRoot = $user->leftChild();
        $rightRoot = $user->rightChild();

        $leftTeam = $this->getBranchTeamMembers($leftRoot);
        $rightTeam = $this->getBranchTeamMembers($rightRoot);

        $leftUserIds = $leftTeam->pluck('id')->all();
        $rightUserIds = $rightTeam->pluck('id')->all();

        $leftBusiness = ! empty($leftUserIds)
            ? (float) UserPackage::whereIn('user_id', $leftUserIds)->where('status', 'active')->sum('invested_amount')
            : 0.00;

        $rightBusiness = ! empty($rightUserIds)
            ? (float) UserPackage::whereIn('user_id', $rightUserIds)->where('status', 'active')->sum('invested_amount')
            : 0.00;

        $directs = User::where('sponsor_code', $user->referral_code)->get();
        $directIds = $directs->pluck('id')->all();

        $teamOverview = [
            'direct_total' => $directs->count(),
            'direct_left' => $directs->where('position', 'left')->count(),
            'direct_right' => $directs->where('position', 'right')->count(),
            'direct_active' => $directs->where('status', 'active')->count(),
            'direct_inactive' => $directs->where('status', '!=', 'active')->count(),
            'direct_business' => ! empty($directIds) ? (float) UserPackage::whereIn('user_id', $directIds)->where('status', 'active')->sum('invested_amount') : 0.00,

            'left_team_count' => $leftTeam->count(),
            'right_team_count' => $rightTeam->count(),
            'total_team_count' => $leftTeam->count() + $rightTeam->count(),

            'left_active_team' => $leftTeam->where('status', 'active')->count(),
            'right_active_team' => $rightTeam->where('status', 'active')->count(),
            'total_active_team' => $leftTeam->where('status', 'active')->count() + $rightTeam->where('status', 'active')->count(),

            'left_inactive_team' => $leftTeam->where('status', '!=', 'active')->count(),
            'right_inactive_team' => $rightTeam->where('status', '!=', 'active')->count(),
            'total_inactive_team' => $leftTeam->where('status', '!=', 'active')->count() + $rightTeam->where('status', '!=', 'active')->count(),

            'left_business' => $leftBusiness,
            'right_business' => $rightBusiness,
            'total_business' => $leftBusiness + $rightBusiness,

            'power_leg_volume' => max($leftBusiness, $rightBusiness),
            'weaker_leg_volume' => min($leftBusiness, $rightBusiness),
        ];

        // 6. Recent Collections
        $activePackages = UserPackage::with('package')->where('user_id', $user->id)->latest()->take(5)->get();
        $recentTransactions = Transaction::where('user_id', $user->id)->latest()->take(5)->get();
        $recentDeposits = Deposit::where('user_id', $user->id)->latest()->take(5)->get();

        return view('user.dashboard', compact(
            'user',
            'totalInvested', 'activeInvestmentsCount', 'activeInvestmentAmount',
            'workingCap', 'nonWorkingCap', 'workingEarned', 'nonWorkingEarned',
            'remainingWorkingCap', 'remainingNonWorkingCap',
            'totalRoiEarned', 'todayRoiEarned',
            'totalDirectEarned', 'todayDirectEarned',
            'totalMatchingEarned', 'todayMatchingEarned',
            'totalReferralRoiEarned', 'todayReferralRoiEarned',
            'totalMatchingRoiEarned', 'todayMatchingRoiEarned',
            'totalUplineMatchingEarned', 'todayUplineMatchingEarned',
            'totalSalaryEarned', 'todaySalaryEarned',
            'totalIncomeEarned', 'totalWithdrawn',
            'directMembersCount', 'activeDirectMembersCount', 'legStats', 'teamOverview',
            'activePackages', 'recentTransactions', 'recentDeposits'
        ));
    }

    /**
     * Get all team members recursively in a leg/branch (by placement and sponsor downline).
     */
    private function getBranchTeamMembers(?User $branchRoot): Collection
    {
        if (! $branchRoot) {
            return collect();
        }

        $members = collect([$branchRoot]);
        $currentCodes = collect([$branchRoot->referral_code]);
        $visitedIds = [$branchRoot->id => true];

        for ($depth = 0; $depth < 50 && $currentCodes->isNotEmpty(); $depth++) {
            $children = User::where(function ($q) use ($currentCodes) {
                $q->whereIn('placement_parent_code', $currentCodes)
                    ->orWhereIn('sponsor_code', $currentCodes);
            })
                ->whereNotIn('id', array_keys($visitedIds))
                ->get();

            if ($children->isEmpty()) {
                break;
            }

            foreach ($children as $child) {
                $visitedIds[$child->id] = true;
            }

            $members = $members->merge($children);
            $currentCodes = $children->pluck('referral_code');
        }

        return $members->unique('id')->values();
    }
}
