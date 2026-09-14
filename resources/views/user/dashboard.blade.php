@extends('user.layouts.app')

@section('title', 'Dex Trade User Dashboard')

@section('content')
    <style>
        .pdf-package-card {
            background: linear-gradient(180deg, #063824 0%, #021d12 50%, #000000 100%) !important;
            border: 2px solid rgba(243, 202, 82, 0.8) !important;
            box-shadow: 0 0 25px rgba(243, 202, 82, 0.25), inset 0 1px 2px rgba(255, 255, 255, 0.2) !important;
            transition: all 0.3s ease-in-out !important;
        }

        .pdf-package-card:hover {
            transform: translateY(-4px) scale(1.01) !important;
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

        .five-cards-row {
            display: grid;
            grid-template-columns: repeat(1, minmax(0, 1fr));
            gap: 0.875rem;
        }

        @media (min-width: 640px) {
            .five-cards-row {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (min-width: 1024px) {
            .five-cards-row {
                grid-template-columns: repeat(5, minmax(0, 1fr)) !important;
            }
        }
    </style>

    <div class="w-full space-y-6 font-sans relative">

        <!-- Ambient Gold Radial Glow Decorator -->
        <div
            class="absolute -top-24 left-1/2 -translate-x-1/2 w-[900px] h-[450px] bg-amber-500/10 blur-[130px] pointer-events-none rounded-full">
        </div>

        <!-- Top Header Banner -->
        <div
            class="p-6 rounded-3xl pdf-package-card flex flex-col md:flex-row items-start md:items-center justify-between gap-4 relative z-10">
            <div>
                <div class="flex items-center gap-2 text-amber-400 text-xs font-bold uppercase tracking-widest mb-1">
                    @if($user->status === 'active')
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-ping"></span>
                        DEX TRADE MEMBER PORTAL • LIVE ACTIVE ACCOUNT
                    @else
                        <span class="w-2.5 h-2.5 rounded-full bg-amber-400 animate-pulse"></span>
                        DEX TRADE MEMBER PORTAL • INACTIVE ACCOUNT
                    @endif
                </div>
                <h1 class="text-2xl sm:text-3xl font-black text-white font-heading">Welcome Back, <span
                        class="gold-highlight">{{ $user->name }}</span></h1>
                <p class="text-xs text-neutral-300 mt-1">Referral Code: <span
                        class="text-amber-400 font-bold font-mono">{{ $user->referral_code }}</span> • Status:
                    @if($user->status === 'active')
                        <span class="text-emerald-400 font-bold uppercase">ACTIVE MEMBER</span>
                    @else
                        <span class="text-amber-400 font-bold uppercase">INACTIVE (PURCHASE PACKAGE TO ACTIVATE)</span>
                    @endif
                </p>
            </div>
            <div class="flex flex-wrap items-center gap-3 shrink-0">
                @if($user->is_bot_active)
                    <a href="{{ route('user.bot.trading') }}" class="px-4 py-2.5 rounded-2xl bg-emerald-500/20 border-2 border-emerald-500/80 text-emerald-300 text-xs font-black uppercase tracking-wider flex items-center gap-2 shadow-[0_0_15px_rgba(16,185,129,0.4)] animate-pulse hover:scale-105 transition" title="Quant Bot is Active & Mining ROI">
                        <span class="relative flex h-2.5 w-2.5">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
                        </span>
                        <span>BOT: ACTIVE ⚡</span>
                    </a>
                @else
                    <a href="{{ route('user.bot.index') }}" class="px-4 py-2.5 rounded-2xl bg-amber-500/20 border-2 border-amber-400/80 text-amber-300 text-xs font-black uppercase tracking-wider flex items-center gap-2 shadow-[0_0_15px_rgba(243,202,82,0.4)] animate-pulse hover:scale-105 transition" title="Click to Start Quant Bot for Daily ROI">
                        <span class="h-2.5 w-2.5 rounded-full bg-amber-400"></span>
                        <span>BOT: INACTIVE (START BOT) ⚡</span>
                    </a>
                @endif

                <div
                    class="px-4 py-2.5 rounded-2xl bg-black/80 border-2 border-amber-400/80 text-amber-300 text-xs font-bold font-mono flex items-center gap-2 shadow-xl">
                    <i data-lucide="calendar" class="w-4 h-4 text-amber-400"></i>
                    <span>{{ date('l, d M Y') }}</span>
                </div>
            </div>
        </div>

        <!-- BOT STATUS ALERT BANNER -->
        <div class="relative z-10">
            @if(!$user->is_bot_active)
                <div class="p-4 sm:p-5 rounded-3xl pdf-package-card flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 shadow-xl animate-pulse">
                    <div class="flex items-start sm:items-center gap-3 text-left w-full sm:w-auto">
                        <div class="w-10 h-10 rounded-xl bg-amber-500/20 border border-amber-400/50 flex items-center justify-center text-amber-400 shrink-0 mt-0.5 sm:mt-0">
                            <i data-lucide="zap" class="w-5 h-5 text-amber-400 fill-amber-400"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-xs sm:text-sm font-black text-amber-300 uppercase tracking-wider leading-snug">⚡ ATTENTION: TRADING BOT IS INACTIVE</h4>
                            <p class="text-[11px] text-neutral-200 mt-0.5 leading-normal">Daily ROI income is ONLY paid with an active Trading Bot.</p>
                        </div>
                    </div>
                    <a href="{{ route('user.bot.index') }}" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-amber-400 to-yellow-500 hover:from-amber-300 hover:to-yellow-400 text-black font-black text-xs uppercase tracking-wider shadow-lg hover:scale-105 transition shrink-0 flex items-center gap-1.5 border border-yellow-200 whitespace-nowrap self-start sm:self-auto">
                        <i data-lucide="play-circle" class="w-4 h-4 text-black fill-black"></i>
                        <span>START BOT</span>
                    </a>
                </div>
            @else
                <div class="p-4 sm:p-5 rounded-3xl pdf-package-card flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 shadow-xl">
                    <div class="flex items-start sm:items-center gap-3 text-left w-full sm:w-auto">
                        <div class="w-10 h-10 rounded-xl bg-emerald-500/20 border border-emerald-500/50 flex items-center justify-center text-emerald-400 shrink-0 mt-0.5 sm:mt-0">
                            <i data-lucide="cpu" class="w-5 h-5 text-emerald-400"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-xs sm:text-sm font-black text-emerald-300 uppercase tracking-wider leading-snug">🚀 TRADING BOT IS ACTIVE & MINING</h4>
                            <p class="text-[11px] text-neutral-200 mt-0.5 leading-normal">Activated on <strong class="text-amber-300 font-mono">{{ $user->bot_activated_at?->format('M d, Y H:i') }}</strong>. Yield mining 24/7.</p>
                        </div>
                    </div>
                    <a href="{{ route('user.bot.trading') }}" class="px-5 py-2.5 rounded-xl bg-emerald-500/20 hover:bg-emerald-500/30 text-emerald-300 border border-emerald-500/50 font-black text-xs uppercase tracking-wider shadow-md hover:scale-105 transition shrink-0 flex items-center gap-1.5 whitespace-nowrap self-start sm:self-auto">
                        <i data-lucide="line-chart" class="w-4 h-4 text-emerald-400"></i>
                        <span>TERMINAL</span>
                    </a>
                </div>
            @endif
        </div>

        <!-- DUAL CAPPING METRIC CARDS (8X WORKING & 2X NON-WORKING IN 1 ROW) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6 relative z-10">
            <!-- 8X WORKING INCOME CAPPING METER -->
            <div class="p-5 rounded-3xl pdf-package-card space-y-3 flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-extrabold text-amber-400 uppercase tracking-widest">8X WORKING INCOME CAP</span>
                    <span class="text-xs font-bold font-mono text-emerald-400">Allowed: ${{ number_format($workingCap, 2) }}</span>
                </div>
                <div class="flex justify-between items-baseline font-mono">
                    <span class="text-xl font-black text-white">${{ number_format($workingEarned, 2) }} <span class="text-xs text-neutral-400 font-normal">Earned</span></span>
                    <span class="text-xs font-bold text-amber-300">Remaining: ${{ number_format($remainingWorkingCap, 2) }}</span>
                </div>
                @php
                    $workingPercent = $workingCap > 0 ? min(100, round(($workingEarned / $workingCap) * 100, 1)) : 0;
                @endphp
                <div class="w-full bg-black/60 rounded-full h-3 p-0.5 border border-amber-500/30 overflow-hidden">
                    <div class="bg-gradient-to-r from-amber-500 via-emerald-400 to-amber-300 h-full rounded-full transition-all duration-500" style="width: {{ $workingPercent }}%"></div>
                </div>
                <p class="text-[10px] text-neutral-400">Combines Direct, Matching, Referral ROI, Matching ROI, Upline Matching & Salary incomes.</p>
            </div>

            <!-- 2X NON-WORKING INCOME CAPPING METER -->
            <div class="p-5 rounded-3xl pdf-package-card space-y-3 flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-extrabold text-amber-400 uppercase tracking-widest">2X NON-WORKING ROI CAP</span>
                    <span class="text-xs font-bold font-mono text-emerald-400">Allowed: ${{ number_format($nonWorkingCap, 2) }}</span>
                </div>
                <div class="flex justify-between items-baseline font-mono">
                    <span class="text-xl font-black text-white">${{ number_format($nonWorkingEarned, 2) }} <span class="text-xs text-neutral-400 font-normal">Earned</span></span>
                    <span class="text-xs font-bold text-amber-300">Remaining: ${{ number_format($remainingNonWorkingCap, 2) }}</span>
                </div>
                @php
                    $nonWorkingPercent = $nonWorkingCap > 0 ? min(100, round(($nonWorkingEarned / $nonWorkingCap) * 100, 1)) : 0;
                @endphp
                <div class="w-full bg-black/60 rounded-full h-3 p-0.5 border border-amber-500/30 overflow-hidden">
                    <div class="bg-gradient-to-r from-sky-400 via-indigo-400 to-amber-300 h-full rounded-full transition-all duration-500" style="width: {{ $nonWorkingPercent }}%"></div>
                </div>
                <p class="text-[10px] text-neutral-400">Daily 0.5% ROI yield credited up to 2X (200%) of active investment package.</p>
            </div>
        </div>

        <!-- DUAL OFFICIAL MEMBER REFERRAL LINKS (LEFT & RIGHT BINARY POSITION CARDS IN 1 ROW) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6 relative z-10">
            
            <!-- LEFT REFERRAL LINK (TEAM A / LEFT POSITION) -->
            <div class="p-4 sm:p-5 rounded-3xl pdf-package-card flex flex-col xl:flex-row items-stretch xl:items-center justify-between gap-3 shadow-xl">
                <div class="flex items-center gap-3 overflow-hidden">
                    <div class="w-10 h-10 rounded-xl bg-amber-500/20 text-amber-300 border border-amber-500/40 flex items-center justify-center font-black shrink-0">
                        <i data-lucide="arrow-left-circle" class="w-5 h-5"></i>
                    </div>
                    <div class="overflow-hidden">
                        <span class="text-[11px] font-extrabold text-amber-400 uppercase tracking-wider block">LEFT REFERRAL LINK (TEAM A)</span>
                        <p class="text-xs text-neutral-200 font-mono font-bold truncate mt-0.5" title="{{ url('/user/register?sponsor=' . $user->referral_code . '&position=left') }}">
                            {{ url('/user/register?sponsor=' . $user->referral_code . '&position=left') }}
                        </p>
                    </div>
                </div>
                <button
                    onclick="navigator.clipboard.writeText('{{ url('/user/register?sponsor=' . $user->referral_code . '&position=left') }}'); showToast('Copied!', 'Left Leg (Team A) referral link copied.', 'success');"
                    class="px-3.5 py-2.5 rounded-xl pdf-gold-ribbon hover:brightness-110 text-black font-black text-xs uppercase tracking-wider transition shrink-0 flex items-center justify-center gap-1.5 cursor-pointer shadow self-start xl:self-auto">
                    <i data-lucide="copy" class="w-3.5 h-3.5 text-black"></i> Copy Left Link
                </button>
            </div>

            <!-- RIGHT REFERRAL LINK (TEAM B / RIGHT POSITION) -->
            <div class="p-4 sm:p-5 rounded-3xl pdf-package-card flex flex-col xl:flex-row items-stretch xl:items-center justify-between gap-3 shadow-xl">
                <div class="flex items-center gap-3 overflow-hidden">
                    <div class="w-10 h-10 rounded-xl bg-emerald-500/20 text-emerald-300 border border-emerald-500/40 flex items-center justify-center font-black shrink-0">
                        <i data-lucide="arrow-right-circle" class="w-5 h-5"></i>
                    </div>
                    <div class="overflow-hidden">
                        <span class="text-[11px] font-extrabold text-emerald-400 uppercase tracking-wider block">RIGHT REFERRAL LINK (TEAM B)</span>
                        <p class="text-xs text-neutral-200 font-mono font-bold truncate mt-0.5" title="{{ url('/user/register?sponsor=' . $user->referral_code . '&position=right') }}">
                            {{ url('/user/register?sponsor=' . $user->referral_code . '&position=right') }}
                        </p>
                    </div>
                </div>
                <button
                    onclick="navigator.clipboard.writeText('{{ url('/user/register?sponsor=' . $user->referral_code . '&position=right') }}'); showToast('Copied!', 'Right Leg (Team B) referral link copied.', 'success');"
                    class="px-3.5 py-2.5 rounded-xl pdf-gold-ribbon hover:brightness-110 text-black font-black text-xs uppercase tracking-wider transition shrink-0 flex items-center justify-center gap-1.5 cursor-pointer shadow self-start xl:self-auto">
                    <i data-lucide="copy" class="w-3.5 h-3.5 text-black"></i> Copy Right Link
                </button>
            </div>

        </div>

        <!-- 5 MAIN FINANCIAL WALLET & CAPITAL CARDS -->
        <div class="five-cards-row relative z-10">

            <!-- WALLET 1: Deposit Wallet -->
            <div class="p-4 rounded-3xl pdf-package-card relative overflow-hidden group flex flex-col justify-between">
                <div class="flex justify-between items-center mb-3">
                    <div class="w-9 h-9 rounded-xl pdf-gold-badge flex items-center justify-center font-black shrink-0">
                        <i data-lucide="wallet" class="w-4 h-4 text-black"></i>
                    </div>
                    <a href="{{ route('user.deposits.index') }}"
                        class="text-[10px] font-black pdf-gold-ribbon px-2.5 py-0.5 rounded-full shrink-0">
                        + Add Fund
                    </a>
                </div>
                <div>
                    <div class="text-[11px] font-extrabold text-neutral-300 uppercase tracking-wider truncate">Deposit Wallet (USDT)</div>
                    <h3 class="text-xl sm:text-2xl font-black text-emerald-400 font-mono mt-0.5">
                        ${{ number_format($user->deposit_wallet, 2) }}</h3>
                </div>
            </div>

            <!-- WALLET 2: Earning Wallet -->
            <div class="p-4 rounded-3xl pdf-package-card relative overflow-hidden group flex flex-col justify-between">
                <div class="flex justify-between items-center mb-3">
                    <div class="w-9 h-9 rounded-xl pdf-gold-badge flex items-center justify-center font-black shrink-0">
                        <i data-lucide="coins" class="w-4 h-4 text-black"></i>
                    </div>
                    <a href="{{ route('user.withdrawals.index') }}"
                        class="text-[10px] font-black text-amber-300 font-mono bg-black/60 px-2.5 py-0.5 rounded-full border border-amber-400/50 hover:bg-amber-400 hover:text-black transition shrink-0">
                        Withdraw &rarr;
                    </a>
                </div>
                <div>
                    <div class="text-[11px] font-extrabold text-neutral-300 uppercase tracking-wider truncate">Earning Wallet</div>
                    <h3 class="text-xl sm:text-2xl font-black text-amber-300 font-mono mt-0.5">
                        ${{ number_format($user->earning_wallet, 2) }}</h3>
                </div>
            </div>

            <!-- WALLET 3: Total Withdrawn -->
            <div class="p-4 rounded-3xl pdf-package-card relative overflow-hidden group flex flex-col justify-between">
                <div class="flex justify-between items-center mb-3">
                    <div class="w-9 h-9 rounded-xl pdf-gold-badge flex items-center justify-center font-black shrink-0">
                        <i data-lucide="arrow-up-right" class="w-4 h-4 text-black"></i>
                    </div>
                    <a href="{{ route('user.withdrawals.history') }}"
                        class="text-[10px] font-black text-rose-300 font-mono bg-black/60 px-2.5 py-0.5 rounded-full border border-rose-400/50 hover:bg-rose-400 hover:text-black transition shrink-0">
                        History &rarr;
                    </a>
                </div>
                <div>
                    <div class="text-[11px] font-extrabold text-neutral-300 uppercase tracking-wider truncate">Withdrawal Wallet</div>
                    <h3 class="text-xl sm:text-2xl font-black text-rose-300 font-mono mt-0.5">
                        ${{ number_format($totalWithdrawn, 2) }}</h3>
                </div>
            </div>

            <!-- WALLET 4: Total Invested Capital -->
            <div class="p-4 rounded-3xl pdf-package-card relative overflow-hidden group flex flex-col justify-between">
                <div class="flex justify-between items-center mb-3">
                    <div class="w-9 h-9 rounded-xl pdf-gold-badge flex items-center justify-center font-black shrink-0">
                        <i data-lucide="package-check" class="w-4 h-4 text-black"></i>
                    </div>
                    <span
                        class="text-[10px] font-black text-sky-300 font-mono bg-black/60 px-2 py-0.5 rounded-full border border-sky-400/50 shrink-0">
                        {{ $activeInvestmentsCount }} Active
                    </span>
                </div>
                <div>
                    <div class="text-[11px] font-extrabold text-neutral-300 uppercase tracking-wider truncate">Invested Capital</div>
                    <h3 class="text-xl sm:text-2xl font-black text-sky-300 font-mono mt-0.5">
                        ${{ number_format($totalInvested, 2) }}</h3>
                </div>
            </div>

            <!-- WALLET 5: Total Income Earned -->
            <div class="p-4 rounded-3xl pdf-package-card relative overflow-hidden group flex flex-col justify-between">
                <div class="flex justify-between items-center mb-3">
                    <div class="w-9 h-9 rounded-xl pdf-gold-badge flex items-center justify-center font-black shrink-0">
                        <i data-lucide="award" class="w-4 h-4 text-black"></i>
                    </div>
                    <span
                        class="text-[10px] font-black text-purple-300 font-mono bg-black/60 px-2 py-0.5 rounded-full border border-purple-400/50 shrink-0">Total
                        Earned</span>
                </div>
                <div>
                    <div class="text-[11px] font-extrabold text-neutral-300 uppercase tracking-wider truncate">Total Income Earned</div>
                    <h3 class="text-xl sm:text-2xl font-black text-purple-300 font-mono mt-0.5">
                        ${{ number_format($totalIncomeEarned, 2) }}</h3>
                </div>
            </div>

        </div>

        <!-- COMPACT FINANCIAL OVERVIEW EARNINGS SUMMARY TABLE CARD (ALL 7 INCOMES) -->
        <div class="p-4 sm:p-5 rounded-3xl pdf-package-card space-y-3 relative z-10 shadow-xl overflow-hidden">
            <div class="flex items-center justify-between border-b border-amber-500/20 pb-3">
                <div>
                    <span class="text-amber-400 font-extrabold text-[10px] uppercase tracking-widest block mb-0.5">DEX TRADE BUSINESS PLAN</span>
                    <h2 class="text-base sm:text-lg font-black text-white font-heading">7 Types of Income Overview</h2>
                </div>
                <a href="{{ route('user.reports.summary') }}"
                    class="px-3 py-1 rounded-full bg-black/60 hover:bg-amber-400 hover:text-black border border-amber-400/60 text-amber-300 text-[11px] font-bold font-heading uppercase tracking-wider transition inline-flex items-center gap-1 shadow">
                    <span>View History</span>
                    <i data-lucide="arrow-right" class="w-3 h-3"></i>
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="border-b border-amber-500/20 text-amber-400 font-extrabold text-[10px] tracking-wider uppercase font-mono">
                        <tr>
                            <th class="py-2 px-3">INCOME TYPE</th>
                            <th class="py-2 px-3 text-center">TODAY INCOME</th>
                            <th class="py-2 px-3 text-right">TOTAL EARNED</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-amber-500/10 text-xs">

                        <!-- 1. ROI Income -->
                        <tr class="hover:bg-amber-500/5 transition">
                            <td class="py-2 px-3 font-semibold text-white flex items-center gap-2.5">
                                <span class="w-6 h-6 rounded-lg bg-amber-500/20 text-amber-300 border border-amber-500/40 flex items-center justify-center shrink-0">
                                    <i data-lucide="line-chart" class="w-3 h-3"></i>
                                </span>
                                <div>
                                    <a href="{{ route('user.reports.roi') }}" class="hover:text-amber-300 transition text-xs font-bold">Daily ROI Income</a>
                                    <span class="text-[9px] text-neutral-400 font-normal block">0.5% Daily for 400 Days (2X Non-Working Cap)</span>
                                </div>
                            </td>
                            <td class="py-2 px-3 text-center font-mono font-bold text-emerald-400 text-xs">${{ number_format($todayRoiEarned, 2) }}</td>
                            <td class="py-2 px-3 text-right font-mono font-bold text-emerald-400 text-xs sm:text-sm">${{ number_format($totalRoiEarned, 2) }}</td>
                        </tr>

                        <!-- 2. Direct Income -->
                        <tr class="hover:bg-amber-500/5 transition">
                            <td class="py-2 px-3 font-semibold text-white flex items-center gap-2.5">
                                <span class="w-6 h-6 rounded-lg bg-sky-500/20 text-sky-400 border border-sky-500/40 flex items-center justify-center shrink-0">
                                    <i data-lucide="user-plus" class="w-3 h-3"></i>
                                </span>
                                <div>
                                    <a href="{{ route('user.reports.direct') }}" class="hover:text-amber-300 transition text-xs font-bold">Direct Income</a>
                                    <span class="text-[9px] text-neutral-400 font-normal block">10% Instant Direct Referral Bonus</span>
                                </div>
                            </td>
                            <td class="py-2 px-3 text-center font-mono font-bold text-emerald-400 text-xs">${{ number_format($todayDirectEarned, 2) }}</td>
                            <td class="py-2 px-3 text-right font-mono font-bold text-emerald-400 text-xs sm:text-sm">${{ number_format($totalDirectEarned, 2) }}</td>
                        </tr>

                        <!-- 3. Matching Income -->
                        <tr class="hover:bg-amber-500/5 transition">
                            <td class="py-2 px-3 font-semibold text-white flex items-center gap-2.5">
                                <span class="w-6 h-6 rounded-lg bg-purple-500/20 text-purple-400 border border-purple-500/40 flex items-center justify-center shrink-0">
                                    <i data-lucide="git-merge" class="w-3 h-3"></i>
                                </span>
                                <div>
                                    <a href="{{ route('user.reports.matching') }}" class="hover:text-amber-300 transition text-xs font-bold">Matching Income</a>
                                    <span class="text-[9px] text-neutral-400 font-normal block">10% Binary Matching Bonus (2:1 / 1:2 Left/Right Requirement)</span>
                                </div>
                            </td>
                            <td class="py-2 px-3 text-center font-mono font-bold text-emerald-400 text-xs">${{ number_format($todayMatchingEarned, 2) }}</td>
                            <td class="py-2 px-3 text-right font-mono font-bold text-emerald-400 text-xs sm:text-sm">${{ number_format($totalMatchingEarned, 2) }}</td>
                        </tr>

                        <!-- 4. Referral ROI Income -->
                        <tr class="hover:bg-amber-500/5 transition">
                            <td class="py-2 px-3 font-semibold text-white flex items-center gap-2.5">
                                <span class="w-6 h-6 rounded-lg bg-teal-500/20 text-teal-400 border border-teal-500/40 flex items-center justify-center shrink-0">
                                    <i data-lucide="repeat" class="w-3 h-3"></i>
                                </span>
                                <div>
                                    <a href="{{ route('user.reports.referral-roi') }}" class="hover:text-amber-300 transition text-xs font-bold">Referral ROI Income</a>
                                    <span class="text-[9px] text-neutral-400 font-normal block">0.5% Daily from Direct Members Total Investment (150 Days)</span>
                                </div>
                            </td>
                            <td class="py-2 px-3 text-center font-mono font-bold text-emerald-400 text-xs">${{ number_format($todayReferralRoiEarned, 2) }}</td>
                            <td class="py-2 px-3 text-right font-mono font-bold text-emerald-400 text-xs sm:text-sm">${{ number_format($totalReferralRoiEarned, 2) }}</td>
                        </tr>

                        <!-- 5. Matching ROI Income -->
                        <tr class="hover:bg-amber-500/5 transition">
                            <td class="py-2 px-3 font-semibold text-white flex items-center gap-2.5">
                                <span class="w-6 h-6 rounded-lg bg-indigo-500/20 text-indigo-400 border border-indigo-500/40 flex items-center justify-center shrink-0">
                                    <i data-lucide="layers" class="w-3 h-3"></i>
                                </span>
                                <div>
                                    <a href="{{ route('user.reports.matching-roi') }}" class="hover:text-amber-300 transition text-xs font-bold">Matching ROI Income</a>
                                    <span class="text-[9px] text-neutral-400 font-normal block">0.5% Daily of Daily Matching Bonus (150 Days)</span>
                                </div>
                            </td>
                            <td class="py-2 px-3 text-center font-mono font-bold text-emerald-400 text-xs">${{ number_format($todayMatchingRoiEarned, 2) }}</td>
                            <td class="py-2 px-3 text-right font-mono font-bold text-emerald-400 text-xs sm:text-sm">${{ number_format($totalMatchingRoiEarned, 2) }}</td>
                        </tr>

                        <!-- 6. Upline Matching Income -->
                        <tr class="hover:bg-amber-500/5 transition">
                            <td class="py-2 px-3 font-semibold text-white flex items-center gap-2.5">
                                <span class="w-6 h-6 rounded-lg bg-rose-500/20 text-rose-400 border border-rose-500/40 flex items-center justify-center shrink-0">
                                    <i data-lucide="share-2" class="w-3 h-3"></i>
                                </span>
                                <div>
                                    <a href="{{ route('user.reports.upline-matching') }}" class="hover:text-amber-300 transition text-xs font-bold">Upline Matching Income</a>
                                    <span class="text-[9px] text-neutral-400 font-normal block">10% Sponsor Matching Pool Shared Equally</span>
                                </div>
                            </td>
                            <td class="py-2 px-3 text-center font-mono font-bold text-emerald-400 text-xs">${{ number_format($todayUplineMatchingEarned, 2) }}</td>
                            <td class="py-2 px-3 text-right font-mono font-bold text-emerald-400 text-xs sm:text-sm">${{ number_format($totalUplineMatchingEarned, 2) }}</td>
                        </tr>

                        <!-- 7. Salary Income -->
                        <tr class="hover:bg-amber-500/5 transition">
                            <td class="py-2 px-3 font-semibold text-white flex items-center gap-2.5">
                                <span class="w-6 h-6 rounded-lg bg-orange-500/20 text-orange-400 border border-orange-500/40 flex items-center justify-center shrink-0">
                                    <i data-lucide="award" class="w-3 h-3"></i>
                                </span>
                                <div>
                                    <a href="{{ route('user.reports.salary') }}" class="hover:text-amber-300 transition text-xs font-bold">Salary Income</a>
                                    <span class="text-[9px] text-neutral-400 font-normal block">17 Milestone Rank Salaries ($50/mo to $12 Lakh/mo over 5–25 Months)</span>
                                </div>
                            </td>
                            <td class="py-2 px-3 text-center font-mono font-bold text-emerald-400 text-xs">${{ number_format($todaySalaryEarned, 2) }}</td>
                            <td class="py-2 px-3 text-right font-mono font-bold text-emerald-400 text-xs sm:text-sm">${{ number_format($totalSalaryEarned, 2) }}</td>
                        </tr>

                    </tbody>
                </table>
            </div>
        </div>

        <!-- DYNAMIC SIDE-BY-SIDE TABLES GRID -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 pt-4 relative z-10">

            <!-- LEFT: MY ACTIVE PACKAGES -->
            <div class="p-6 rounded-3xl pdf-package-card space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-amber-500/20">
                    <div class="px-3.5 py-1 rounded-xl pdf-gold-ribbon font-black text-xs uppercase tracking-wider shadow">
                        My Active Packages
                    </div>
                    <a href="{{ route('user.packages.history') }}" class="text-xs text-amber-300 font-bold hover:underline">View
                        All &rarr;</a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="text-amber-400 font-bold uppercase border-b border-amber-500/20">
                            <tr>
                                <th class="pb-2">PACKAGE</th>
                                <th class="pb-2">INVESTED</th>
                                <th class="pb-2">DAILY ROI</th>
                                <th class="pb-2">STATUS</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-amber-500/10 text-neutral-300 font-mono">
                            @forelse($activePackages as $ap)
                                <tr>
                                    <td class="py-2.5 font-sans font-bold text-white">{{ $ap->package->name ?? 'Dex Trade Package' }}</td>
                                    <td class="py-2.5 font-black text-amber-300">${{ number_format($ap->invested_amount, 2) }}</td>
                                    <td class="py-2.5 font-bold text-emerald-400">{{ number_format($ap->daily_roi, 2) }}%</td>
                                    <td class="py-2.5">
                                        <span
                                            class="px-2 py-0.5 rounded text-[9px] font-black bg-emerald-500/20 text-emerald-400 border border-emerald-500/40 uppercase">ACTIVE</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-4 text-center text-neutral-500 font-sans">No active packages found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- RIGHT: RECENT TRANSACTIONS LOG -->
            <div class="p-6 rounded-3xl pdf-package-card space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-amber-500/20">
                    <div class="px-3.5 py-1 rounded-xl pdf-gold-ribbon font-black text-xs uppercase tracking-wider shadow">
                        Recent Transactions
                    </div>
                    <a href="{{ route('user.transactions.index') }}" class="text-xs text-amber-300 font-bold hover:underline">View
                        All &rarr;</a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="text-amber-400 font-bold uppercase border-b border-amber-500/20">
                            <tr>
                                <th class="pb-2">TXN ID</th>
                                <th class="pb-2">TYPE</th>
                                <th class="pb-2">AMOUNT</th>
                                <th class="pb-2">DATE</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-amber-500/10 text-neutral-300 font-mono">
                            @forelse($recentTransactions as $rt)
                                <tr>
                                    <td class="py-2.5 font-bold text-amber-400 text-[11px]">{{ $rt->txn_number }}</td>
                                    <td class="py-2.5 font-sans capitalize text-neutral-200">{{ str_replace('_', ' ', $rt->type) }}</td>
                                    <td class="py-2.5 font-black {{ $rt->trx_type === '+' ? 'text-emerald-400' : 'text-rose-400' }}">
                                        {{ $rt->trx_type }}${{ number_format($rt->amount, 2) }}
                                    </td>
                                    <td class="py-2.5 text-[10px] text-neutral-400">{{ $rt->created_at->format('M d, H:i') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-4 text-center text-neutral-500 font-sans">No recent transactions found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

    </div>
@endsection
