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

        <!-- TOP 5 FINANCIAL & WALLET METRIC CARDS -->
        <div class="five-cards-row relative z-10">
            <!-- 1. DEPOSIT WALLET CARD -->
            <div class="p-4 sm:p-5 rounded-3xl pdf-package-card flex flex-col justify-between space-y-2 shadow-xl hover:scale-[1.02] transition">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-black text-amber-400 uppercase tracking-widest">DEPOSIT WALLET</span>
                    <div class="w-8 h-8 rounded-xl bg-amber-500/20 border border-amber-400/40 text-amber-300 flex items-center justify-center shrink-0">
                        <i data-lucide="wallet" class="w-4 h-4"></i>
                    </div>
                </div>
                <div>
                    <h3 class="text-xl sm:text-2xl font-black text-white font-mono">${{ number_format($user->deposit_wallet, 2) }}</h3>
                </div>
                <div class="pt-1.5 border-t border-amber-500/20 flex items-center justify-between text-[11px]">
                    <span class="text-neutral-400">Available Balance</span>
                    <a href="{{ route('user.deposits.index') }}" class="text-amber-300 font-bold hover:underline flex items-center gap-0.5">
                        <span>Deposit</span> &rarr;
                    </a>
                </div>
            </div>

            <!-- 2. EARNING WALLET CARD -->
            <div class="p-4 sm:p-5 rounded-3xl pdf-package-card flex flex-col justify-between space-y-2 shadow-xl hover:scale-[1.02] transition">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-black text-emerald-400 uppercase tracking-widest">EARNING WALLET</span>
                    <div class="w-8 h-8 rounded-xl bg-emerald-500/20 border border-emerald-500/40 text-emerald-300 flex items-center justify-center shrink-0">
                        <i data-lucide="banknote" class="w-4 h-4"></i>
                    </div>
                </div>
                <div>
                    <h3 class="text-xl sm:text-2xl font-black text-emerald-400 font-mono">${{ number_format($user->earning_wallet, 2) }}</h3>
                </div>
                <div class="pt-1.5 border-t border-amber-500/20 flex items-center justify-between text-[11px]">
                    <span class="text-neutral-400">Withdrawable Income</span>
                    <a href="{{ route('user.withdrawals.index') }}" class="text-emerald-300 font-bold hover:underline flex items-center gap-0.5">
                        <span>Withdraw</span> &rarr;
                    </a>
                </div>
            </div>

            <!-- 3. TOTAL ACTIVE CAPITAL -->
            <div class="p-4 sm:p-5 rounded-3xl pdf-package-card flex flex-col justify-between space-y-2 shadow-xl hover:scale-[1.02] transition">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-black text-sky-400 uppercase tracking-widest">ACTIVE CAPITAL</span>
                    <div class="w-8 h-8 rounded-xl bg-sky-500/20 border border-sky-500/40 text-sky-300 flex items-center justify-center shrink-0">
                        <i data-lucide="pie-chart" class="w-4 h-4"></i>
                    </div>
                </div>
                <div>
                    <h3 class="text-xl sm:text-2xl font-black text-white font-mono">${{ number_format($activeInvestmentAmount, 2) }}</h3>
                </div>
                <div class="pt-1.5 border-t border-amber-500/20 flex items-center justify-between text-[11px]">
                    <span class="text-neutral-400">{{ $activeInvestmentsCount }} Active Packages</span>
                    <a href="{{ route('user.packages.index') }}" class="text-sky-300 font-bold hover:underline flex items-center gap-0.5">
                        <span>Buy More</span> &rarr;
                    </a>
                </div>
            </div>

            <!-- 4. TOTAL INCOME EARNED -->
            <div class="p-4 sm:p-5 rounded-3xl pdf-package-card flex flex-col justify-between space-y-2 shadow-xl hover:scale-[1.02] transition">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-black text-yellow-400 uppercase tracking-widest">TOTAL EARNED</span>
                    <div class="w-8 h-8 rounded-xl bg-yellow-500/20 border border-yellow-500/40 text-yellow-300 flex items-center justify-center shrink-0">
                        <i data-lucide="trending-up" class="w-4 h-4"></i>
                    </div>
                </div>
                <div>
                    <h3 class="text-xl sm:text-2xl font-black text-amber-300 font-mono">${{ number_format($totalIncomeEarned, 2) }}</h3>
                </div>
                <div class="pt-1.5 border-t border-amber-500/20 flex items-center justify-between text-[11px]">
                    <span class="text-neutral-400">All 7 Incomes</span>
                    <a href="{{ route('user.reports.summary') }}" class="text-yellow-300 font-bold hover:underline flex items-center gap-0.5">
                        <span>Summary</span> &rarr;
                    </a>
                </div>
            </div>

            <!-- 5. TOTAL WITHDRAWN -->
            <div class="p-4 sm:p-5 rounded-3xl pdf-package-card flex flex-col justify-between space-y-2 shadow-xl hover:scale-[1.02] transition">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-black text-rose-400 uppercase tracking-widest">TOTAL WITHDRAWN</span>
                    <div class="w-8 h-8 rounded-xl bg-rose-500/20 border border-rose-500/40 text-rose-300 flex items-center justify-center shrink-0">
                        <i data-lucide="arrow-down-circle" class="w-4 h-4"></i>
                    </div>
                </div>
                <div>
                    <h3 class="text-xl sm:text-2xl font-black text-white font-mono">${{ number_format($totalWithdrawn, 2) }}</h3>
                </div>
                <div class="pt-1.5 border-t border-amber-500/20 flex items-center justify-between text-[11px]">
                    <span class="text-neutral-400">Completed Payouts</span>
                    <a href="{{ route('user.withdrawals.history') }}" class="text-rose-300 font-bold hover:underline flex items-center gap-0.5">
                        <span>History</span> &rarr;
                    </a>
                </div>
            </div>
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

        <!-- TEAM OVERVIEW & 7 TYPES OF INCOME OVERVIEW -->
        <div class="relative z-10 grid grid-cols-1 items-stretch gap-4 sm:grid-cols-2">

            <!-- COLUMN 1: NETWORK OVERVIEW / TEAM OVERVIEW SECTION -->
            <div class="flex h-full flex-col justify-between space-y-4 overflow-hidden rounded-3xl border-2 border-amber-500/80 p-5 shadow-2xl pdf-package-card sm:p-6">
                <!-- Header Bar -->
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 border-b border-amber-500/20 pb-3">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="text-[10px] font-black text-amber-400 uppercase tracking-widest">NETWORK OVERVIEW</span>
                            <span class="px-2 py-0.5 rounded-full bg-amber-500/20 text-amber-300 border border-amber-400/40 text-[9px] font-black uppercase flex items-center gap-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-400 animate-pulse"></span> LIVE NETWORK
                            </span>
                        </div>
                        <h2 class="text-xl sm:text-2xl font-black text-white font-heading mt-0.5">Team Overview</h2>
                    </div>
                    <div class="flex items-center gap-2 flex-wrap">
                        <a href="{{ route('user.network.tree') }}" class="px-3.5 py-1.5 rounded-xl bg-gradient-to-r from-amber-400 to-yellow-500 hover:brightness-110 text-black font-black text-xs uppercase tracking-wider transition shadow flex items-center gap-1">
                            <i data-lucide="git-fork" class="w-3.5 h-3.5"></i>
                            <span>TREE VIEW &rarr;</span>
                        </a>
                        <a href="{{ route('user.network.direct') }}" class="px-3.5 py-1.5 rounded-xl bg-black/80 hover:bg-black border border-amber-400/50 text-amber-300 font-black text-xs uppercase tracking-wider transition shadow flex items-center gap-1">
                            <i data-lucide="users" class="w-3.5 h-3.5"></i>
                            <span>DIRECT TEAM &rarr;</span>
                        </a>
                    </div>
                </div>

                <!-- Top Leg Volume Cards: Left Leg Business vs Right Leg Business -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <!-- Left Leg (Team A) Business Card -->
                    <div class="p-3 rounded-2xl bg-black/60 border border-emerald-500/40 hover:border-emerald-400 transition flex items-center justify-between gap-2 shadow">
                        <div class="flex items-center gap-2.5 min-w-0">
                            <div class="w-8 h-8 rounded-xl bg-emerald-500/20 text-emerald-400 border border-emerald-500/40 flex items-center justify-center font-black shrink-0">
                                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                            </div>
                            <div class="min-w-0">
                                <span class="text-[9.5px] font-black uppercase text-neutral-400 tracking-wider truncate block">👈 LEFT BUSINESS</span>
                                <h3 class="text-base sm:text-lg font-black text-emerald-400 font-mono leading-tight">${{ number_format($teamOverview['left_business'], 2) }}</h3>
                                <span class="text-[9px] font-bold text-neutral-300 font-mono block truncate">{{ number_format($teamOverview['left_team_count']) }} Members ({{ $teamOverview['left_active_team'] }} Active)</span>
                            </div>
                        </div>
                    </div>

                    <!-- Right Leg (Team B) Business Card -->
                    <div class="p-3 rounded-2xl bg-black/60 border border-amber-500/40 hover:border-amber-400 transition flex items-center justify-between gap-2 shadow">
                        <div class="flex items-center gap-2.5 min-w-0">
                            <div class="w-8 h-8 rounded-xl bg-amber-500/20 text-amber-300 border border-amber-500/40 flex items-center justify-center font-black shrink-0">
                                <i data-lucide="arrow-right" class="w-4 h-4"></i>
                            </div>
                            <div class="min-w-0">
                                <span class="text-[9.5px] font-black uppercase text-neutral-400 tracking-wider truncate block">RIGHT BUSINESS 👉</span>
                                <h3 class="text-base sm:text-lg font-black text-amber-300 font-mono leading-tight">${{ number_format($teamOverview['right_business'], 2) }}</h3>
                                <span class="text-[9px] font-bold text-neutral-300 font-mono block truncate">{{ number_format($teamOverview['right_team_count']) }} Members ({{ $teamOverview['right_active_team'] }} Active)</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Detailed Team Metric Rows -->
                <div class="space-y-2.5 flex-1 flex flex-col justify-between">
                    <!-- Row 1: Active Directs & Business -->
                    <div class="p-3 rounded-2xl bg-black/50 border border-amber-500/30 hover:border-amber-400/60 transition flex items-center justify-between gap-2">
                        <div class="flex items-center gap-2.5 min-w-0">
                            <div class="w-8 h-8 rounded-xl bg-emerald-500/20 text-emerald-400 border border-emerald-500/40 flex items-center justify-center shrink-0">
                                <i data-lucide="user-check" class="w-4 h-4"></i>
                            </div>
                            <div class="min-w-0">
                                <h4 class="text-xs font-black text-white font-heading truncate">Active Directs & Business</h4>
                                <p class="text-[10px] text-neutral-300 font-mono truncate">Volume: <strong class="text-emerald-400">${{ number_format($teamOverview['direct_business'], 2) }}</strong> (L:{{ $teamOverview['direct_left'] }} | R:{{ $teamOverview['direct_right'] }})</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-1 shrink-0">
                            <span class="px-2 py-0.5 rounded bg-emerald-500/20 text-emerald-400 border border-emerald-500/40 font-mono font-bold text-[10px] uppercase">
                                {{ $teamOverview['direct_active'] }} Active
                            </span>
                        </div>
                    </div>

                    <!-- Row 2: Inactive Direct Count -->
                    <div class="p-3 rounded-2xl bg-black/50 border border-amber-500/30 hover:border-amber-400/60 transition flex items-center justify-between gap-2">
                        <div class="flex items-center gap-2.5 min-w-0">
                            <div class="w-8 h-8 rounded-xl bg-amber-500/20 text-amber-400 border border-amber-500/40 flex items-center justify-center shrink-0">
                                <i data-lucide="user-minus" class="w-4 h-4"></i>
                            </div>
                            <div class="min-w-0">
                                <h4 class="text-xs font-black text-white font-heading truncate">Inactive Direct Count</h4>
                                <p class="text-[10px] text-neutral-300 font-mono truncate">Awaiting package activation</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-1 shrink-0">
                            <span class="px-2 py-0.5 rounded bg-rose-500/20 text-rose-400 border border-rose-500/40 font-mono font-bold text-[10px] uppercase">
                                {{ $teamOverview['direct_inactive'] }} Inactive
                            </span>
                        </div>
                    </div>

                    <!-- Row 3: Total My Team Count -->
                    <div class="p-3 rounded-2xl bg-black/50 border border-amber-500/30 hover:border-amber-400/60 transition flex items-center justify-between gap-2">
                        <div class="flex items-center gap-2.5 min-w-0">
                            <div class="w-8 h-8 rounded-xl bg-yellow-500/20 text-yellow-400 border border-yellow-500/40 flex items-center justify-center shrink-0">
                                <i data-lucide="users" class="w-4 h-4"></i>
                            </div>
                            <div class="min-w-0">
                                <h4 class="text-xs font-black text-white font-heading truncate">Total My Team Count</h4>
                                <p class="text-[10px] text-neutral-300 font-mono truncate">Full downline (L:{{ number_format($teamOverview['left_team_count']) }} | R:{{ number_format($teamOverview['right_team_count']) }})</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-1 shrink-0">
                            <span class="px-2.5 py-1 rounded-xl bg-gradient-to-r from-amber-400 to-yellow-500 text-black font-black font-mono text-xs shadow">
                                {{ number_format($teamOverview['total_team_count']) }} Members
                            </span>
                        </div>
                    </div>

                    <!-- Row 4: Total Active Team -->
                    <div class="p-3 rounded-2xl bg-black/50 border border-emerald-500/30 hover:border-emerald-400/60 transition flex items-center justify-between gap-2">
                        <div class="flex items-center gap-2.5 min-w-0">
                            <div class="w-8 h-8 rounded-xl bg-emerald-500/20 text-emerald-400 border border-emerald-500/40 flex items-center justify-center shrink-0">
                                <i data-lucide="shield-check" class="w-4 h-4"></i>
                            </div>
                            <div class="min-w-0">
                                <h4 class="text-xs font-black text-white font-heading truncate">Total Active Team</h4>
                                <p class="text-[10px] text-neutral-300 font-mono truncate">Paid members (L:{{ number_format($teamOverview['left_active_team']) }} | R:{{ number_format($teamOverview['right_active_team']) }})</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-1 shrink-0">
                            <span class="px-2.5 py-1 rounded-xl bg-emerald-500/20 text-emerald-400 border border-emerald-400/50 font-black font-mono text-xs uppercase">
                                {{ number_format($teamOverview['total_active_team']) }} Active
                            </span>
                        </div>
                    </div>

                    <!-- Row 5: Total Inactive Team -->
                    <div class="p-3 rounded-2xl bg-black/50 border border-rose-500/30 hover:border-rose-400/60 transition flex items-center justify-between gap-2">
                        <div class="flex items-center gap-2.5 min-w-0">
                            <div class="w-8 h-8 rounded-xl bg-rose-500/20 text-rose-400 border border-rose-500/40 flex items-center justify-center shrink-0">
                                <i data-lucide="user-x" class="w-4 h-4"></i>
                            </div>
                            <div class="min-w-0">
                                <h4 class="text-xs font-black text-white font-heading truncate">Total Inactive Team</h4>
                                <p class="text-[10px] text-neutral-300 font-mono truncate">Unpaid members (L:{{ number_format($teamOverview['left_inactive_team']) }} | R:{{ number_format($teamOverview['right_inactive_team']) }})</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-1 shrink-0">
                            <span class="px-2.5 py-1 rounded-xl bg-rose-500/20 text-rose-400 border border-rose-400/50 font-black font-mono text-xs uppercase">
                                {{ number_format($teamOverview['total_inactive_team']) }} Inactive
                            </span>
                        </div>
                    </div>

                    <!-- Row 6: Total Team Business Volume -->
                    <div class="p-3 rounded-2xl bg-black/50 border border-amber-500/30 hover:border-amber-400/60 transition flex items-center justify-between gap-2">
                        <div class="flex items-center gap-2.5 min-w-0">
                            <div class="w-8 h-8 rounded-xl bg-amber-500/20 text-amber-300 border border-amber-500/40 flex items-center justify-center shrink-0">
                                <i data-lucide="bar-chart-3" class="w-4 h-4"></i>
                            </div>
                            <div class="min-w-0">
                                <h4 class="text-xs font-black text-white font-heading truncate">Total Team Business Volume</h4>
                                <p class="text-[10px] text-neutral-300 font-mono truncate">L: <strong class="text-emerald-400">${{ number_format($teamOverview['left_business'], 2) }}</strong> | R: <strong class="text-amber-300">${{ number_format($teamOverview['right_business'], 2) }}</strong></p>
                            </div>
                        </div>
                        <div class="flex items-center gap-1 shrink-0">
                            <span class="px-2.5 py-1 rounded-xl bg-black/80 border border-amber-400 text-emerald-400 font-black font-mono text-xs shadow">
                                ${{ number_format($teamOverview['total_business'], 2) }}
                            </span>
                        </div>
                    </div>

                </div>
            </div>

            <!-- COLUMN 2: COMPACT FINANCIAL OVERVIEW EARNINGS SUMMARY TABLE CARD (ALL 7 INCOMES) -->
            <div class="flex h-full flex-col justify-between space-y-4 overflow-hidden rounded-3xl border-2 border-amber-500/80 p-5 shadow-2xl pdf-package-card sm:p-6">
                <div class="flex items-center justify-between border-b border-amber-500/20 pb-3">
                    <div>
                        <span class="text-amber-400 font-extrabold text-[10px] uppercase tracking-widest block mb-0.5">DEX TRADE BUSINESS PLAN</span>
                        <h2 class="text-xl sm:text-2xl font-black text-white font-heading">7 Types of Income Overview</h2>
                    </div>
                    <a href="{{ route('user.reports.summary') }}"
                        class="px-3 py-1.5 rounded-full bg-black/60 hover:bg-amber-400 hover:text-black border border-amber-400/60 text-amber-300 text-[11px] font-bold font-heading uppercase tracking-wider transition inline-flex items-center gap-1 shadow shrink-0">
                        <span>View History</span>
                        <i data-lucide="arrow-right" class="w-3 h-3"></i>
                    </a>
                </div>

                <div class="overflow-x-auto flex-1">
                    <table class="w-full text-left border-collapse">
                        <thead class="border-b border-amber-500/20 text-amber-400 font-extrabold text-[10px] tracking-wider uppercase font-mono">
                            <tr>
                                <th class="py-2.5 px-3">INCOME TYPE</th>
                                <th class="py-2.5 px-3 text-center">TODAY</th>
                                <th class="py-2.5 px-3 text-right">TOTAL EARNED</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-amber-500/10 text-xs">

                            <!-- 1. ROI Income -->
                            <tr class="hover:bg-amber-500/5 transition">
                                <td class="py-2.5 px-3 font-semibold text-white flex items-center gap-2">
                                    <span class="w-6 h-6 rounded-lg bg-amber-500/20 text-amber-300 border border-amber-500/40 flex items-center justify-center shrink-0">
                                        <i data-lucide="line-chart" class="w-3 h-3"></i>
                                    </span>
                                    <div>
                                        <a href="{{ route('user.reports.roi') }}" class="hover:text-amber-300 transition text-xs font-bold block">Daily ROI Income</a>
                                        <span class="text-[9px] text-neutral-400 font-normal block">0.5% Daily for 400 Days</span>
                                    </div>
                                </td>
                                <td class="py-2.5 px-3 text-center font-mono font-bold text-emerald-400 text-xs">${{ number_format($todayRoiEarned, 2) }}</td>
                                <td class="py-2.5 px-3 text-right font-mono font-bold text-emerald-400 text-xs sm:text-sm">${{ number_format($totalRoiEarned, 2) }}</td>
                            </tr>

                            <!-- 2. Direct Income -->
                            <tr class="hover:bg-amber-500/5 transition">
                                <td class="py-2.5 px-3 font-semibold text-white flex items-center gap-2">
                                    <span class="w-6 h-6 rounded-lg bg-sky-500/20 text-sky-400 border border-sky-500/40 flex items-center justify-center shrink-0">
                                        <i data-lucide="user-plus" class="w-3 h-3"></i>
                                    </span>
                                    <div>
                                        <a href="{{ route('user.reports.direct') }}" class="hover:text-amber-300 transition text-xs font-bold block">Direct Income</a>
                                        <span class="text-[9px] text-neutral-400 font-normal block">10% Instant Bonus</span>
                                    </div>
                                </td>
                                <td class="py-2.5 px-3 text-center font-mono font-bold text-emerald-400 text-xs">${{ number_format($todayDirectEarned, 2) }}</td>
                                <td class="py-2.5 px-3 text-right font-mono font-bold text-emerald-400 text-xs sm:text-sm">${{ number_format($totalDirectEarned, 2) }}</td>
                            </tr>

                            <!-- 3. Matching Income -->
                            <tr class="hover:bg-amber-500/5 transition">
                                <td class="py-2.5 px-3 font-semibold text-white flex items-center gap-2">
                                    <span class="w-6 h-6 rounded-lg bg-purple-500/20 text-purple-400 border border-purple-500/40 flex items-center justify-center shrink-0">
                                        <i data-lucide="git-merge" class="w-3 h-3"></i>
                                    </span>
                                    <div>
                                        <a href="{{ route('user.reports.matching') }}" class="hover:text-amber-300 transition text-xs font-bold block">Matching Income</a>
                                        <span class="text-[9px] text-neutral-400 font-normal block">10% Binary Matching (2:1/1:2)</span>
                                    </div>
                                </td>
                                <td class="py-2.5 px-3 text-center font-mono font-bold text-emerald-400 text-xs">${{ number_format($todayMatchingEarned, 2) }}</td>
                                <td class="py-2.5 px-3 text-right font-mono font-bold text-emerald-400 text-xs sm:text-sm">${{ number_format($totalMatchingEarned, 2) }}</td>
                            </tr>

                            <!-- 4. Referral ROI Income -->
                            <tr class="hover:bg-amber-500/5 transition">
                                <td class="py-2.5 px-3 font-semibold text-white flex items-center gap-2">
                                    <span class="w-6 h-6 rounded-lg bg-teal-500/20 text-teal-400 border border-teal-500/40 flex items-center justify-center shrink-0">
                                        <i data-lucide="repeat" class="w-3 h-3"></i>
                                    </span>
                                    <div>
                                        <a href="{{ route('user.reports.referral-roi') }}" class="hover:text-amber-300 transition text-xs font-bold block">Referral ROI Income</a>
                                        <span class="text-[9px] text-neutral-400 font-normal block">0.5% Daily (150 Days)</span>
                                    </div>
                                </td>
                                <td class="py-2.5 px-3 text-center font-mono font-bold text-emerald-400 text-xs">${{ number_format($todayReferralRoiEarned, 2) }}</td>
                                <td class="py-2.5 px-3 text-right font-mono font-bold text-emerald-400 text-xs sm:text-sm">${{ number_format($totalReferralRoiEarned, 2) }}</td>
                            </tr>

                            <!-- 5. Matching ROI Income -->
                            <tr class="hover:bg-amber-500/5 transition">
                                <td class="py-2.5 px-3 font-semibold text-white flex items-center gap-2">
                                    <span class="w-6 h-6 rounded-lg bg-indigo-500/20 text-indigo-400 border border-indigo-500/40 flex items-center justify-center shrink-0">
                                        <i data-lucide="layers" class="w-3 h-3"></i>
                                    </span>
                                    <div>
                                        <a href="{{ route('user.reports.matching-roi') }}" class="hover:text-amber-300 transition text-xs font-bold block">Matching ROI Income</a>
                                        <span class="text-[9px] text-neutral-400 font-normal block">0.5% Daily (150 Days)</span>
                                    </div>
                                </td>
                                <td class="py-2.5 px-3 text-center font-mono font-bold text-emerald-400 text-xs">${{ number_format($todayMatchingRoiEarned, 2) }}</td>
                                <td class="py-2.5 px-3 text-right font-mono font-bold text-emerald-400 text-xs sm:text-sm">${{ number_format($totalMatchingRoiEarned, 2) }}</td>
                            </tr>

                            <!-- 6. Upline Matching Income -->
                            <tr class="hover:bg-amber-500/5 transition">
                                <td class="py-2.5 px-3 font-semibold text-white flex items-center gap-2">
                                    <span class="w-6 h-6 rounded-lg bg-rose-500/20 text-rose-400 border border-rose-500/40 flex items-center justify-center shrink-0">
                                        <i data-lucide="share-2" class="w-3 h-3"></i>
                                    </span>
                                    <div>
                                        <a href="{{ route('user.reports.upline-matching') }}" class="hover:text-amber-300 transition text-xs font-bold block">Upline Matching</a>
                                        <span class="text-[9px] text-neutral-400 font-normal block">10% Sponsor Pool</span>
                                    </div>
                                </td>
                                <td class="py-2.5 px-3 text-center font-mono font-bold text-emerald-400 text-xs">${{ number_format($todayUplineMatchingEarned, 2) }}</td>
                                <td class="py-2.5 px-3 text-right font-mono font-bold text-emerald-400 text-xs sm:text-sm">${{ number_format($totalUplineMatchingEarned, 2) }}</td>
                            </tr>

                            <!-- 7. Salary Income -->
                            <tr class="hover:bg-amber-500/5 transition">
                                <td class="py-2.5 px-3 font-semibold text-white flex items-center gap-2">
                                    <span class="w-6 h-6 rounded-lg bg-orange-500/20 text-orange-400 border border-orange-500/40 flex items-center justify-center shrink-0">
                                        <i data-lucide="award" class="w-3 h-3"></i>
                                    </span>
                                    <div>
                                        <a href="{{ route('user.reports.salary') }}" class="hover:text-amber-300 transition text-xs font-bold block">Salary Income</a>
                                        <span class="text-[9px] text-neutral-400 font-normal block">17 Milestone Ranks</span>
                                    </div>
                                </td>
                                <td class="py-2.5 px-3 text-center font-mono font-bold text-emerald-400 text-xs">${{ number_format($todaySalaryEarned, 2) }}</td>
                                <td class="py-2.5 px-3 text-right font-mono font-bold text-emerald-400 text-xs sm:text-sm">${{ number_format($totalSalaryEarned, 2) }}</td>
                            </tr>

                        </tbody>
                    </table>
                </div>
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
