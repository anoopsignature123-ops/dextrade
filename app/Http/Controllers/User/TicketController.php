<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\SupportTicket;
use App\Models\TicketMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class TicketController extends Controller
{
    /**
     * Display a listing of the user's support tickets.
     */
    public function index(Request $request)
    {
        $query = SupportTicket::where('user_id', Auth::id());

        // Status Filter Tab
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Category Filter
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Search Query
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('ticket_number', 'like', "%{$search}%")
                    ->orWhere('subject', 'like', "%{$search}%");
            });
        }

        $tickets = $query->with('lastMessage')->latest()->paginate(10)->withQueryString();

        $openCount = SupportTicket::where('user_id', Auth::id())->whereIn('status', ['open', 'user_reply'])->count();
        $answeredCount = SupportTicket::where('user_id', Auth::id())->where('status', 'answered')->count();
        $closedCount = SupportTicket::where('user_id', Auth::id())->where('status', 'closed')->count();

        return view('user.tickets.index', compact('tickets', 'openCount', 'answeredCount', 'closedCount'));
    }

    /**
     * Show form to create a new ticket.
     */
    public function create()
    {
        return view('user.tickets.create');
    }

    /**
     * Store a newly created support ticket in database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'category' => 'required|in:deposit,withdrawal,package,network,account,other',
            'priority' => 'required|in:low,medium,high,urgent',
            'message' => 'required|string|min:10',
            'attachment' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:5120',
        ]);

        $ticketNumber = 'TKT-'.strtoupper(Str::random(6));

        $ticket = SupportTicket::create([
            'ticket_number' => $ticketNumber,
            'user_id' => Auth::id(),
            'subject' => $request->subject,
            'category' => $request->category,
            'priority' => $request->priority,
            'status' => 'open',
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
            'is_admin_reply' => false,
        ]);

        // Send Support Ticket Created Email Notification via Database Template System
        send_template_email('support-ticket-created-user', Auth::user()->email, [
            'name' => Auth::user()->name,
            'ticket_number' => $ticket->ticket_number,
            'subject' => $ticket->subject,
            'category' => $ticket->category,
            'priority' => $ticket->priority,
            'ticket_url' => route('user.tickets.show', $ticket->id),
        ]);

        return redirect()->route('user.tickets.show', $ticket->id)
            ->with('success', "Support Ticket #{$ticketNumber} created successfully! Our team will respond shortly.");
    }

    /**
     * Display a specific support ticket conversation thread.
     */
    public function show($id)
    {
        $ticket = SupportTicket::where('user_id', Auth::id())
            ->with(['messages.user', 'user'])
            ->findOrFail($id);

        return view('user.tickets.show', compact('ticket'));
    }

    /**
     * Send a user reply to an existing support ticket thread.
     */
    public function reply(Request $request, $id)
    {
        $ticket = SupportTicket::where('user_id', Auth::id())->findOrFail($id);

        if ($ticket->status === 'closed') {
            return back()->with('error', 'This support ticket is closed and cannot accept new replies.');
        }

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
            'is_admin_reply' => false,
        ]);

        // Update ticket status to user_reply
        $ticket->update(['status' => 'user_reply']);

        return back()->with('success', 'Your reply has been posted successfully.');
    }

    /**
     * Close a support ticket.
     */
    public function close($id)
    {
        $ticket = SupportTicket::where('user_id', Auth::id())->findOrFail($id);
        $ticket->update(['status' => 'closed']);

        return back()->with('success', "Support Ticket #{$ticket->ticket_number} marked as closed.");
    }
}
