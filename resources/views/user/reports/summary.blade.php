@extends('user.layouts.app')

@section('title', 'Dex Trade Income Overview')

@section('content')
<div class="space-y-6">

    <!-- PAGE HEADER -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-panel p-6 shadow-2xl rounded-2xl border border-amber-500/30">
        <div>
            <div class="flex items-center gap-2 text-xs font-semibold text-amber-400 uppercase tracking-widest mb-1">
                <i data-lucide="bar-chart-3" class="w-4 h-4 text-amber-400"></i>
                <span>Dex Trade Financial Reports</span>
            </div>
            <h1 class="text-2xl font-black font-heading text-white uppercase tracking-wider">
                My Income Overview
            </h1>
            <p class="text-xs text-neutral-400 mt-1">Unified earnings dashboard summarizing your returns across all 7 Dex Trade income streams</p>
        </div>
        <div class="px-5 py-3 rounded-2xl bg-amber-500/10 border border-amber-500/30 shrink-0">
            <span class="text-[10px] font-bold text-amber-400 uppercase block">Grand Total Income Earned</span>
            <span class="text-2xl font-black font-mono text-emerald-400">${{ number_format($grandTotal, 2) }}</span>
        </div>
    </div>

    <!-- 7 INCOME SUMMARY KPI TILES -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        
        <!-- 1. Daily ROI Income -->
        <a href="{{ route('user.reports.roi') }}" class="group bg-panel p-4 rounded-2xl border border-amber-500/30 hover:border-amber-400 shadow-lg transition block">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] font-extrabold text-amber-400 uppercase tracking-wider">Daily ROI Income</span>
                <i data-lucide="line-chart" class="w-4 h-4 text-amber-400 group-hover:scale-110 transition"></i>
            </div>
            <p class="text-xl font-black text-white font-mono">${{ number_format($roiTotal, 2) }}</p>
            <p class="text-[10px] text-neutral-400 mt-1 flex items-center justify-between">
                <span>0.5% Daily (400 Days)</span>
                <span class="text-amber-400 group-hover:underline">View History &rarr;</span>
            </p>
        </a>

        <!-- 2. Direct Income -->
        <a href="{{ route('user.reports.direct') }}" class="group bg-panel p-4 rounded-2xl border border-amber-500/30 hover:border-amber-400 shadow-lg transition block">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] font-extrabold text-sky-400 uppercase tracking-wider">Direct Income</span>
                <i data-lucide="user-plus" class="w-4 h-4 text-sky-400 group-hover:scale-110 transition"></i>
            </div>
            <p class="text-xl font-black text-white font-mono">${{ number_format($directTotal, 2) }}</p>
            <p class="text-[10px] text-neutral-400 mt-1 flex items-center justify-between">
                <span>10% Instant Referral Bonus</span>
                <span class="text-amber-400 group-hover:underline">View History &rarr;</span>
            </p>
        </a>

        <!-- 3. Matching Income -->
        <a href="{{ route('user.reports.matching') }}" class="group bg-panel p-4 rounded-2xl border border-amber-500/30 hover:border-amber-400 shadow-lg transition block">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] font-extrabold text-purple-400 uppercase tracking-wider">Matching Income</span>
                <i data-lucide="git-merge" class="w-4 h-4 text-purple-400 group-hover:scale-110 transition"></i>
            </div>
            <p class="text-xl font-black text-white font-mono">${{ number_format($matchingTotal, 2) }}</p>
            <p class="text-[10px] text-neutral-400 mt-1 flex items-center justify-between">
                <span>10% Binary Matching (2:1 / 1:2 Req)</span>
                <span class="text-amber-400 group-hover:underline">View History &rarr;</span>
            </p>
        </a>

        <!-- 4. Referral ROI Income -->
        <a href="{{ route('user.reports.referral-roi') }}" class="group bg-panel p-4 rounded-2xl border border-amber-500/30 hover:border-amber-400 shadow-lg transition block">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] font-extrabold text-teal-400 uppercase tracking-wider">Referral ROI Income</span>
                <i data-lucide="repeat" class="w-4 h-4 text-teal-400 group-hover:scale-110 transition"></i>
            </div>
            <p class="text-xl font-black text-white font-mono">${{ number_format($referralRoiTotal, 2) }}</p>
            <p class="text-[10px] text-neutral-400 mt-1 flex items-center justify-between">
                <span>0.5% Daily from Directs (150 Days)</span>
                <span class="text-amber-400 group-hover:underline">View History &rarr;</span>
            </p>
        </a>

        <!-- 5. Matching ROI Income -->
        <a href="{{ route('user.reports.matching-roi') }}" class="group bg-panel p-4 rounded-2xl border border-amber-500/30 hover:border-amber-400 shadow-lg transition block">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] font-extrabold text-indigo-400 uppercase tracking-wider">Matching ROI Income</span>
                <i data-lucide="layers" class="w-4 h-4 text-indigo-400 group-hover:scale-110 transition"></i>
            </div>
            <p class="text-xl font-black text-white font-mono">${{ number_format($matchingRoiTotal, 2) }}</p>
            <p class="text-[10px] text-neutral-400 mt-1 flex items-center justify-between">
                <span>0.5% Daily of Matching (150 Days)</span>
                <span class="text-amber-400 group-hover:underline">View History &rarr;</span>
            </p>
        </a>

        <!-- 6. Upline Matching Income -->
        <a href="{{ route('user.reports.upline-matching') }}" class="group bg-panel p-4 rounded-2xl border border-amber-500/30 hover:border-amber-400 shadow-lg transition block">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] font-extrabold text-rose-400 uppercase tracking-wider">Upline Matching Income</span>
                <i data-lucide="share-2" class="w-4 h-4 text-rose-400 group-hover:scale-110 transition"></i>
            </div>
            <p class="text-xl font-black text-white font-mono">${{ number_format($uplineMatchingTotal, 2) }}</p>
            <p class="text-[10px] text-neutral-400 mt-1 flex items-center justify-between">
                <span>10% Sponsor Pool Shared</span>
                <span class="text-amber-400 group-hover:underline">View History &rarr;</span>
            </p>
        </a>

        <!-- 7. Salary Income -->
        <a href="{{ route('user.reports.salary') }}" class="group bg-panel p-4 rounded-2xl border border-amber-500/30 hover:border-amber-400 shadow-lg transition block">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] font-extrabold text-orange-400 uppercase tracking-wider">Salary Income</span>
                <i data-lucide="award" class="w-4 h-4 text-orange-400 group-hover:scale-110 transition"></i>
            </div>
            <p class="text-xl font-black text-white font-mono">${{ number_format($salaryTotal, 2) }}</p>
            <p class="text-[10px] text-neutral-400 mt-1 flex items-center justify-between">
                <span>17 Milestone Ranks</span>
                <span class="text-amber-400 group-hover:underline">View History &rarr;</span>
            </p>
        </a>

    </div>

    <!-- 1-ROW COMPACT MULTI-FILTER FORM -->
    <div class="p-3.5 rounded-2xl bg-bg/80 border border-amber-500/40 shadow-lg">
        <form action="{{ route('user.reports.summary') }}" method="GET" class="flex flex-nowrap items-end gap-3 w-full overflow-x-auto text-xs font-sans pb-1">
            
            <div class="w-40 shrink-0">
                <label class="block text-[10px] font-extrabold text-amber-400 uppercase tracking-wider mb-1">INCOME TYPE</label>
                <select name="type" class="w-full px-3 py-2.5 rounded-xl bg-black/60 border border-amber-500/40 text-white font-semibold text-xs focus:outline-none focus:border-amber-400">
                    <option value="">All Income Types</option>
                    <option value="daily_roi" {{ request('type') == 'daily_roi' ? 'selected' : '' }}>Daily ROI</option>
                    <option value="direct_commission" {{ request('type') == 'direct_commission' ? 'selected' : '' }}>Direct Commission</option>
                    <option value="matching_income" {{ request('type') == 'matching_income' ? 'selected' : '' }}>Matching Income</option>
                    <option value="referral_roi" {{ request('type') == 'referral_roi' ? 'selected' : '' }}>Referral ROI</option>
                    <option value="matching_roi" {{ request('type') == 'matching_roi' ? 'selected' : '' }}>Matching ROI</option>
                    <option value="upline_matching" {{ request('type') == 'upline_matching' ? 'selected' : '' }}>Upline Matching</option>
                    <option value="salary_income" {{ request('type') == 'salary_income' ? 'selected' : '' }}>Salary Income</option>
                </select>
            </div>

            <div class="w-40 shrink-0">
                <label class="block text-[10px] font-extrabold text-amber-400 uppercase tracking-wider mb-1">FROM DATE</label>
                <div class="relative">
                    <i data-lucide="calendar" class="w-3.5 h-3.5 absolute left-3 top-1/2 -translate-y-1/2 text-amber-400"></i>
                    <input type="text" name="start_date" value="{{ request('start_date') }}" placeholder="YYYY-MM-DD" class="datepicker w-full pl-8 pr-3 py-2.5 rounded-xl bg-black/60 border border-amber-500/40 text-white font-mono text-xs focus:outline-none focus:border-amber-400">
                </div>
            </div>

            <div class="w-40 shrink-0">
                <label class="block text-[10px] font-extrabold text-amber-400 uppercase tracking-wider mb-1">TO DATE</label>
                <div class="relative">
                    <i data-lucide="calendar" class="w-3.5 h-3.5 absolute left-3 top-1/2 -translate-y-1/2 text-amber-400"></i>
                    <input type="text" name="end_date" value="{{ request('end_date') }}" placeholder="YYYY-MM-DD" class="datepicker w-full pl-8 pr-3 py-2.5 rounded-xl bg-black/60 border border-amber-500/40 text-white font-mono text-xs focus:outline-none focus:border-amber-400">
                </div>
            </div>

            <div class="flex-1 min-w-[200px]">
                <label class="block text-[10px] font-extrabold text-amber-400 uppercase tracking-wider mb-1">SEARCH TXN # / REMARK</label>
                <div class="relative">
                    <i data-lucide="search" class="w-3.5 h-3.5 absolute left-3.5 top-1/2 -translate-y-1/2 text-amber-400"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search Txn #..." class="w-full pl-9 pr-4 py-2.5 rounded-xl bg-black/60 border border-amber-500/40 text-white font-semibold text-xs focus:outline-none focus:border-amber-400">
                </div>
            </div>

            <div class="flex items-center gap-2 shrink-0">
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-amber-400 to-amber-500 hover:from-amber-300 hover:to-amber-400 text-black font-black text-xs uppercase tracking-wider shadow-[0_0_15px_rgba(243,202,82,0.5)] transition flex items-center justify-center gap-1.5 shrink-0">
                    <i data-lucide="filter" class="w-3.5 h-3.5 text-black"></i> FILTER
                </button>
                <a href="{{ route('user.reports.summary') }}" class="py-2.5 px-4 rounded-xl bg-black/60 border border-white/60 text-white hover:bg-white/10 font-bold text-xs transition flex items-center justify-center shrink-0">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- RECENT INCOME PAYOUT AUDIT LOG -->
    <div class="bg-panel p-6 shadow-2xl rounded-2xl border border-amber-500/30 space-y-4">
        <div class="flex items-center justify-between border-b border-amber-500/20 pb-3">
            <h3 class="text-sm font-black text-white uppercase tracking-wider">Recent Income Transactions</h3>
            <span class="text-xs text-amber-400 font-mono">Live Earnings Log</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm whitespace-nowrap">
                <thead class="bg-bg text-amber-400 uppercase text-xs font-bold font-heading tracking-wider border-b border-amber-500/30">
                    <tr>
                        <th class="p-4 rounded-l-xl">TXN NUMBER</th>
                        <th class="p-4">INCOME TYPE</th>
                        <th class="p-4">AMOUNT ($)</th>
                        <th class="p-4">POST BALANCE</th>
                        <th class="p-4">DESCRIPTION / REMARK</th>
                        <th class="p-4">DATE & TIME</th>
                        <th class="p-4 rounded-r-xl">STATUS</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-amber-500/20 text-neutral-200">
                    @forelse($recentIncomes as $log)
                    <tr class="hover:bg-amber-500/10 transition">
                        <td class="p-4 font-mono font-bold text-amber-400 text-xs">{{ $log->txn_number }}</td>
                        <td class="p-4">
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase bg-amber-500/10 text-amber-300 border border-amber-500/30">
                                {{ str_replace('_', ' ', $log->type) }}
                            </span>
                        </td>
                        <td class="p-4 font-mono font-black text-emerald-400">+${{ number_format($log->amount, 2) }}</td>
                        <td class="p-4 font-mono text-neutral-300">${{ number_format($log->post_balance, 2) }}</td>
                        <td class="p-4 text-xs text-neutral-300 max-w-xs truncate">{{ $log->description }}</td>
                        <td class="p-4 text-xs text-neutral-400 font-mono">{{ $log->created_at->format('M d, Y h:i A') }}</td>
                        <td class="p-4">
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-black bg-emerald-500/20 text-emerald-300 border border-emerald-500/40 uppercase">
                                Completed
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="p-8 text-center text-neutral-400 font-medium">
                            No income transactions recorded yet matching your criteria.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($recentIncomes->hasPages())
        <div class="pt-4 border-t border-amber-500/20">
            {{ $recentIncomes->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
