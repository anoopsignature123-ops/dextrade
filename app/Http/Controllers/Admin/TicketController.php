<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SupportTicket;
use App\Models\TicketMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class TicketController extends Controller
{
    /**
     * Display listing of all support tickets for Admin.
     */
    public function index(Request $request)
    {
        $query = SupportTicket::with(['user', 'lastMessage']);

        // Filter status tab
        if ($request->filled('status')) {
            if ($request->status === 'pending') {
                $query->whereIn('status', ['open', 'user_reply']);
            } else {
                $query->where('status', $request->status);
            }
        }

        // Category filter
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Priority filter
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        // Search query
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('ticket_number', 'like', "%{$search}%")
                    ->orWhere('subject', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($uq) use ($search) {
                        $uq->where('name', 'like', "%{$search}%")
                            ->orWhere('referral_code', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        $tickets = $query->latest()->paginate(15)->withQueryString();

        $allCount = SupportTicket::count();
        $pendingCount = SupportTicket::whereIn('status', ['open', 'user_reply'])->count();
        $answeredCount = SupportTicket::where('status', 'answered')->count();
        $closedCount = SupportTicket::where('status', 'closed')->count();

        return view('admin.tickets.index', compact('tickets', 'allCount', 'pendingCount', 'answeredCount', 'closedCount'));
    }

    /**
     * Display a specific support ticket thread for Admin audit & response.
     */
    public function show($id)
    {
        $ticket = SupportTicket::with(['messages.user', 'user'])->findOrFail($id);

        return view('admin.tickets.show', compact('ticket'));
    }

    /**
     * Post an Admin response to a ticket thread.
     */
    public function reply(Request $request, $id)
    {
        $ticket = SupportTicket::findOrFail($id);

        $request->validate([
            'message' => 'required|string|min:2',
            'attachment' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:5120',
        ]);

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('tickets', 'public');
        }

        TicketMessage::create([
            'ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'message' => $request->message,
            'attachment' => $attachmentPath,
            'is_admin_reply' => true,
        ]);

        // Update ticket status to answered
        $ticket->update(['status' => 'answered']);

        // Send Support Ticket Admin Reply Email Notification via Database Template System
        send_template_email('support-ticket-reply-user', $ticket->user->email, [
            'name' => $ticket->user->name,
            'ticket_number' => $ticket->ticket_number,
            'subject' => $ticket->subject,
            'message_snippet' => Str::limit($request->message, 150),
            'ticket_url' => route('user.tickets.show', $ticket->id),
        ]);

        return back()->with('success', "Admin response posted to Ticket #{$ticket->ticket_number} successfully!");
    }

    /**
     * Close or reopen a support ticket.
     */
    public function close(Request $request, $id)
    {
        $ticket = SupportTicket::findOrFail($id);
        $newStatus = $request->input('status', 'closed');

        $ticket->update(['status' => $newStatus]);

        $statusLabel = ucfirst($newStatus);

        return back()->with('success', "Ticket #{$ticket->ticket_number} status updated to {$statusLabel}.");
    }
}
