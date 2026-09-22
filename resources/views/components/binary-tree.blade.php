@props(['treeData', 'directMembers' => collect(), 'routePrefix' => 'user'])

@php
    $root = $treeData['root'] ?? null;
    $leftChild = $treeData['left_child'] ?? null;
    $rightChild = $treeData['right_child'] ?? null;

    $leftBusiness = $treeData['left_business'] ?? 0.00;
    $rightBusiness = $treeData['right_business'] ?? 0.00;
    $leftCount = $treeData['left_count'] ?? 0;
    $rightCount = $treeData['right_count'] ?? 0;
    $leftActive = $treeData['left_active'] ?? 0;
    $leftInactive = $treeData['left_inactive'] ?? 0;
    $rightActive = $treeData['right_active'] ?? 0;
    $rightInactive = $treeData['right_inactive'] ?? 0;
    $totalTeam = $treeData['total_team'] ?? 0;
    $totalBusiness = $treeData['total_business'] ?? 0.00;
    $visibleTreeDepth = 3;
    $visibleMemberLimit = 15;

    // Helper closure to calculate user financial stats safely
    $getUserStats = function($u) {
        if (!$u) return [
            'sponsor_name' => 'N/A',
            'sponsor_code' => 'N/A',
            'active_invest' => '$0.00',
            'earning_wallet' => '$0.00',
            'daily_roi' => '$0.00',
            'direct_income' => '$0.00',
            'email' => 'N/A',
            'directs_count' => 0,
        ];

        $activeInvest = $u->userPackages ? $u->userPackages->where('status', 'active')->sum('invested_amount') : 0;
        $dailyRoi = $u->transactions ? $u->transactions->where('type', 'daily_roi')->sum('amount') : 0;
        $directInc = $u->transactions ? $u->transactions->whereIn('type', ['direct_commission', 'direct_income'])->sum('amount') : 0;
        
        return [
            'sponsor_name' => $u->sponsor ? $u->sponsor->name : ($u->sponsor_code ? $u->sponsor_code : 'No Sponsor'),
            'sponsor_code' => $u->sponsor_code ?? 'N/A',
            'active_invest' => '$' . number_format($activeInvest, 2),
            'earning_wallet' => '$' . number_format((float)($u->earning_wallet ?? 0), 2),
            'daily_roi' => '$' . number_format($dailyRoi, 2),
            'direct_income' => '$' . number_format($directInc, 2),
            'email' => $u->email ?? 'N/A',
            'directs_count' => \App\Models\User::where('sponsor_code', $u->referral_code)->count(),
        ];
    };

    $rootStats = $getUserStats($root);
@endphp

<style>
.genealogy-tree-wrapper {
    width: 100%;
    overflow-x: auto;
    padding: 1.5rem 0.5rem 2.5rem 0.5rem;
    -webkit-overflow-scrolling: touch;
}

@media (min-width: 768px) {
    .genealogy-tree-wrapper {
        padding: 3rem 1.5rem 3.5rem 1.5rem;
    }
}

.binary-tree-container {
    display: inline-block;
    min-width: 100%;
    text-align: center;
    padding: 0 1rem;
}

.binary-tree-container ul {
    padding-top: 14px;
    position: relative;
    transition: all 0.3s;
    display: flex;
    justify-content: center;
    margin: 0;
    padding-left: 0;
}

.binary-tree-container li {
    text-align: center;
    list-style-type: none;
    position: relative;
    padding: 14px 2px 0 2px;
    transition: all 0.3s;
    display: flex;
    flex-direction: column;
    align-items: center;
}

/* Perfect Binary Tree Connector Lines (Dashed Yellow Lines matching Reference) */
.binary-tree-container li::before, .binary-tree-container li::after {
    content: '';
    position: absolute;
    top: 0;
    right: 50%;
    border-top: 1.5px dashed #f3ca52;
    width: 50%;
    height: 14px;
}

.binary-tree-container li::after {
    right: auto;
    left: 50%;
    border-left: 1.5px dashed #f3ca52;
}

.binary-tree-container li:only-child::after, .binary-tree-container li:only-child::before {
    display: none;
}

.binary-tree-container li:only-child {
    padding-top: 0;
}

.binary-tree-container li:first-child::before, .binary-tree-container li:last-child::after {
    border: 0 none;
}

.binary-tree-container li:last-child::before {
    border-right: 1.5px dashed #f3ca52;
    border-radius: 0 8px 0 0;
}

.binary-tree-container li:first-child::after {
    border-radius: 8px 0 0 0;
}

.binary-tree-container ul ul::before {
    content: '';
    position: absolute;
    top: 0;
    left: 50%;
    border-left: 1.5px dashed #f3ca52;
    width: 0;
    height: 14px;
}

.binary-tree-layout {
    position: relative;
    margin: 0 auto;
}

.binary-tree-layout svg {
    position: absolute;
    inset: 0;
    pointer-events: none;
    overflow: visible;
}

.binary-tree-layout .node-card-wrapper {
    position: absolute;
    z-index: 1;
    transform: translateX(-50%);
}

/* A tooltip cannot escape its parent's stacking layer, so raise the hovered node itself. */
.binary-tree-layout .node-card-wrapper:hover {
    z-index: 50;
}

