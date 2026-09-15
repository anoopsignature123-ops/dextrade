<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\DepositStatusMail;
use App\Models\Deposit;
use App\Models\Transaction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class DepositController extends Controller
{
    /**
     * Display listing of all deposit requests with status filters.
     */
    public function index(Request $request): View
    {
        $status = $request->query('status');
        $search = $request->query('search');
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        $query = Deposit::with(['user', 'transaction'])->latest();

        if ($status && in_array($status, ['pending', 'approved', 'rejected'])) {
            $query->where('status', $status);
        }

        if ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        }

        if ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($uq) use ($search) {
                    $uq->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('referral_code', 'like', "%{$search}%");
                })->orWhere('txn_hash', 'like', "%{$search}%");
            });
        }

        $deposits = $query->paginate(15)->withQueryString();

        return view('admin.deposits.index', compact('deposits'));
    }

    /**
     * Approve deposit request and credit user's deposit_wallet.
     */
    public function approve(Request $request, Deposit $deposit): RedirectResponse
    {
        if ($deposit->status !== 'pending') {
            return redirect()->back()->with('error', 'This deposit request has already been processed.');
        }

        DB::transaction(function () use ($deposit, $request) {
            $adminNotes = $request->input('admin_notes', 'Approved by Admin');

            $deposit->update([
                'status' => 'approved',
                'approved_at' => now(),
                'admin_notes' => $adminNotes,
            ]);

            // Credit User's Deposit Wallet
            $user = $deposit->user;
            $user->increment('deposit_wallet', $deposit->amount);

            // Log detailed financial transaction
            Transaction::create([
                'user_id' => $user->id,
                'txn_number' => 'TXN-'.rand(10000000, 99999999),
                'wallet_type' => 'deposit_wallet',
                'amount' => $deposit->amount,
                'charge' => 0.00,
                'post_balance' => $user->fresh()->deposit_wallet,
                'trx_type' => '+',
                'type' => 'deposit',
                'description' => "Deposit of \${$deposit->amount} approved via {$deposit->payment_gateway} by Admin (Ref: {$deposit->deposit_ref}) - Remark: {$adminNotes}",
                'reference_id' => $deposit->id,
                'status' => 'completed',
            ]);
        });

        // Send Deposit Approved Email Notification via Database Template System
        send_template_email('deposit-approved-user', $deposit->user->email, [
            'name' => $deposit->user->name,
            'amount' => number_format($deposit->amount, 2),
            'deposit_ref' => $deposit->deposit_ref ?? 'DEP-'.$deposit->id,
            'packages_url' => route('user.packages.index'),
        ]);

        return redirect()->back()->with('success', "Deposit of \${$deposit->amount} approved and credited to user's Deposit Wallet.");
    }

    /**
     * Reject deposit request.
     */
    public function reject(Request $request, Deposit $deposit): RedirectResponse
    {
        if ($deposit->status !== 'pending') {
            return redirect()->back()->with('error', 'This deposit request has already been processed.');
        }

        $deposit->update([
            'status' => 'rejected',
            'admin_notes' => $request->input('admin_notes', 'Rejected by Admin'),
        ]);

        // Send Deposit Rejected Email Notification
        try {
            Mail::to($deposit->user->email)->send(new DepositStatusMail($deposit->user, $deposit, 'rejected'));
        } catch (\Throwable $e) {
            Log::error('Deposit Rejected Mail Exception: '.$e->getMessage());
        }

        return redirect()->back()->with('success', 'Deposit request has been rejected.');
    }
}
