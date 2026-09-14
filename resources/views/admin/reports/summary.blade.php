@extends('admin.layouts.app')

@section('title', 'Master Income Overview')

@section('content')
<div class="space-y-6">

    <!-- PAGE HEADER -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-panel p-6 shadow-2xl rounded-2xl border border-amber-500/30">
        <div>
            <div class="flex items-center gap-2 text-xs font-semibold text-amber-400 uppercase tracking-widest mb-1">
                <i data-lucide="bar-chart-3" class="w-4 h-4 text-amber-400"></i>
                <span>Master Financial Reports</span>
            </div>
            <h1 class="text-2xl font-black font-heading text-white uppercase tracking-wider">
                System Income Overview
            </h1>
            <p class="text-xs text-neutral-400 mt-1">High-level financial audit dashboard summarizing payouts across all 7 Dex Trade income streams</p>
        </div>
        <div class="px-5 py-3 rounded-2xl bg-amber-500/10 border border-amber-500/30 shrink-0">
            <span class="text-[10px] font-bold text-amber-400 uppercase block">Grand Total Incomes Distributed</span>
            <span class="text-2xl font-black font-mono text-emerald-400">${{ number_format($grandTotal, 2) }}</span>
        </div>
    </div>

    <!-- 7 DEX TRADE INCOME SUMMARY KPI TILES -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        
        <!-- 1. Daily ROI Income -->
        <a href="{{ route('admin.reports.roi') }}" class="group bg-panel p-4 rounded-2xl border border-amber-500/30 hover:border-amber-400 shadow-lg transition block">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] font-extrabold text-amber-400 uppercase tracking-wider">Daily ROI Yield</span>
                <i data-lucide="line-chart" class="w-4 h-4 text-amber-400 group-hover:scale-110 transition"></i>
            </div>
            <p class="text-xl font-black text-white font-mono">${{ number_format($roiTotal, 2) }}</p>
            <p class="text-[10px] text-neutral-400 mt-1 flex items-center justify-between">
                <span>0.5% Daily Yield (400 Days)</span>
                <span class="text-amber-400 group-hover:underline">View Audit &rarr;</span>
            </p>
        </a>

        <!-- 2. Direct Income -->
        <a href="{{ route('admin.reports.direct') }}" class="group bg-panel p-4 rounded-2xl border border-amber-500/30 hover:border-amber-400 shadow-lg transition block">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] font-extrabold text-sky-400 uppercase tracking-wider">Direct Income (10%)</span>
                <i data-lucide="user-plus" class="w-4 h-4 text-sky-400 group-hover:scale-110 transition"></i>
            </div>
            <p class="text-xl font-black text-white font-mono">${{ number_format($directTotal, 2) }}</p>
            <p class="text-[10px] text-neutral-400 mt-1 flex items-center justify-between">
                <span>10% Instant Referral Share</span>
                <span class="text-amber-400 group-hover:underline">View Audit &rarr;</span>
            </p>
        </a>

        <!-- 3. Matching Income -->
        <a href="{{ route('admin.reports.matching') }}" class="group bg-panel p-4 rounded-2xl border border-amber-500/30 hover:border-amber-400 shadow-lg transition block">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] font-extrabold text-purple-400 uppercase tracking-wider">Matching Income (10%)</span>
                <i data-lucide="git-merge" class="w-4 h-4 text-purple-400 group-hover:scale-110 transition"></i>
            </div>
            <p class="text-xl font-black text-white font-mono">${{ number_format($matchingTotal, 2) }}</p>
            <p class="text-[10px] text-neutral-400 mt-1 flex items-center justify-between">
                <span>10% Binary Matching (2:1 / 1:2 Req)</span>
                <span class="text-amber-400 group-hover:underline">View Audit &rarr;</span>
            </p>
        </a>

        <!-- 4. Referral ROI Income -->
        <a href="{{ route('admin.reports.referral-roi') }}" class="group bg-panel p-4 rounded-2xl border border-amber-500/30 hover:border-amber-400 shadow-lg transition block">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] font-extrabold text-teal-400 uppercase tracking-wider">Referral ROI Income</span>
                <i data-lucide="repeat" class="w-4 h-4 text-teal-400 group-hover:scale-110 transition"></i>
            </div>
            <p class="text-xl font-black text-white font-mono">${{ number_format($referralRoiTotal, 2) }}</p>
            <p class="text-[10px] text-neutral-400 mt-1 flex items-center justify-between">
                <span>0.5% Daily Direct Investment</span>
                <span class="text-amber-400 group-hover:underline">View Audit &rarr;</span>
            </p>
        </a>

        <!-- 5. Matching ROI Income -->
        <a href="{{ route('admin.reports.matching-roi') }}" class="group bg-panel p-4 rounded-2xl border border-amber-500/30 hover:border-amber-400 shadow-lg transition block">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] font-extrabold text-indigo-400 uppercase tracking-wider">Matching ROI Income</span>
                <i data-lucide="layers" class="w-4 h-4 text-indigo-400 group-hover:scale-110 transition"></i>
            </div>
            <p class="text-xl font-black text-white font-mono">${{ number_format($matchingRoiTotal, 2) }}</p>
            <p class="text-[10px] text-neutral-400 mt-1 flex items-center justify-between">
                <span>0.5% Daily Matching Bonus</span>
                <span class="text-amber-400 group-hover:underline">View Audit &rarr;</span>
            </p>
        </a>

        <!-- 6. Upline Matching Income -->
        <a href="{{ route('admin.reports.upline-matching') }}" class="group bg-panel p-4 rounded-2xl border border-amber-500/30 hover:border-amber-400 shadow-lg transition block">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] font-extrabold text-rose-400 uppercase tracking-wider">Upline Matching Income</span>
                <i data-lucide="share-2" class="w-4 h-4 text-rose-400 group-hover:scale-110 transition"></i>
            </div>
            <p class="text-xl font-black text-white font-mono">${{ number_format($uplineMatchingTotal, 2) }}</p>
            <p class="text-[10px] text-neutral-400 mt-1 flex items-center justify-between">
                <span>10% Upline Deduction Share</span>
                <span class="text-amber-400 group-hover:underline">View Audit &rarr;</span>
            </p>
        </a>

        <!-- 7. Salary Income -->
        <a href="{{ route('admin.reports.salary') }}" class="group bg-panel p-4 rounded-2xl border border-amber-500/30 hover:border-amber-400 shadow-lg transition block">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] font-extrabold text-emerald-400 uppercase tracking-wider">Salary Income</span>
                <i data-lucide="award" class="w-4 h-4 text-emerald-400 group-hover:scale-110 transition"></i>
            </div>
            <p class="text-xl font-black text-white font-mono">${{ number_format($salaryTotal, 2) }}</p>
            <p class="text-[10px] text-neutral-400 mt-1 flex items-center justify-between">
                <span>17 Milestone Ranks</span>
                <span class="text-amber-400 group-hover:underline">View Audit &rarr;</span>
            </p>
        </a>

    </div>

    <!-- 1-ROW COMPACT MULTI-FILTER FORM -->
    <div class="p-3.5 rounded-2xl bg-bg/80 border border-amber-500/40 shadow-lg">
        <form action="{{ route('admin.reports.summary') }}" method="GET" class="flex flex-nowrap items-end gap-3 w-full overflow-x-auto text-xs font-sans pb-1">
            
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

            <div class="w-44 shrink-0">
                <label class="block text-[10px] font-extrabold text-amber-400 uppercase tracking-wider mb-1">INCOME STREAM</label>
                <select name="type" class="w-full px-3 py-2.5 rounded-xl bg-black/60 border border-amber-500/40 text-white font-semibold text-xs focus:outline-none focus:border-amber-400 cursor-pointer">
                    <option value="">All 7 Income Streams</option>
                    <option value="daily_roi" {{ request('type') === 'daily_roi' ? 'selected' : '' }}>Daily ROI Yield</option>
                    <option value="direct_commission" {{ request('type') === 'direct_commission' ? 'selected' : '' }}>Direct Income (10%)</option>
                    <option value="matching_income" {{ request('type') === 'matching_income' ? 'selected' : '' }}>Matching Income (10%)</option>
                    <option value="referral_roi" {{ request('type') === 'referral_roi' ? 'selected' : '' }}>Referral ROI Income</option>
                    <option value="matching_roi" {{ request('type') === 'matching_roi' ? 'selected' : '' }}>Matching ROI Income</option>
                    <option value="upline_matching" {{ request('type') === 'upline_matching' ? 'selected' : '' }}>Upline Matching Income</option>
                    <option value="salary_income" {{ request('type') === 'salary_income' ? 'selected' : '' }}>Salary Income</option>
                </select>
            </div>

            <div class="flex-1 min-w-[200px]">
                <label class="block text-[10px] font-extrabold text-amber-400 uppercase tracking-wider mb-1">SEARCH MEMBER / TXN #</label>
                <div class="relative">
                    <i data-lucide="search" class="w-3.5 h-3.5 absolute left-3.5 top-1/2 -translate-y-1/2 text-amber-400"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Name, code, TXN-..." class="w-full pl-9 pr-4 py-2.5 rounded-xl bg-black/60 border border-amber-500/40 text-white font-semibold text-xs focus:outline-none focus:border-amber-400">
                </div>
            </div>

            <div class="flex items-center gap-2 shrink-0">
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-amber-400 to-amber-500 hover:from-amber-300 hover:to-amber-400 text-black font-black text-xs uppercase tracking-wider shadow-[0_0_15px_rgba(243,202,82,0.5)] transition flex items-center justify-center gap-1.5 shrink-0">
                    <i data-lucide="filter" class="w-3.5 h-3.5 text-black"></i> FILTER
                </button>
                <a href="{{ route('admin.reports.summary') }}" class="py-2.5 px-4 rounded-xl bg-black/60 border border-white/60 text-white hover:bg-white/10 font-bold text-xs transition flex items-center justify-center shrink-0">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- RECENT TRANSACTIONS TABLE -->
    <div class="bg-panel p-6 shadow-2xl rounded-2xl border border-amber-500/30">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
            <div>
                <h3 class="text-lg font-black font-heading text-white uppercase tracking-wider">
                    Recent Platform Financial Transactions
                </h3>
                <p class="text-xs text-neutral-400">Live ledger log of all income distributions</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-amber-500/30 bg-amber-500/5">
                        <th class="py-3 px-4 text-xs font-bold text-amber-400 uppercase">Txn ID</th>
                        <th class="py-3 px-4 text-xs font-bold text-amber-400 uppercase">Member Details</th>
                        <th class="py-3 px-4 text-xs font-bold text-amber-400 uppercase">Type</th>
                        <th class="py-3 px-4 text-xs font-bold text-amber-400 uppercase">Amount</th>
                        <th class="py-3 px-4 text-xs font-bold text-amber-400 uppercase">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-amber-500/10 text-xs">
                    @forelse($recentIncomes as $income)
                        <tr class="hover:bg-amber-500/5 transition">
                            <td class="py-3 px-4 font-mono font-bold text-amber-300 text-xs">{{ $income->txn_number }}</td>
                            <td class="py-3 px-4">
                                @if($income->user)
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-amber-500/20 border border-amber-500/40 text-amber-300 font-bold text-xs flex items-center justify-center shrink-0">
                                            {{ strtoupper(substr($income->user->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="font-bold text-white text-xs">{{ $income->user->name }}</div>
                                            <div class="text-[11px] text-amber-400 font-mono flex items-center gap-1">
                                                <span>{{ $income->user->referral_code }}</span>
                                                <a href="{{ route('admin.users.show', $income->user->id) }}" class="text-neutral-400 hover:text-amber-300 transition" title="View Member Profile">
                                                    <i data-lucide="external-link" class="w-3 h-3"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-neutral-500 text-xs font-mono">Deleted User</span>
                                @endif
                            </td>
                            <td class="py-3 px-4">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase border bg-amber-500/10 text-amber-400 border-amber-500/30">
                                    {{ str_replace('_', ' ', $income->type) }}
                                </span>
                            </td>
                            <td class="py-3 px-4 font-mono font-bold text-emerald-400">+${{ number_format($income->amount, 2) }}</td>
                            <td class="py-3 px-4 text-neutral-400 font-mono">{{ $income->created_at->format('M d, Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-neutral-400 italic">No income transactions found matching filter parameters.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $recentIncomes->links() }}
        </div>
    </div>

</div>
@endsection