.tree-view-more {
    width: max-content;
    max-width: 88px;
    margin: 0.4rem auto 0;
}

/* Tooltip Hover Overlay */
.node-card-wrapper {
    position: relative;
}

.node-tooltip {
    position: absolute;
    background: linear-gradient(180deg, #051b11 0%, #010a06 100%);
    border: 2px solid #f3ca52;
    box-shadow: 0 20px 45px rgba(0, 0, 0, 0.95), 0 0 35px rgba(243, 202, 82, 0.45);
    border-radius: 1.25rem;
    padding: 1.35rem;
    width: 330px;
    opacity: 0;
    visibility: hidden;
    pointer-events: none;
    transition: opacity 0.2s ease-in-out, transform 0.2s ease-in-out, visibility 0.2s;
    z-index: 999999 !important;
    text-align: left;
}

/* Level 0 & Level 1 Tooltips open DOWNWARDS to prevent top container clipping */
.level-0-node-wrapper .node-tooltip,
.level-1-node-wrapper .node-tooltip {
    bottom: auto;
    top: 108%;
    left: 50%;
    transform: translateX(-50%) translateY(4px);
}

.level-0-node-wrapper:hover .node-tooltip,
.level-1-node-wrapper:hover .node-tooltip {
    opacity: 1;
    visibility: visible;
    pointer-events: auto;
    transform: translateX(-50%) translateY(12px);
}

/* Level 2 & Level 3 Tooltips open UPWARDS */
.level-2-node-wrapper .node-tooltip,
.level-3-node-wrapper .node-tooltip {
    top: auto;
    bottom: 108%;
    left: 50%;
    transform: translateX(-50%) translateY(-4px);
}

.level-2-node-wrapper:hover .node-tooltip,
.level-3-node-wrapper:hover .node-tooltip {
    opacity: 1;
    visibility: visible;
    pointer-events: auto;
    transform: translateX(-50%) translateY(-12px);
}

/* 3D Glossy Sphere Avatars matching reference image */
.avatar-3d-gold {
    background: radial-gradient(circle at 35% 30%, #fff7ed 0%, #fbbf24 35%, #d97706 70%, #78350f 100%) !important;
    border: 2px solid #fef08a !important;
    box-shadow: 0 0 15px rgba(251, 191, 36, 0.85), inset 0 2px 4px rgba(255, 255, 255, 0.8), inset 0 -3px 6px rgba(0, 0, 0, 0.4) !important;
    color: #1c1917 !important;
    font-weight: 900 !important;
    text-shadow: 0 1px 1px rgba(255, 255, 255, 0.6);
}

.avatar-3d-emerald {
    background: radial-gradient(circle at 35% 30%, #ecfdf5 0%, #34d399 35%, #059669 70%, #064e3b 100%) !important;
    border: 2px solid #a7f3d0 !important;
    box-shadow: 0 0 15px rgba(52, 211, 153, 0.85), inset 0 2px 4px rgba(255, 255, 255, 0.8), inset 0 -3px 6px rgba(0, 0, 0, 0.4) !important;
    color: #022c22 !important;
    font-weight: 900 !important;
    text-shadow: 0 1px 1px rgba(255, 255, 255, 0.6);
}

.avatar-3d-rose {
    background: radial-gradient(circle at 35% 30%, #fff1f2 0%, #fb7185 35%, #e11d48 70%, #881337 100%) !important;
    border: 2px solid #fecdd3 !important;
    box-shadow: 0 0 15px rgba(244, 63, 94, 0.85), inset 0 2px 4px rgba(255, 255, 255, 0.8), inset 0 -3px 6px rgba(0, 0, 0, 0.4) !important;
    color: #4c0519 !important;
    font-weight: 900 !important;
    text-shadow: 0 1px 1px rgba(255, 255, 255, 0.6);
}

.gold-glowing-avatar {
    background: radial-gradient(circle at 35% 35%, #fff3a3 0%, #f59e0b 55%, #b45309 100%) !important;
    border: 2.5px solid #fef08a !important;
    box-shadow: 0 0 20px rgba(245, 158, 11, 0.9), 0 0 35px rgba(254, 240, 138, 0.5) !important;
    color: #000000 !important;
}

/* UNIFORM ELEGANT CARD STYLING MATCHING REFERENCE IMAGE EXCLUSIVELY */
.tree-node-card-root,
.tree-node-card-l1,
.tree-node-card-l2,
.tree-node-card-l3 {
    width: 88px;
    min-width: 88px;
    max-width: 88px;
    height: 100px;
    min-height: 100px;
    max-height: 100px;
    box-sizing: border-box;
    overflow: hidden !important;
    border: 2px solid rgba(243, 202, 82, 0.85) !important;
    border-radius: 0.85rem !important;
    background-color: rgba(8, 21, 16, 0.95) !important;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.5) !important;
}

@media (max-width: 767px) {
    .genealogy-tree-wrapper {
        padding: 1rem 0.25rem 1.5rem;
    }

    .binary-tree-container li {
        padding: 10px 1px 0;
    }

    .tree-node-card-root,
    .tree-node-card-l1,
    .tree-node-card-l2,
    .tree-node-card-l3 {
        width: 68px;
        min-width: 68px;
        max-width: 68px;
        height: 82px;
        min-height: 82px;
        max-height: 82px;
    }

    .tree-view-more {
        max-width: 68px;
        margin-top: 0.3rem;
    }
}
</style>

@php
    $leftLink = url('/user/register?sponsor=' . ($root->referral_code ?? '') . '&position=left');
    $rightLink = url('/user/register?sponsor=' . ($root->referral_code ?? '') . '&position=right');
@endphp

<div class="w-full space-y-4 select-none font-sans">

    <!-- TOP 3 BINARY TEAM STATS CARDS (ALWAYS 1 ROW ON TABLET/DESKTOP, FULLY RESPONSIVE ON MOBILE) -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-2.5 sm:gap-3.5 items-stretch">
        
        <!-- CARD 1: COMBINED USER PROFILE & USER ID WITH COPY CODE -->
        <div class="p-3 sm:p-3.5 rounded-xl bg-gradient-to-b from-[#063824] to-[#021d12] border border-amber-500/50 hover:border-amber-400 hover:scale-[1.01] transition-all shadow-md flex flex-col justify-between h-full min-h-[80px]" title="Root User: {{ $root ? $root->name : 'N/A' }} ({{ $root ? $root->referral_code : 'N/A' }})">
            <div class="flex flex-wrap items-center justify-between gap-1.5 border-b border-amber-500/20 pb-2">
                <div class="flex items-center gap-2 min-w-0">
                    <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-full bg-gradient-to-br from-amber-400 via-yellow-400 to-amber-500 text-slate-950 font-black text-sm flex items-center justify-center shrink-0 shadow-[0_0_10px_rgba(245,158,11,0.6)] border-2 border-yellow-200">
                        {{ strtoupper(substr($root ? $root->name : 'U', 0, 1)) }}
                    </div>
                    <div class="min-w-0">
                        <h3 class="text-xs sm:text-sm font-black text-amber-300 font-heading truncate max-w-[120px] sm:max-w-[150px]">{{ $root ? $root->name : 'N/A' }}</h3>
                        <span class="text-[8.5px] sm:text-[9px] font-black uppercase text-neutral-400 flex items-center gap-1">
                            <span>👤</span> <span>USER PROFILE</span>
                        </span>
                    </div>
                </div>
                <div class="flex flex-col items-end shrink-0">
                    <div class="flex items-center gap-1">
                        <span class="text-xs sm:text-sm font-black text-amber-300 font-mono">{{ $root ? $root->referral_code : 'N/A' }}</span>
                        <button type="button" 
                                onclick="copyReferralCode(event, '{{ $root ? $root->referral_code : '' }}')" 
                                class="px-1.5 py-0.5 rounded bg-amber-500/20 hover:bg-amber-500/40 text-amber-300 text-[9px] font-bold uppercase transition flex items-center gap-0.5 cursor-pointer"
                                title="Copy User ID Code">
                            📋 Copy
                        </button>
                    </div>
                    <span class="text-[8.5px] sm:text-[9px] font-black uppercase text-neutral-400 block mt-0.5">USER ID</span>
                </div>
            </div>
            <div class="flex items-center justify-between pt-1.5 text-[9.5px] sm:text-[10px] font-mono font-bold text-neutral-300 flex-wrap gap-1">
                <span class="flex items-center gap-1"><span class="text-amber-400">⚡</span> Status: <strong class="uppercase {{ ($root && $root->status === 'active') ? 'text-emerald-400' : 'text-amber-400' }}">{{ $root ? $root->status : 'N/A' }}</strong></span>
                <span class="flex items-center gap-1"><span class="text-amber-400">📅</span> Root Tree View</span>
            </div>
        </div>

        <!-- CARD 2: LEFT BUSINESS & COPY LEFT REFERRAL LINK -->
        <div class="p-3 sm:p-3.5 rounded-xl bg-gradient-to-b from-[#063824] to-[#021d12] border border-amber-500/50 hover:border-amber-400 hover:scale-[1.01] transition-all shadow-md flex flex-col justify-between h-full min-h-[80px]" title="Left Leg Business: ${{ number_format($leftBusiness, 2) }} ({{ $leftActive }} Active, {{ $leftInactive }} Inactive, {{ $leftCount }} Total Members)">
            <div class="flex items-center justify-between gap-1.5 border-b border-amber-500/20 pb-2">
                <div class="min-w-0">
                    <h3 class="text-xs sm:text-sm font-black text-emerald-400 font-mono truncate">${{ number_format($leftBusiness, 2) }}</h3>
                    <span class="text-[8.5px] sm:text-[9px] font-black uppercase text-neutral-400 block">👈 LEFT BUSINESS</span>
                </div>
                <div class="flex items-center gap-1 shrink-0">
                    <span class="px-1.5 py-0.5 rounded-full bg-amber-500/20 text-amber-300 text-[8.5px] font-mono font-bold">{{ $leftCount }} Total</span>
                    <button type="button" 
                            onclick="copyReferralCode(event, '{{ $leftLink }}')" 
                            class="px-2 py-1 rounded-lg bg-gradient-to-r from-amber-400 to-yellow-500 hover:brightness-110 text-black font-black text-[9px] sm:text-[9.5px] uppercase tracking-wider transition flex items-center gap-1 shadow cursor-pointer whitespace-nowrap"
                            title="Copy Left Leg Referral Link">
                        <span>📋</span> <span class="hidden sm:inline xl:inline">Copy</span> Left Link
                    </button>
                </div>
            </div>
            <div class="flex items-center justify-between pt-1.5 text-[8.5px] sm:text-[9.5px] font-mono font-bold flex-wrap gap-1">
                <div class="flex items-center gap-1 flex-wrap">
                    <span class="px-1 py-0.2 rounded bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 flex items-center gap-0.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span> Active: {{ $leftActive }}
                    </span>
                    <span class="px-1 py-0.2 rounded bg-rose-500/20 text-rose-400 border border-rose-500/30 flex items-center gap-0.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-rose-400"></span> Inactive: {{ $leftInactive }}
                    </span>
                </div>
                <span class="text-[8.5px] sm:text-[9.5px] font-black uppercase text-amber-300 shrink-0">TEAM A</span>
            </div>
        </div>

        <!-- CARD 3: RIGHT BUSINESS & COPY RIGHT REFERRAL LINK -->
        <div class="p-3 sm:p-3.5 rounded-xl bg-gradient-to-b from-[#063824] to-[#021d12] border border-amber-500/50 hover:border-amber-400 hover:scale-[1.01] transition-all shadow-md flex flex-col justify-between h-full min-h-[80px]" title="Right Leg Business: ${{ number_format($rightBusiness, 2) }} ({{ $rightActive }} Active, {{ $rightInactive }} Inactive, {{ $rightCount }} Total Members)">
            <div class="flex items-center justify-between gap-1.5 border-b border-amber-500/20 pb-2">
                <div class="min-w-0">
                    <h3 class="text-xs sm:text-sm font-black text-emerald-400 font-mono truncate">${{ number_format($rightBusiness, 2) }}</h3>
                    <span class="text-[8.5px] sm:text-[9px] font-black uppercase text-neutral-400 block">RIGHT BUSINESS 👉</span>
                </div>
                <div class="flex items-center gap-1 shrink-0">
                    <span class="px-1.5 py-0.5 rounded-full bg-emerald-500/20 text-emerald-300 text-[8.5px] font-mono font-bold">{{ $rightCount }} Total</span>
                    <button type="button" 
                            onclick="copyReferralCode(event, '{{ $rightLink }}')" 
                            class="px-2 py-1 rounded-lg bg-gradient-to-r from-amber-400 to-yellow-500 hover:brightness-110 text-black font-black text-[9px] sm:text-[9.5px] uppercase tracking-wider transition flex items-center gap-1 shadow cursor-pointer whitespace-nowrap"
                            title="Copy Right Leg Referral Link">
                        <span>📋</span> <span class="hidden sm:inline xl:inline">Copy</span> Right Link
                    </button>
                </div>
            </div>
            <div class="flex items-center justify-between pt-1.5 text-[8.5px] sm:text-[9.5px] font-mono font-bold flex-wrap gap-1">
                <div class="flex items-center gap-1 flex-wrap">
                    <span class="px-1 py-0.2 rounded bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 flex items-center gap-0.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span> Active: {{ $rightActive }}
                    </span>
                    <span class="px-1 py-0.2 rounded bg-rose-500/20 text-rose-400 border border-rose-500/30 flex items-center gap-0.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-rose-400"></span> Inactive: {{ $rightInactive }}
                    </span>
                </div>
                <span class="text-[8.5px] sm:text-[9.5px] font-black uppercase text-emerald-300 shrink-0">TEAM B</span>
            </div>
        </div>

    </div>

    <!-- CANVAS HEADER TOOLBAR WITH DOWNLOAD IMAGE BUTTON & LEGEND -->
    <div class="w-full flex flex-col md:flex-row items-center justify-between gap-3 p-3 sm:p-3.5 rounded-xl bg-gradient-to-b from-[#042115] to-[#010c07] border border-amber-500/40 shadow-md">
        <!-- Left Group: Title Badge & Legend -->
        <div class="flex items-center gap-2.5 flex-wrap justify-center md:justify-start">
            <span class="px-3 py-1.5 rounded-xl bg-gradient-to-r from-amber-500/20 to-yellow-500/10 border border-amber-400/50 text-amber-300 font-black text-xs uppercase tracking-wider flex items-center gap-2 shadow-sm whitespace-nowrap">
                <span class="w-2 h-2 rounded-full bg-amber-400 animate-pulse"></span>
                <span>BINARY TREE</span>
            </span>

            <div class="flex items-center gap-2 px-3 py-1 rounded-xl bg-black/60 border border-amber-500/30 text-xs font-bold text-amber-300 whitespace-nowrap">
                <span class="text-amber-400 flex items-center gap-1">👈 Left Branch</span>
                <span class="text-amber-500/40">|</span>
                <span class="text-amber-400 flex items-center gap-1">Right Branch 👉</span>
            </div>
        </div>

        <!-- Right Group: Action Buttons & Zoom Controls -->
        <div class="flex items-center gap-2 shrink-0 flex-wrap justify-center w-full md:w-auto">
            <button type="button"
                    onclick="goToPreviousTree()"
                    class="px-3 py-1.5 sm:px-4 sm:py-2 rounded-xl bg-black/90 hover:bg-black border border-amber-500/50 text-amber-300 font-bold text-[11px] sm:text-xs transition shadow whitespace-nowrap active:scale-95">
                Back
            </button>

            <a href="{{ route($routePrefix . '.network.tree') }}"
               class="px-3 py-1.5 sm:px-4 sm:py-2 rounded-xl bg-black/90 hover:bg-black border border-amber-500/50 text-amber-300 font-bold text-[11px] sm:text-xs transition shadow whitespace-nowrap active:scale-95">
                Reset Tree
            </a>

            <!-- Mobile/Desktop Zoom Controls -->
            <div class="flex items-center gap-1 bg-black/80 p-1 rounded-xl border border-amber-500/40 shadow-sm">
                <button type="button" onclick="zoomTree(0.85)" class="w-7 h-7 rounded-lg bg-amber-500/20 text-amber-300 hover:bg-amber-500/40 flex items-center justify-center font-black font-mono text-xs sm:text-sm active:scale-95 transition" title="Zoom Out">-</button>
                <button type="button" onclick="zoomTree(1)" class="px-2 h-7 rounded-lg text-amber-300 font-mono font-bold text-[10px] sm:text-[11px] hover:bg-amber-500/20 active:scale-95 transition" title="Reset Zoom">100%</button>
                <button type="button" onclick="zoomTree(1.15)" class="w-7 h-7 rounded-lg bg-amber-500/20 text-amber-300 hover:bg-amber-500/40 flex items-center justify-center font-black font-mono text-xs sm:text-sm active:scale-95 transition" title="Zoom In">+</button>
            </div>

            <button type="button" 
                    onclick="downloadTreeImage()" 
                    id="downloadTreeBtn"
                    class="px-3 py-1.5 sm:px-4 sm:py-2 rounded-xl bg-gradient-to-r from-amber-400 to-yellow-500 hover:from-amber-300 hover:to-amber-400 text-black font-black text-[11px] sm:text-xs uppercase tracking-wider flex items-center justify-center gap-1 transition shadow cursor-pointer whitespace-nowrap active:scale-95">
                <span>📸 Save</span>
            </button>

            <button type="button" 
                    onclick="centerTreeCanvas()" 
                    class="px-3 py-1.5 sm:px-4 sm:py-2 rounded-xl bg-black/90 hover:bg-black border border-amber-500/50 text-amber-300 font-bold text-[11px] sm:text-xs flex items-center justify-center gap-1 transition shadow whitespace-nowrap active:scale-95"
                    title="Recenter Tree View">
                <span>🎯 Recenter</span>
            </button>
        </div>
    </div>

    <!-- MOBILE HORIZONTAL SWIPE & TAP HINT BAR -->
    <div class="md:hidden flex items-center justify-between px-3 py-2 rounded-xl bg-amber-500/10 border border-amber-500/30 text-[10.5px] text-amber-300 font-mono shadow-sm">
        <span class="flex items-center gap-1.5 font-bold">
            <span class="text-amber-400 animate-pulse">↔️</span> <span>Swipe horizontally to view binary tree</span>
        </span>
        <span class="px-2 py-0.5 rounded-md bg-amber-500/20 text-amber-300 font-black text-[9.5px] uppercase border border-amber-400/40">
            Tap node for info
        </span>
    </div>

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 px-3 py-2.5 rounded-xl bg-emerald-500/10 border border-emerald-400/25 text-xs">
        <span class="text-emerald-300 font-bold">Showing up to {{ $visibleMemberLimit }} members at once for a clear tree view.</span>
        <span class="text-amber-300 font-semibold">Select “View more” to open a deeper branch.</span>
    </div>

    <!-- MAIN LEFT / RIGHT BINARY TREE GRAPH CANVAS -->
    <div id="treeCanvasContainer" class="w-full rounded-2xl bg-black/80 border border-amber-500/40 p-2 sm:p-4 relative overflow-hidden">
        
        <div class="genealogy-tree-wrapper">
            <div class="binary-tree-container">
                <ul id="binaryTreeSource" class="binary-tree-container">
                    @include('components.binary-tree-node', ['node' => $root, 'level' => 0, 'maxLevel' => $visibleTreeDepth, 'path' => 'Root Node', 'routePrefix' => $routePrefix])
                </ul>
            </div>
        </div>

    </div>
</div>

<!-- DETAILED MOBILE MEMBER INFO MODAL OVERLAY -->
<div id="mobileMemberModal" style="display: none;" class="fixed inset-0 z-[99999] bg-black/85 backdrop-blur-md flex items-center justify-center p-3 sm:p-4 overflow-y-auto">
    <div class="relative w-full max-w-[340px] sm:max-w-md p-5 rounded-3xl border-2 border-amber-400 shadow-[0_0_50px_rgba(243,202,82,0.45)] text-left space-y-3 text-xs animate-modal-pop" style="background: linear-gradient(180deg, #051b11 0%, #010a06 100%) !important;">
        
        <!-- Header -->
        <div class="flex justify-between items-center pb-3 border-b border-amber-500/30 gap-2">
            <div class="flex items-center gap-2.5 min-w-0">
                <div id="mobileModalAvatar" class="w-10 h-10 rounded-full font-black text-sm flex items-center justify-center shrink-0 shadow-md avatar-3d-gold">
                    U
                </div>
                <div class="min-w-0">
                    <h3 id="mobileModalName" class="font-black text-white text-sm sm:text-base font-heading truncate">Member Name</h3>
                    <div class="flex items-center gap-1.5 mt-0.5">
                        <span id="mobileModalCode" class="text-[11px] sm:text-xs text-amber-400 font-mono font-bold">0000000</span>
                        <button type="button" 
                                onclick="copyReferralCode(event, document.getElementById('mobileModalCode').textContent)" 
                                class="px-1.5 py-0.5 rounded bg-amber-500/20 hover:bg-amber-500/40 text-amber-300 text-[9px] font-bold uppercase transition flex items-center gap-0.5 cursor-pointer">
                            📋 Copy
                        </button>
                    </div>
                </div>
            </div>
            <button type="button" onclick="closeMobileMemberModal()" class="w-8 h-8 rounded-xl bg-rose-500/20 border border-rose-500/40 text-rose-300 hover:bg-rose-500/40 flex items-center justify-center font-black text-sm transition shrink-0">
                ✕
            </button>
        </div>

        <!-- Details Rows with Icons & Subtle Dividers -->
        <div class="space-y-2 py-1">
            <div class="flex justify-between items-center py-1 border-b border-amber-500/10">
                <span class="text-slate-300 font-semibold flex items-center gap-1.5"><span class="text-amber-400">👤</span> Sponsor:</span>
                <span id="mobileModalSponsor" class="font-bold text-white font-mono truncate max-w-[160px]">ROOT</span>
            </div>
            <div class="flex justify-between items-center py-1 border-b border-amber-500/10">
                <span class="text-slate-300 font-semibold flex items-center gap-1.5"><span class="text-amber-400">⚡</span> Status:</span>
                <span id="mobileModalStatus" class="px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider bg-emerald-500/20 text-emerald-400 border border-emerald-400/40">ACTIVE</span>
            </div>
            <div class="flex justify-between items-center py-1 border-b border-amber-500/10">
                <span class="text-slate-300 font-semibold flex items-center gap-1.5"><span class="text-emerald-400">💰</span> Active Capital:</span>
                <span id="mobileModalActiveInvest" class="font-mono text-emerald-400 font-bold">$0.00</span>
            </div>
            <div class="flex justify-between items-center py-1 border-b border-amber-500/10">
                <span class="text-slate-300 font-semibold flex items-center gap-1.5"><span class="text-emerald-400">👛</span> Earning Wallet:</span>
                <span id="mobileModalEarningWallet" class="font-mono text-emerald-400 font-bold">$0.00</span>
            </div>
            <div class="flex justify-between items-center py-1 border-b border-amber-500/10">
                <span class="text-slate-300 font-semibold flex items-center gap-1.5"><span class="text-amber-400">📈</span> Daily ROI Income:</span>
                <span id="mobileModalDailyRoi" class="font-mono text-amber-400 font-bold">$0.00</span>
            </div>
            <div class="flex justify-between items-center py-1 border-b border-amber-500/10">
                <span class="text-slate-300 font-semibold flex items-center gap-1.5"><span class="text-amber-400">🎁</span> Direct Income:</span>
                <span id="mobileModalDirectIncome" class="font-mono text-amber-400 font-bold">$0.00</span>
            </div>
            <div class="flex justify-between items-center py-1 border-b border-amber-500/10">
                <span class="text-slate-300 font-semibold flex items-center gap-1.5"><span class="text-amber-300">👥</span> Downline Count:</span>
                <span id="mobileModalDirects" class="font-bold text-amber-300 font-mono">0 Members</span>
            </div>
            <div class="flex justify-between items-center py-1">
                <span class="text-slate-300 font-semibold flex items-center gap-1.5"><span class="text-neutral-400">📅</span> Joined Date:</span>
                <span id="mobileModalJoined" class="font-mono text-neutral-300 text-[11px]">2026-01-01</span>
            </div>
        </div>

        <div class="pt-2 flex flex-col gap-2">
            <a id="mobileModalNavBtn" href="#" class="w-full py-2.5 rounded-xl bg-gradient-to-r from-amber-400 to-yellow-500 hover:brightness-110 text-black font-black text-xs uppercase tracking-wider text-center block shadow transition">
                🔍 Inspect This Branch Subtree
            </a>
            <button type="button" onclick="closeMobileMemberModal()" class="w-full py-2 rounded-xl bg-black/80 hover:bg-black border border-white/30 text-neutral-300 font-bold text-xs uppercase tracking-wider transition">
                Close
            </button>
        </div>
    </div>
</div>

<style>
@keyframes modalPop {
    0% { transform: scale(0.92); opacity: 0; }
    100% { transform: scale(1); opacity: 1; }
}
.animate-modal-pop {
    animation: modalPop 0.22s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}
</style>

<!-- HTML2CANVAS SCRIPT FOR 1-CLICK TREE IMAGE EXPORT -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

<script>
    let currentTreeScale = 1;

    function copyReferralCode(event, code) {
        if (event) {
            event.preventDefault();
            event.stopPropagation();
        }
        if (!code) return;

        navigator.clipboard.writeText(code).then(() => {
            const target = event ? event.currentTarget : null;
            if (target) {
                const orig = target.innerHTML;
                target.innerHTML = '<span>✓ Copied!</span>';
                target.classList.add('bg-emerald-500/40', 'text-emerald-300');
                setTimeout(() => {
                    target.innerHTML = orig;
                    target.classList.remove('bg-emerald-500/40', 'text-emerald-300');
                }, 1800);
            }
        }).catch(err => {
            console.error('Copy failed: ', err);
        });
    }

    function zoomTree(scale) {
        const container = document.querySelector('.binary-tree-container');
        if (!container) return;

        if (scale === 1) {
            currentTreeScale = 1;
        } else if (scale === 0.85) {
            currentTreeScale = Math.max(0.55, Math.round((currentTreeScale - 0.15) * 100) / 100);
        } else if (scale === 1.15) {
            currentTreeScale = Math.min(1.4, Math.round((currentTreeScale + 0.15) * 100) / 100);
        }

        container.style.zoom = currentTreeScale;
        centerTreeCanvas();
    }

    function fitTreeCanvas() {
        const wrapper = document.querySelector('.genealogy-tree-wrapper');
        const container = document.querySelector('.binary-tree-container');

        if (!wrapper || !container) return;

        const availableWidth = wrapper.clientWidth - 24;
        const requiredWidth = wrapper.scrollWidth;
        const minimumScale = window.innerWidth < 768 ? 0.75 : 1;
        const fittedScale = Math.max(minimumScale, Math.min(1, availableWidth / requiredWidth));

        currentTreeScale = Math.round(fittedScale * 100) / 100;
        container.style.zoom = currentTreeScale;
        centerTreeCanvas();
    }

    function layoutBinaryTree() {
        const sourceTree = document.getElementById('binaryTreeSource');
        const wrapper = document.querySelector('.genealogy-tree-wrapper');

        if (!sourceTree || !wrapper || document.getElementById('binaryTreeLayout')) return;

        const buildNode = (listItem, level = 0) => {
            const card = listItem.querySelector(':scope > .node-card-wrapper');
            const childList = listItem.querySelector(':scope > ul');
            const children = childList
                ? [...childList.children].map((child) => buildNode(child, level + 1))
                : [];

            return { card, children, level, x: 0 };
        };

        const rootItem = sourceTree.querySelector(':scope > li');
        if (!rootItem) return;

        const rootNode = buildNode(rootItem);
        const layout = document.createElement('div');
        const svgNamespace = 'http://www.w3.org/2000/svg';
        const connectors = document.createElementNS(svgNamespace, 'svg');
        const nodes = [];
        let leafIndex = 0;
        let deepestLevel = 0;
        const isMobile = window.innerWidth < 768;
        const horizontalGap = isMobile ? 78 : 106;
        const verticalGap = isMobile ? 122 : 152;
        const cardHeight = isMobile ? 82 : 100;

        const assignCoordinates = (node) => {
            deepestLevel = Math.max(deepestLevel, node.level);

            if (node.children.length === 0) {
                node.x = leafIndex * horizontalGap;
                leafIndex += 1;
            } else {
                node.children.forEach(assignCoordinates);
                node.x = (node.children[0].x + node.children[node.children.length - 1].x) / 2;
            }

            nodes.push(node);
        };

        assignCoordinates(rootNode);

        const treeWidth = (Math.max(leafIndex - 1, 0) * horizontalGap) + horizontalGap;
        const width = Math.max(wrapper.clientWidth - 32, treeWidth);
        const height = ((deepestLevel + 1) * verticalGap) + 24;
        const offset = (width - treeWidth) / 2 + (horizontalGap / 2);

        layout.id = 'binaryTreeLayout';
        layout.className = 'binary-tree-layout';
        layout.style.width = `${width}px`;
        layout.style.height = `${height}px`;
        connectors.setAttribute('width', width);
        connectors.setAttribute('height', height);
        connectors.setAttribute('viewBox', `0 0 ${width} ${height}`);

        nodes.forEach((node) => {
            const nodeX = node.x + offset;
            const nodeY = (node.level * verticalGap) + 8;

            if (node.card) {
                node.card.style.left = `${nodeX}px`;
                node.card.style.top = `${nodeY}px`;
                layout.appendChild(node.card);
            }

            node.children.forEach((child) => {
                const childX = child.x + offset;
                const childY = (child.level * verticalGap) + 8;
                const middleY = nodeY + (verticalGap / 2);
                const path = document.createElementNS(svgNamespace, 'path');
                path.setAttribute('d', `M ${nodeX} ${nodeY + cardHeight} V ${middleY} H ${childX} V ${childY}`);
                path.setAttribute('fill', 'none');
                path.setAttribute('stroke', '#f3ca52');
                path.setAttribute('stroke-width', '1.5');
                path.setAttribute('stroke-dasharray', '4 3');
                connectors.appendChild(path);
            });
        });

        layout.prepend(connectors);
        sourceTree.style.display = 'none';
        wrapper.appendChild(layout);
    }

    function centerTreeCanvas() {
        const wrapper = document.querySelector('.genealogy-tree-wrapper');
        if (wrapper) {
            const scrollLeft = (wrapper.scrollWidth - wrapper.clientWidth) / 2;
            if (scrollLeft > 0) {
                wrapper.scrollLeft = scrollLeft;
            }
        }
    }

    function goToPreviousTree() {
        if (window.history.length > 1) {
            window.history.back();
            return;
        }

        window.location.href = "{{ route($routePrefix . '.network.tree') }}";
    }

    function downloadTreeImage() {
        const btn = document.getElementById('downloadTreeBtn');
        const container = document.getElementById('treeCanvasContainer');
        if (!container) return;

        const originalText = btn.innerHTML;
        btn.innerHTML = '<span>⏳ Saving PNG...</span>';
        btn.disabled = true;

        html2canvas(container, {
            backgroundColor: '#010905',
            scale: 2,
            useCORS: true,
            logging: false
        }).then(canvas => {
            const image = canvas.toDataURL('image/png');
            const link = document.createElement('a');
            const userCode = "{{ $root->referral_code ?? 'TREE' }}";
            link.download = `Dex_Trade_Binary_Tree_${userCode}.png`;
            link.href = image;
            link.click();

            btn.innerHTML = originalText;
            btn.disabled = false;
        }).catch(err => {
            console.error(err);
            alert('Could not capture tree image. Please try again.');
            btn.innerHTML = originalText;
            btn.disabled = false;
        });
    }

    function handleCardClick(event, navUrl, name, code, sponsor, status, activeInvest, earningWallet, dailyRoi, directIncome, directs, joined) {
        if (window.innerWidth < 768) {
            event.preventDefault();
            event.stopPropagation();
            openMobileModal(name, code, sponsor, status, activeInvest, earningWallet, dailyRoi, directIncome, directs, joined, navUrl);
        } else {
            window.location.href = navUrl;
        }
    }

    function openMobileModal(name, code, sponsor, status, activeInvest, earningWallet, dailyRoi, directIncome, directs, joined, navUrl = '#') {
        document.getElementById('mobileModalName').textContent = name;
        document.getElementById('mobileModalCode').textContent = code;
        document.getElementById('mobileModalSponsor').textContent = sponsor;
        
        const statusEl = document.getElementById('mobileModalStatus');
        if (statusEl) {
            statusEl.textContent = status.toUpperCase();
            if (status.toUpperCase() === 'ACTIVE') {
                statusEl.className = 'px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider bg-emerald-500/20 text-emerald-400 border border-emerald-400/40';
            } else {
                statusEl.className = 'px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider bg-rose-500/20 text-rose-400 border border-rose-400/40';
            }
        }

        document.getElementById('mobileModalActiveInvest').textContent = activeInvest;
        document.getElementById('mobileModalEarningWallet').textContent = earningWallet;
        document.getElementById('mobileModalDailyRoi').textContent = dailyRoi;
        document.getElementById('mobileModalDirectIncome').textContent = directIncome;
        document.getElementById('mobileModalDirects').textContent = directs;
        document.getElementById('mobileModalJoined').textContent = joined;

        const avatar = document.getElementById('mobileModalAvatar');
        if (avatar) {
            avatar.textContent = name.charAt(0).toUpperCase();
            const rootCode = "{{ $root->referral_code ?? '' }}";
            if (code === rootCode) {
                avatar.className = 'w-10 h-10 rounded-full avatar-3d-gold flex items-center justify-center text-sm shadow-md shrink-0';
            } else if (status.toUpperCase() === 'ACTIVE') {
                avatar.className = 'w-10 h-10 rounded-full avatar-3d-emerald flex items-center justify-center text-sm shadow-md shrink-0';
            } else {
                avatar.className = 'w-10 h-10 rounded-full avatar-3d-rose flex items-center justify-center text-sm shadow-md shrink-0';
            }
        }
        
        const navBtn = document.getElementById('mobileModalNavBtn');
        if (navBtn) {
            navBtn.href = navUrl !== '#' ? navUrl : "{{ route($routePrefix . '.network.tree') }}?code=" + code;
        }

        const modal = document.getElementById('mobileMemberModal');
        if (modal) modal.style.display = 'flex';
    }

    function closeMobileMemberModal() {
        const modal = document.getElementById('mobileMemberModal');
        if (modal) modal.style.display = 'none';
    }

    document.addEventListener('click', function(e) {
        const modal = document.getElementById('mobileMemberModal');
        if (modal && e.target === modal) {
            closeMobileMemberModal();
        }
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeMobileMemberModal();
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        layoutBinaryTree();
        fitTreeCanvas();
    });

    window.addEventListener('resize', function() {
        fitTreeCanvas();
    });
</script>
