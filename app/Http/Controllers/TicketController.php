<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\TicketType;
use App\Models\Event;
use Carbon\Carbon;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        $query = Ticket::with(['event', 'ticketType']);

        if ($request->filled('event_id')) {
            $query->where('event_id', $request->event_id);
        }

        if ($request->filled('ticket_status')) {
            $query->where('ticket_status', $request->ticket_status);
        }

        $tickets = $query->orderBy('created_at', 'desc')->paginate(20);
        $events = Event::has('ticketTypes')->get();
        $ticketTypes = TicketType::all();

        return view('tickets.index', compact('tickets', 'events', 'ticketTypes'));
    }

    public function verifyTicket(Request $request)
    {
        $request->validate(['token' => 'required|string']);

        $token = trim($request->token);
        $ticket = Ticket::with(['event', 'ticketType'])
            ->where('qr_token', $token)
            ->orWhere('ticket_number', $token)
            ->first();

        if (!$ticket) {
            return back()->with('error', 'Invalid Ticket / QR Token: Not found in database.');
        }

        if ($ticket->ticket_status === 'checked_in') {
            return back()->with('warning', "ALREADY USED! Ticket {$ticket->ticket_number} was checked in on {$ticket->checked_in_at}.");
        }

        $ticket->update([
            'ticket_status' => 'checked_in',
            'checked_in_at' => Carbon::now(),
            'checked_in_by' => auth()->user()->name ?? 'Scanner Gate 1',
        ]);

        return back()->with('success', "VALID ENTRY! Ticket {$ticket->ticket_number} ({$ticket->holder_name} - {$ticket->ticketType->name}) verified & admitted.");
    }
}

