<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserPackage;
use App\Services\Incomes\DirectIncomeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class PackageController extends Controller
{
    /**
     * Display Available Dex Trade Packages for Purchase.
     */
    public function index(): View
    {
        $user = Auth::user();
        $packageConfig = Package::where('status', 'active')->first();

        // Get all active packages/investments of this user
        $userActivePackages = UserPackage::where('user_id', $user->id)
            ->where('status', 'active')
            ->latest('id')
            ->get();

        $totalActiveCapital = $userActivePackages->sum('invested_amount');

        return view('user.packages.index', compact('user', 'packageConfig', 'userActivePackages', 'totalActiveCapital'));
    }

    /**
     * Buy / Invest in a Package using Deposit Wallet balance.
     */
    public function buy(Request $request): RedirectResponse
    {
        $request->validate([
            'invested_amount' => 'required|numeric|min:10',
        ]);

        $investedAmount = (float) $request->invested_amount;

        // Enforce Minimum $10 & Multiple of $10 Rule (Dex Trade PDF Slide 8)
        if ($investedAmount < 10.0) {
            return redirect()->back()->with('error', 'Minimum investment amount is $10.00 USD.');
        }

        if (fmod($investedAmount, 10.0) != 0) {
            return redirect()->back()->with('error', 'Investment amount must be an exact multiple of $10 (e.g., $10, $20, $30, $50, $100, $500).');
        }

        $user = Auth::user();

        $package = Package::where('status', 'active')->first();
        if (! $package) {
            return redirect()->back()->with('error', 'No active investment package is currently available.');
        }

        // Check Deposit Wallet Balance
        if ((float) $user->deposit_wallet < $investedAmount) {
            return redirect()->route('user.deposits.index')->with('error', "Insufficient Deposit Wallet Balance (\${$user->deposit_wallet}). Please add funds first to invest \${$investedAmount}!");
        }

        // Perform Transaction: Deduct Deposit Wallet, Create UserPackage, Activate User Account
        DB::transaction(function () use ($user, $package, $investedAmount) {
            // Deduct Deposit Wallet
            $user->decrement('deposit_wallet', $investedAmount);

            // Activate User & Start Trading Bot
            $now = now();
            $activatedAt = $user->activated_at ?? $now;
            $user->update([
                'status' => 'active',
                'activated_at' => $activatedAt,
                'is_bot_active' => true,
                'bot_activated_at' => $user->bot_activated_at ?? $activatedAt,
            ]);

            // Calculate ROI amounts (0.5% Daily, 2X Total Return)
            $dailyRoiAmount = ($investedAmount * 0.50) / 100;
            $totalReturnAmount = $investedAmount * 2.00;

            $userPackage = UserPackage::create([
                'user_id' => $user->id,
                'package_id' => $package->id,
                'invested_amount' => $investedAmount,
                'daily_roi' => 0.50,
                'daily_roi_amount' => $dailyRoiAmount,
                'duration_days' => 400,
                'total_return_amount' => $totalReturnAmount,
                'paid_roi_amount' => 0.00,
                'status' => 'active',
                'purchased_at' => now(),
                'expires_at' => now()->addDays(400),
            ]);

            // Log detailed financial transaction
            Transaction::create([
                'user_id' => $user->id,
                'txn_number' => 'TXN-'.rand(10000000, 99999999),
                'wallet_type' => 'deposit_wallet',
                'amount' => $investedAmount,
                'charge' => 0.00,
                'post_balance' => $user->fresh()->deposit_wallet,
                'trx_type' => '-',
                'type' => 'package_purchase',
                'description' => 'Invested $'.number_format($investedAmount, 2).' in Dex Trade Package via Deposit Wallet',
                'reference_id' => $userPackage->id,
                'status' => 'completed',
            ]);

            // Distribute 10% Direct Referral Commission to Sponsor
            app(DirectIncomeService::class)->distributeDirectCommission($user, $userPackage, $investedAmount);

            // Send Package Purchased Confirmation Email Notification via Database Template System
            send_template_email('package-purchased-user', $user->email, [
                'name' => $user->name,
                'amount' => number_format($investedAmount, 2),
                'daily_roi_amount' => number_format($dailyRoiAmount, 2),
                'dashboard_url' => route('user.dashboard'),
            ]);
        });

        return redirect()->route('user.packages.index')->with('success', 'Congratulations! You have successfully invested $'.number_format($investedAmount, 2).' in Dex Trade! 0.5% Daily ROI activated.');
    }

    /**
     * View User's Purchased Packages History.
     */
    public function history(): View
    {
        $user = Auth::user();
        $userPackages = UserPackage::with('package')->where('user_id', $user->id)->latest()->paginate(10);

        return view('user.packages.history', compact('user', 'userPackages'));
    }
}
