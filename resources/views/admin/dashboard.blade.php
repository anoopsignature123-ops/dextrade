@extends('admin.layouts.app')

@section('title', 'Admin Dashboard Overview')

@section('content')
    <style>
        .pdf-package-card {
            background: linear-gradient(180deg, #063824 0%, #021d12 50%, #000000 100%) !important;
            border: 2px solid rgba(243, 202, 82, 0.8) !important;
            box-shadow: 0 0 25px rgba(243, 202, 82, 0.25), inset 0 1px 2px rgba(255, 255, 255, 0.2) !important;
            transition: all 0.3s ease-in-out !important;
        }

        .pdf-package-card:hover {
            transform: translateY(-4px) scale(1.02) !important;
            box-shadow: 0 0 35px rgba(243, 202, 82, 0.45) !important;
        }

        .pdf-gold-badge {
            background: linear-gradient(180deg, #fef08a 0%, #f59e0b 50%, #b45309 100%) !important;
            border: 2px solid #fef08a !important;
            box-shadow: 0 0 15px rgba(243, 202, 82, 0.7), inset 0 2px 4px rgba(255, 255, 255, 0.8) !important;
            color: #000000 !important;
        }

        .pdf-gold-ribbon {
            background: linear-gradient(90deg, #d97706 0%, #fef08a 50%, #d97706 100%) !important;
            color: #000000 !important;
            text-shadow: 0 1px 0 rgba(255, 255, 255, 0.4);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.5) !important;
        }

        .gold-highlight {
            color: #f3ca52 !important;
            font-weight: 800 !important;
            text-shadow: 0 0 10px rgba(243, 202, 82, 0.35) !important;
        }
    </style>

    <div class="w-full space-y-6 font-sans relative">

        <!-- Ambient Gold Radial Glow Decorator -->
        <div
            class="absolute -top-24 left-1/2 -translate-x-1/2 w-[900px] h-[450px] bg-amber-500/10 blur-[130px] pointer-events-none rounded-full">
        </div>

        <!-- Top Header Banner (MATCHING PDF DEEP EMERALD & GOLD DESIGN 100%) -->
        <div
            class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4 p-6 rounded-3xl pdf-package-card relative z-10">
            <div>
                <div class="text-[11px] font-black text-amber-400 uppercase tracking-widest mb-0.5 flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-amber-400 animate-ping"></span>
                    ADMIN OVERVIEW CONTROL
                </div>
                <h1 class="text-2xl sm:text-3xl font-black text-white font-heading tracking-tight">Welcome back, <span
                        class="gold-highlight">Super Admin</span></h1>
                <p class="text-xs text-neutral-300 mt-1">Real-time platform financial audit, user activity, capital flow and
                    total income distribution.</p>
            </div>
            <div
                class="px-5 py-2.5 rounded-2xl bg-black/80 border-2 border-amber-400/80 text-amber-300 text-xs font-bold font-mono flex items-center gap-2 shadow-xl">
                <i data-lucide="calendar" class="w-4 h-4 text-amber-400"></i>
                <span>{{ date('l, d M Y') }}</span>
            </div>
        </div>

        <!-- 8 DYNAMIC STAT CARDS GRID (MATCHING USER DASHBOARD COMPACT & SLEEK CARDS) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 relative z-10">

            <!-- CARD 1: Total Members -->
            <div class="p-3.5 sm:p-4 rounded-2xl pdf-package-card relative overflow-hidden group flex flex-col justify-between">
                <div class="flex justify-between items-center mb-3">
                    <div class="w-9 h-9 rounded-xl pdf-gold-badge flex items-center justify-center font-black shrink-0">
                        <i data-lucide="users" class="w-4 h-4 text-black"></i>
                    </div>
                    <span
                        class="text-[10px] font-black text-emerald-300 font-mono bg-black/60 px-2.5 py-0.5 rounded-full border border-emerald-400/50 shrink-0">
                        {{ $activeMembers }} Active
                    </span>
                </div>
                <div>
                    <div class="text-[11px] font-extrabold text-neutral-300 uppercase tracking-wider truncate">Total Members
                    </div>
                    <h3 class="text-xl sm:text-2xl font-black text-white font-heading mt-0.5">
                        {{ number_format($totalMembers) }}
                    </h3>
                </div>
            </div>

            <!-- CARD 2: Total Active Packages -->
            <div class="p-3.5 sm:p-4 rounded-2xl pdf-package-card relative overflow-hidden group flex flex-col justify-between">
                <div class="flex justify-between items-center mb-3">
                    <div class="w-9 h-9 rounded-xl pdf-gold-badge flex items-center justify-center font-black shrink-0">
                        <i data-lucide="package-check" class="w-4 h-4 text-black"></i>
                    </div>
                    <span
                        class="text-[10px] font-black text-emerald-300 font-mono bg-black/60 px-2.5 py-0.5 rounded-full border border-emerald-400/50 shrink-0">Active
                        Now</span>
                </div>
                <div>
                    <div class="text-[11px] font-extrabold text-neutral-300 uppercase tracking-wider truncate">Active
                        Investments</div>
                    <h3 class="text-xl sm:text-2xl font-black text-white font-heading mt-0.5">
                        {{ number_format($activePackagesCount) }}
                    </h3>
                </div>
            </div>

            <!-- CARD 3: Total Deposit Wallet Sum -->
            <div class="p-3.5 sm:p-4 rounded-2xl pdf-package-card relative overflow-hidden group flex flex-col justify-between">
                <div class="flex justify-between items-center mb-3">
                    <div class="w-9 h-9 rounded-xl pdf-gold-badge flex items-center justify-center font-black shrink-0">
                        <i data-lucide="wallet" class="w-4 h-4 text-black"></i>
                    </div>
                    <span
                        class="text-[10px] font-black text-emerald-300 font-mono bg-black/60 px-2.5 py-0.5 rounded-full border border-emerald-400/50 shrink-0">System
                        Deposit</span>
                </div>
                <div>
                    <div class="text-[11px] font-extrabold text-neutral-300 uppercase tracking-wider truncate">Total Deposit
                        Wallets</div>
                    <h3 class="text-xl sm:text-2xl font-black gold-highlight font-mono mt-0.5">
                        ${{ number_format($totalDepositWalletSum, 2) }}</h3>
                </div>
            </div>

            <!-- CARD 4: Total Earning Wallet Sum -->
            <div class="p-3.5 sm:p-4 rounded-2xl pdf-package-card relative overflow-hidden group flex flex-col justify-between">
                <div class="flex justify-between items-center mb-3">
                    <div class="w-9 h-9 rounded-xl pdf-gold-badge flex items-center justify-center font-black shrink-0">
                        <i data-lucide="coins" class="w-4 h-4 text-black"></i>
                    </div>
                    <span
                        class="text-[10px] font-black text-amber-300 font-mono bg-black/60 px-2.5 py-0.5 rounded-full border border-amber-400/50 shrink-0">System
                        Earning</span>
                </div>
                <div>
                    <div class="text-[11px] font-extrabold text-neutral-300 uppercase tracking-wider truncate">Total Earning
                        Wallets</div>
                    <h3 class="text-xl sm:text-2xl font-black text-amber-300 font-mono mt-0.5">
                        ${{ number_format($totalEarningWalletSum, 2) }}</h3>
                </div>
            </div>

            <!-- CARD 5: Total Approved Capital Deposits -->
            <div class="p-3.5 sm:p-4 rounded-2xl pdf-package-card relative overflow-hidden group flex flex-col justify-between">
                <div class="flex justify-between items-center mb-3">
                    <div class="w-9 h-9 rounded-xl pdf-gold-badge flex items-center justify-center font-black shrink-0">
                        <i data-lucide="shield-check" class="w-4 h-4 text-black"></i>
                    </div>
                    <span
                        class="text-[10px] font-black text-emerald-300 font-mono bg-black/60 px-2.5 py-0.5 rounded-full border border-emerald-400/50 shrink-0">Approved</span>
                </div>
                <div>
                    <div class="text-[11px] font-extrabold text-neutral-300 uppercase tracking-wider truncate">Approved
                        Funding
                    </div>
                    <h3 class="text-xl sm:text-2xl font-black text-white font-mono mt-0.5">
                        ${{ number_format($totalApprovedDepositsSum, 2) }}</h3>
                </div>
            </div>

            <!-- CARD 6: Total Capital Invested -->
            <div class="p-3.5 sm:p-4 rounded-2xl pdf-package-card relative overflow-hidden group flex flex-col justify-between">
                <div class="flex justify-between items-center mb-3">
                    <div class="w-9 h-9 rounded-xl pdf-gold-badge flex items-center justify-center font-black shrink-0">
                        <i data-lucide="trending-up" class="w-4 h-4 text-black"></i>
                    </div>
                    <span
                        class="text-[10px] font-black text-sky-300 font-mono bg-black/60 px-2.5 py-0.5 rounded-full border border-sky-400/50 shrink-0">Total
                        Packages</span>
                </div>
                <div>
                    <div class="text-[11px] font-extrabold text-neutral-300 uppercase tracking-wider truncate">Invested
                        Capital
                    </div>
                    <h3 class="text-xl sm:text-2xl font-black text-sky-300 font-mono mt-0.5">
                        ${{ number_format($totalCapitalInvestedSum, 2) }}</h3>
                </div>
            </div>

            <!-- CARD 7: Total Income Distributed -->
            <div class="p-3.5 sm:p-4 rounded-2xl pdf-package-card relative overflow-hidden group flex flex-col justify-between">
                <div class="flex justify-between items-center mb-3">
                    <div class="w-9 h-9 rounded-xl pdf-gold-badge flex items-center justify-center font-black shrink-0">
                        <i data-lucide="award" class="w-4 h-4 text-black"></i>
                    </div>
                    <span
                        class="text-[10px] font-black text-purple-300 font-mono bg-black/60 px-2.5 py-0.5 rounded-full border border-purple-400/50 shrink-0">7
                        Streams</span>
                </div>
                <div>
                    <div class="text-[11px] font-extrabold text-neutral-300 uppercase tracking-wider truncate">Total Income
                        Paid
                    </div>
                    <h3 class="text-xl sm:text-2xl font-black text-purple-300 font-mono mt-0.5">
                        ${{ number_format($totalIncomeDistributedSum, 2) }}</h3>
                </div>
            </div>

            <!-- CARD 8: Pending Deposit Requests -->
            <a href="{{ route('admin.deposits.index') }}"
                class="p-3.5 sm:p-4 rounded-2xl pdf-package-card relative overflow-hidden group block flex flex-col justify-between">
                <div class="flex justify-between items-center mb-3">
                    <div class="w-9 h-9 rounded-xl pdf-gold-badge flex items-center justify-center font-black shrink-0 relative">
                        <i data-lucide="bell" class="w-4 h-4 text-black"></i>
                        @if($pendingDepositsCount > 0)
                            <span class="absolute top-0 right-0 w-2.5 h-2.5 rounded-full bg-rose-500 animate-ping"></span>
                        @endif
                    </div>
                    <span
                        class="text-[10px] font-black text-rose-300 font-mono bg-black/60 px-2.5 py-0.5 rounded-full border border-rose-400/50 shrink-0">
                        {{ $pendingDepositsCount }} Log
                    </span>
                </div>
                <div>
                    <div class="text-[11px] font-extrabold text-neutral-300 uppercase tracking-wider truncate">Instant
                        Deposit
                        History</div>
                    <h3 class="text-xl sm:text-2xl font-black text-white font-heading mt-0.5">
                        {{ number_format($pendingDepositsCount) }}
                    </h3>
                </div>
            </a>

        </div>

        <!-- DYNAMIC 7 DEX TRADE INCOME STREAMS BREAKDOWN CARDS -->
        <div class="space-y-3 pt-2 relative z-10">
            <div class="flex items-center justify-between border-b border-amber-400/30 pb-2.5">
                <div class="flex items-center gap-2.5">
                    <div
                        class="w-8 h-8 rounded-xl pdf-gold-badge flex items-center justify-center text-black font-black text-sm shadow">
                        🛡️
                    </div>
                    <div>
                        <h2 class="text-base sm:text-lg font-black text-gold-gradient font-heading tracking-wide uppercase">
                            DEX TRADE 7 TYPES OF INCOMES</h2>
                        <p class="text-[11px] text-neutral-300">Live Payout Summaries Across All 7 Business Income Streams
                        </p>
                    </div>
                </div>
                <a href="{{ route('admin.reports.summary') }}"
                    class="px-4 py-1.5 rounded-full pdf-gold-ribbon text-[11px] font-black uppercase tracking-wider shadow transition">
                    View Full Audit Summary &rarr;
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

                <!-- 1. DAILY ROI INCOME -->
                <a href="{{ route('admin.reports.roi') }}"
                    class="p-3.5 sm:p-4 rounded-2xl pdf-package-card relative group block hover:border-amber-400 transition">
                    <div class="flex justify-between items-center mb-2">
                        <span
                            class="w-6 h-6 rounded-lg pdf-gold-badge font-black text-[10px] flex items-center justify-center"><i
                                data-lucide="line-chart" class="w-3.5 h-3.5"></i></span>
                        <span
                            class="px-2 py-0.5 rounded-full bg-black/60 text-amber-300 text-[9px] font-black font-mono uppercase border border-amber-500/40">Daily
                            Yield</span>
                    </div>
                    <h4 class="text-xs font-extrabold text-white font-heading uppercase tracking-wide">Daily ROI Income</h4>
                    <h3 class="text-xl sm:text-2xl font-black text-amber-300 font-mono mt-0.5">
                        ${{ number_format($totalRoiPaidSum, 2) }}</h3>
                    <p class="text-[11px] text-neutral-400 font-normal mt-1 leading-snug">
                        0.5% Daily yield for <span class="gold-highlight">400 Days</span> (<span class="text-emerald-400 font-bold">2X
                            Cap</span>).
                    </p>
                </a>

                <!-- 2. DIRECT INCOME (10%) -->
                <a href="{{ route('admin.reports.direct') }}"
                    class="p-3.5 sm:p-4 rounded-2xl pdf-package-card relative group block hover:border-sky-400 transition">
                    <div class="flex justify-between items-center mb-2">
                        <span
                            class="w-6 h-6 rounded-lg pdf-gold-badge font-black text-[10px] flex items-center justify-center"><i
                                data-lucide="user-plus" class="w-3.5 h-3.5"></i></span>
                        <span
                            class="px-2 py-0.5 rounded-full bg-black/60 text-sky-300 text-[9px] font-black font-mono uppercase border border-sky-500/40">Flat
                            10%</span>
                    </div>
                    <h4 class="text-xs font-extrabold text-white font-heading uppercase tracking-wide">Direct Income</h4>
                    <h3 class="text-xl sm:text-2xl font-black text-sky-300 font-mono mt-0.5">
                        ${{ number_format($totalDirectCommissionPaidSum, 2) }}</h3>
                    <p class="text-[11px] text-neutral-400 font-normal mt-1 leading-snug">
                        Flat <span class="text-sky-300 font-bold">10% instant</span> referral bonus (<span class="text-amber-400 font-bold">8X
                            Cap</span>).
                    </p>
                </a>

                <!-- 3. MATCHING INCOME (10%) -->
                <a href="{{ route('admin.reports.matching') }}"
                    class="p-3.5 sm:p-4 rounded-2xl pdf-package-card relative group block hover:border-purple-400 transition">
                    <div class="flex justify-between items-center mb-2">
                        <span
                            class="w-6 h-6 rounded-lg pdf-gold-badge font-black text-[10px] flex items-center justify-center"><i
                                data-lucide="git-merge" class="w-3.5 h-3.5"></i></span>
                        <span
                            class="px-2 py-0.5 rounded-full bg-black/60 text-purple-300 text-[9px] font-black font-mono uppercase border border-purple-500/40">10%
                            Binary</span>
                    </div>
                    <h4 class="text-xs font-extrabold text-white font-heading uppercase tracking-wide">Matching Income</h4>
                    <h3 class="text-xl sm:text-2xl font-black text-purple-300 font-mono mt-0.5">
                        ${{ number_format($totalMatchingPaidSum, 2) }}</h3>
                    <p class="text-[11px] text-neutral-400 font-normal mt-1 leading-snug">
                        10% Binary matching (<span class="text-purple-300 font-bold">2:1 / 1:2 Direct Req</span>, 10% Upline
                        Pool).
                    </p>
                </a>

                <!-- 4. REFERRAL ROI INCOME -->
                <a href="{{ route('admin.reports.referral-roi') }}"
                    class="p-3.5 sm:p-4 rounded-2xl pdf-package-card relative group block hover:border-teal-400 transition">
                    <div class="flex justify-between items-center mb-2">
                        <span
                            class="w-6 h-6 rounded-lg pdf-gold-badge font-black text-[10px] flex items-center justify-center"><i
                                data-lucide="repeat" class="w-3.5 h-3.5"></i></span>
                        <span
                            class="px-2 py-0.5 rounded-full bg-black/60 text-teal-300 text-[9px] font-black font-mono uppercase border border-teal-500/40">0.5%
                            Daily</span>
                    </div>
                    <h4 class="text-xs font-extrabold text-white font-heading uppercase tracking-wide">Referral ROI Income
                    </h4>
                    <h3 class="text-xl sm:text-2xl font-black text-teal-300 font-mono mt-0.5">
                        ${{ number_format($totalReferralRoiPaidSum, 2) }}</h3>
                    <p class="text-[11px] text-neutral-400 font-normal mt-1 leading-snug">
                        0.5% Daily on direct team's investment for <span class="text-teal-300 font-bold">150 Days</span>.
                    </p>
                </a>

                <!-- 5. MATCHING ROI INCOME -->
                <a href="{{ route('admin.reports.matching-roi') }}"
                    class="p-3.5 sm:p-4 rounded-2xl pdf-package-card relative group block hover:border-indigo-400 transition">
                    <div class="flex justify-between items-center mb-2">
                        <span
                            class="w-6 h-6 rounded-lg pdf-gold-badge font-black text-[10px] flex items-center justify-center"><i
                                data-lucide="layers" class="w-3.5 h-3.5"></i></span>
                        <span
                            class="px-2 py-0.5 rounded-full bg-black/60 text-indigo-300 text-[9px] font-black font-mono uppercase border border-indigo-500/40">0.5%
                            Daily</span>
                    </div>
                    <h4 class="text-xs font-extrabold text-white font-heading uppercase tracking-wide">Matching ROI Income
                    </h4>
                    <h3 class="text-xl sm:text-2xl font-black text-indigo-300 font-mono mt-0.5">
                        ${{ number_format($totalMatchingRoiPaidSum, 2) }}</h3>
                    <p class="text-[11px] text-neutral-400 font-normal mt-1 leading-snug">
                        0.5% Daily of matching bonus for <span class="text-indigo-300 font-bold">150 Days</span>.
                    </p>
                </a>

                <!-- 6. UPLINE MATCHING INCOME -->
                <a href="{{ route('admin.reports.upline-matching') }}"
                    class="p-3.5 sm:p-4 rounded-2xl pdf-package-card relative group block hover:border-rose-400 transition">
                    <div class="flex justify-between items-center mb-2">
                        <span
                            class="w-6 h-6 rounded-lg pdf-gold-badge font-black text-[10px] flex items-center justify-center"><i
                                data-lucide="share-2" class="w-3.5 h-3.5"></i></span>
                        <span
                            class="px-2 py-0.5 rounded-full bg-black/60 text-rose-300 text-[9px] font-black font-mono uppercase border border-rose-500/40">10%
                            Pool</span>
                    </div>
                    <h4 class="text-xs font-extrabold text-white font-heading uppercase tracking-wide">Upline Matching
                        Income</h4>
                    <h3 class="text-xl sm:text-2xl font-black text-rose-300 font-mono mt-0.5">
                        ${{ number_format($totalUplineMatchingPaidSum, 2) }}</h3>
                    <p class="text-[11px] text-neutral-400 font-normal mt-1 leading-snug">
                        10% Sponsor matching pool <span class="text-rose-300 font-bold">shared equally</span> among direct
                        team.
                    </p>
                </a>

                <!-- 7. SALARY INCOME -->
                <a href="{{ route('admin.reports.salary') }}"
                    class="p-3.5 sm:p-4 rounded-2xl pdf-package-card relative group block hover:border-emerald-400 transition">
                    <div class="flex justify-between items-center mb-2">
                        <span
                            class="w-6 h-6 rounded-lg pdf-gold-badge font-black text-[10px] flex items-center justify-center"><i
                                data-lucide="award" class="w-3.5 h-3.5"></i></span>
                        <span
                            class="px-2 py-0.5 rounded-full bg-black/60 text-emerald-300 text-[9px] font-black font-mono uppercase border border-emerald-500/40">17
                            Ranks</span>
                    </div>
                    <h4 class="text-xs font-extrabold text-white font-heading uppercase tracking-wide">Salary Income</h4>
                    <h3 class="text-xl sm:text-2xl font-black text-emerald-300 font-mono mt-0.5">
                        ${{ number_format($totalSalaryPaidSum, 2) }}</h3>
                    <p class="text-[11px] text-neutral-400 font-normal mt-1 leading-snug">
                        17 Milestone ranks (<span class="text-emerald-300 font-bold">$50 to $12 Lakh/mo</span> over 5–25
                        months).
                    </p>
                </a>

            </div>
        </div>

        <!-- DYNAMIC SIDE-BY-SIDE TABLES GRID -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 pt-4 relative z-10">

            <!-- LEFT: RECENT USERS DIRECTORY -->
            <div class="p-6 rounded-3xl pdf-package-card space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-amber-500/20">
                    <div class="px-3.5 py-1 rounded-xl pdf-gold-ribbon font-black text-xs uppercase tracking-wider shadow">
                        Recent Users Registered
                    </div>
                    <a href="{{ route('admin.users') }}" class="text-xs text-amber-300 font-bold hover:underline">View All
                        &rarr;</a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs whitespace-nowrap">
                        <thead class="bg-black/80 text-amber-400 uppercase text-[10px] font-bold border-b border-amber-500/30">
                            <tr>
                                <th class="p-3 min-w-[180px]">USER PROFILE</th>
                                <th class="p-3 min-w-[160px]">REFERRAL CODE & LINK</th>
                                <th class="p-3 min-w-[130px]">SPONSOR INFO</th>
                                <th class="p-3 text-right">ACTION</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-amber-500/10 text-neutral-200">
                            @forelse($recentUsers as $ru)
                                <tr class="hover:bg-amber-500/10 transition group">
                                    <!-- User Profile -->
                                    <td class="p-3">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="w-9 h-9 rounded-full pdf-gold-badge text-black font-black text-xs flex items-center justify-center shadow-md shrink-0">
                                                {{ strtoupper(substr($ru->name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <a href="{{ route('admin.users.show', $ru->id) }}"
                                                    class="font-black text-white text-xs hover:text-amber-300 transition block">
                                                    {{ $ru->name }}
                                                </a>
                                                <div class="text-[10px] text-neutral-400 font-mono">{{ $ru->email }}</div>
                                                <div class="text-[10px] text-amber-400 font-mono">
                                                    {{ $ru->mobile ?? 'No Phone' }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Referral Code & Link -->
                                    <td class="p-3">
                                        <div class="space-y-0.5">
                                            <div class="flex items-center gap-1.5">
                                                <span class="font-black text-amber-400 font-mono text-xs">{{ $ru->referral_code }}</span>
                                                <button
                                                    onclick="navigator.clipboard.writeText('{{ $ru->referral_code }}'); showToast('Copied!', 'Referral code copied.', 'success');"
                                                    class="p-0.5 rounded hover:bg-amber-500/20 text-amber-400 transition"
                                                    title="Copy Referral Code">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-amber-400"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                        stroke-linecap="round" stroke-linejoin="round">
                                                        <rect x="9" y="9" width="13" height="13" rx="2" ry="2" />
                                                        <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1" />
                                                    </svg>
                                                </button>
                                            </div>
                                            <div class="flex items-center gap-1.5 mt-0.5">
                                                <button
                                                    onclick="navigator.clipboard.writeText('{{ url('/user/register?sponsor=' . $ru->referral_code . '&position=left') }}'); showToast('Copied!', 'Left Leg registration link copied.', 'success');"
                                                    class="px-2 py-0.5 rounded bg-amber-500/20 text-amber-300 hover:bg-amber-500/40 text-[9px] font-bold font-mono border border-amber-500/40 cursor-pointer"
                                                    title="Copy Left Leg Link">
                                                    Copy Left
                                                </button>
                                                <button
                                                    onclick="navigator.clipboard.writeText('{{ url('/user/register?sponsor=' . $ru->referral_code . '&position=right') }}'); showToast('Copied!', 'Right Leg registration link copied.', 'success');"
                                                    class="px-2 py-0.5 rounded bg-emerald-500/20 text-emerald-300 hover:bg-emerald-500/40 text-[9px] font-bold font-mono border border-emerald-500/40 cursor-pointer"
                                                    title="Copy Right Leg Link">
                                                    Copy Right
                                                </button>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Sponsor Info -->
                                    <td class="p-3">
                                        <div>
                                            @if($ru->sponsor)
                                                <div class="font-bold text-white text-xs">
                                                    {{ $ru->sponsor->name }}
                                                </div>
                                                <div class="text-[10px] text-amber-400 font-mono">
                                                    {{ $ru->sponsor_code }}
                                                </div>
                                            @elseif($ru->sponsor_code)
                                                <div class="font-bold text-white text-xs">
                                                    {{ $ru->sponsor_code }}
                                                </div>
                                                <div class="text-[10px] text-amber-400 font-mono">
                                                    {{ $ru->sponsor_code }}
                                                </div>
                                            @else
                                                <div class="font-bold text-neutral-400 text-xs">
                                                    No Sponsor
                                                </div>
                                                <div class="text-[10px] text-neutral-400 font-mono">
                                                    N/A
                                                </div>
                                            @endif
                                        </div>
                                    </td>

                                    <!-- Action -->
                                    <td class="p-3 text-right">
                                        <a href="{{ route('admin.users.show', $ru->id) }}"
                                            class="px-2.5 py-1 rounded-lg pdf-gold-ribbon text-[10px] font-black uppercase tracking-wider transition inline-flex items-center gap-1 shadow">
                                            <span>Profile</span>
                                            <i data-lucide="chevron-right" class="w-3 h-3 text-black"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="p-4 text-center text-neutral-500 font-medium">No users registered
                                        yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- RIGHT: RECENT DEPOSIT REQUESTS -->
            <div class="p-6 rounded-3xl pdf-package-card space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-amber-500/20">
                    <div class="px-3.5 py-1 rounded-xl pdf-gold-ribbon font-black text-xs uppercase tracking-wider shadow">
                        Recent Deposit History Log
                    </div>
                    <a href="{{ route('admin.deposits.index') }}" class="text-xs text-amber-300 font-bold hover:underline">View All
                        &rarr;</a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs whitespace-nowrap">
                        <thead class="bg-black/80 text-amber-400 uppercase text-[10px] font-bold border-b border-amber-500/30">
                            <tr>
                                <th class="p-3">MEMBER</th>
                                <th class="p-3">AMOUNT ($)</th>
                                <th class="p-3">STATUS</th>
                                <th class="p-3 text-right">ACTION</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-amber-500/10 text-neutral-200">
                            @forelse($recentDeposits as $rd)
                                <tr class="hover:bg-amber-500/10 transition group">
                                    <td class="p-3">
                                        @if($rd->user)
                                            <a href="{{ route('admin.users.show', $rd->user->id) }}"
                                                class="flex items-center gap-2 group-hover:translate-x-1 transition-transform">
                                                <div
                                                    class="w-7 h-7 rounded-full pdf-gold-badge text-black font-black text-[10px] flex items-center justify-center shrink-0">
                                                    {{ strtoupper(substr($rd->user->name, 0, 2)) }}
                                                </div>
                                                <div>
                                                    <div class="font-black text-white group-hover:text-amber-300 transition">
                                                        {{ $rd->user->name }}
                                                        </div>
                                                        <span class="text-[10px] text-amber-400 font-mono">{{ $rd->user->referral_code }}</span>
                                                </div>
                                            </a>
                                        @else
                                            <span class="text-neutral-500 italic">User Deleted</span>
                                        @endif
                                    </td>
                                    <td class="p-3 font-mono font-black text-emerald-400 text-sm">
                                        ${{ number_format($rd->amount, 2) }}</td>
                                    <td class="p-3">
                                        <span
                                            class="px-2.5 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider bg-emerald-500/20 text-emerald-300 border border-emerald-500/50">
                                            INSTANT CREDITED
                                        </span>
                                    </td>
                                    <td class="p-3 text-right">
                                        <a href="{{ route('admin.deposits.index', ['search' => $rd->trx_number]) }}"
                                            class="px-2.5 py-1.5 rounded-lg pdf-gold-ribbon text-[10px] font-black uppercase tracking-wider transition inline-flex items-center gap-1 shadow">
                                            <span>Audit Log</span>
                                            <i data-lucide="chevron-right" class="w-3 h-3 text-black"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="p-4 text-center text-neutral-500 font-medium">No deposit records
                                        found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

    </div>
@endsection