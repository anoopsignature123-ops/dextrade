<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Withdrawal;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class WithdrawalController extends Controller
{
    /**
     * Display Withdrawal Request Form and user earning wallet info.
     */
    public function index(): View
    {
        $user = Auth::user();
        $recentWithdrawals = Withdrawal::where('user_id', $user->id)->latest()->take(5)->get();

        return view('user.withdrawals.index', compact('user', 'recentWithdrawals'));
    }

    /**
     * Store and process a new withdrawal request from Earning Wallet.
     */
    public function store(Request $request): RedirectResponse
    {
        $user = Auth::user();

        if ($user->status !== 'active') {
            return redirect()->back()->with('error', 'Your account is currently inactive. Inactive users are not permitted to make withdrawals. Please activate an investment package to enable withdrawals!');
        }

        // PDF SLIDE 20 TERMS: Minimum Withdrawal $5
        $request->validate([
            'amount' => 'required|numeric|min:5',
            'usdt_address' => 'required|string|min:10|max:255',
        ]);

        $requestedAmount = (float) $request->amount;

        // 1. Auto-save wallet_address to user profile if not set
        if (! $user->wallet_address && $request->filled('usdt_address')) {
            $user->update(['wallet_address' => $request->usdt_address]);
        }

        // 2. Check Earning Wallet Balance
        if ((float) $user->earning_wallet < $requestedAmount) {
            return redirect()->back()->with('error', "Insufficient Earning Wallet Balance (\${$user->earning_wallet}). Minimum withdrawal is \$5!");
        }

        // 2. Calculate 10% Withdrawal Deduction (PDF Slide 20 Rule 4)
        $deductionCharge = ($requestedAmount * 10) / 100;
        $netPayableAmount = $requestedAmount - $deductionCharge;

        $trxNumber = 'WD-'.rand(10000000, 99999999);

        DB::transaction(function () use ($user, $requestedAmount, $deductionCharge, $netPayableAmount, $request, $trxNumber) {
            // Deduct requested amount from Earning Wallet
            $user->decrement('earning_wallet', $requestedAmount);

            // Create Withdrawal Request Record
            $withdrawal = Withdrawal::create([
                'user_id' => $user->id,
                'trx_number' => $trxNumber,
                'amount' => $requestedAmount,
                'charge' => $deductionCharge,
                'net_amount' => $netPayableAmount,
                'usdt_address' => trim($request->usdt_address),
                'wallet_type' => 'earning_wallet',
                'status' => 'pending',
            ]);

            // Create Financial Audit Transaction Log
            Transaction::create([
                'user_id' => $user->id,
                'txn_number' => $trxNumber,
                'wallet_type' => 'earning_wallet',
                'amount' => $requestedAmount,
                'charge' => $deductionCharge,
                'post_balance' => $user->fresh()->earning_wallet,
                'trx_type' => '-',
                'type' => 'withdrawal_request',
                'description' => 'Requested Withdrawal of $'.number_format($requestedAmount, 2).' (Net Pay: $'.number_format($netPayableAmount, 2)." after 10% deduction) to USDT BEP20 Address: {$request->usdt_address}",
                'reference_id' => $withdrawal->id,
                'status' => 'pending',
            ]);
        });

        return redirect()->route('user.withdrawals.history')->with('success', 'Withdrawal request of $'.number_format($requestedAmount, 2).' submitted successfully! Net payable: $'.number_format($netPayableAmount, 2).' after 10% deduction.');
    }

    /**
     * Display user's withdrawal requests history.
     */
    public function history(Request $request): View
    {
        $user = Auth::user();
        $query = Withdrawal::where('user_id', $user->id);

        if ($request->filled('status')) {
            if (in_array($request->status, ['approved', 'completed'])) {
                $query->whereIn('status', ['approved', 'completed']);
            } else {
                $query->where('status', $request->status);
            }
        }

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('trx_number', 'like', "%{$search}%")
                    ->orWhere('usdt_address', 'like', "%{$search}%");
            });
        }

        $withdrawals = (clone $query)->latest('id')->paginate(15)->withQueryString();
        $totalRequested = (clone $query)->sum('amount');
        $totalNetPaid = (clone $query)->whereIn('status', ['approved', 'completed'])->sum('net_amount');

        return view('user.withdrawals.history', compact('user', 'withdrawals', 'totalRequested', 'totalNetPaid'));
    }
}
