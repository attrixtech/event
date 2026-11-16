@extends('layouts.app')

@section('title', 'Ticketing & QR Gate Validator')

@section('content')
<div class="space-y-6">

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Ticketing & Gate QR Validation</h1>
            <p class="text-xs text-slate-500 mt-1">Concert passes, VIP credentials, and instant gate check-in scanner.</p>
        </div>
    </div>

    <!-- Interactive Gate QR Validator Box -->
    <div class="bg-gradient-to-r from-slate-900 to-slate-800 rounded-2xl p-6 text-white shadow-xl border border-slate-700">
        <div class="max-w-xl mx-auto text-center space-y-4">
            <div class="w-12 h-12 rounded-2xl bg-rose-600/30 text-rose-400 border border-rose-500/40 flex items-center justify-center mx-auto text-xl shadow-lg">
                <i class="fa-solid fa-qrcode"></i>
            </div>
            <div>
                <h3 class="text-lg font-extrabold tracking-tight">Live Gate Entrance Scanner</h3>
                <p class="text-xs text-slate-400 mt-1">Enter or scan ticket barcode / QR token to admit attendee.</p>
            </div>

            <form action="{{ route('tickets.verify') }}" method="POST" class="flex gap-2">
                @csrf
                <div class="relative flex-1">
                    <i class="fa-solid fa-barcode absolute left-3 top-3 text-slate-400 text-xs"></i>
                    <input type="text" name="token" required placeholder="e.g. QR-SUNWAVE-8899-A001 or TCK-..." class="w-full bg-slate-800 border border-slate-700 rounded-xl pl-9 pr-3 py-2.5 text-xs text-white placeholder-slate-500 font-mono focus:outline-none focus:border-rose-500">
                </div>
                <button type="submit" class="px-5 py-2.5 bg-rose-600 hover:bg-rose-500 text-white font-bold text-xs rounded-xl shadow-lg transition flex items-center space-x-1.5 shrink-0">
                    <i class="fa-solid fa-check-to-slot"></i>
                    <span>Validate & Admit</span>
                </button>
            </form>

            <div class="text-[11px] text-slate-400 flex items-center justify-center space-x-4">
                <span>Demo Tokens:</span>
                <span class="font-mono bg-slate-800 px-2 py-0.5 rounded text-amber-300">QR-SUNWAVE-8899-A001</span>
                <span class="font-mono bg-slate-800 px-2 py-0.5 rounded text-amber-300">QR-SUNWAVE-8899-B002</span>
            </div>
        </div>
    </div>

    <!-- Tickets Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="p-4 border-b border-slate-100 flex items-center justify-between">
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Issued Passes & Badges</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-400 text-[11px] uppercase tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="px-5 py-3">Ticket #</th>
                        <th class="px-4 py-3">QR Token</th>
                        <th class="px-4 py-3">Holder Name</th>
                        <th class="px-4 py-3">Production & Tier</th>
                        <th class="px-4 py-3">Price Paid</th>
                        <th class="px-4 py-3 text-right">Gate Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse ($tickets as $t)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="px-5 py-3.5 font-mono font-bold text-slate-900">{{ $t->ticket_number }}</td>
                            <td class="px-4 py-3.5 font-mono font-bold text-rose-600">{{ $t->qr_token }}</td>
                            <td class="px-4 py-3.5">
                                <div class="font-bold text-slate-900">{{ $t->holder_name }}</div>
                                <div class="text-[11px] text-slate-400">{{ $t->holder_email ?? $t->holder_phone }}</div>
                            </td>
                            <td class="px-4 py-3.5">
                                <span class="font-medium text-slate-800">{{ $t->event->title ?? '-' }}</span>
                                <div class="text-[10px] text-purple-600 font-bold uppercase">{{ $t->ticketType->name ?? 'Standard' }}</div>
                            </td>
                            <td class="px-4 py-3.5 font-bold text-slate-900">₹{{ number_format($t->price_paid) }}</td>
                            <td class="px-4 py-3.5 text-right">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $t->ticket_status === 'checked_in' ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-700' }}">
                                    {{ $t->ticket_status === 'checked_in' ? 'Admitted' : 'Valid' }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-slate-400">No tickets found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection

