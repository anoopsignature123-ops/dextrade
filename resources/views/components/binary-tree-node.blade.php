@props(['node' => null, 'level' => 0, 'maxLevel' => 15, 'path' => 'Root Node', 'routePrefix' => 'user'])

@php
    $isRoot = $level === 0;
    $isActive = $node && $node->status === 'active';
    
    $sponsorName = $node?->sponsor?->name ?? 'N/A';
    $sponsorCode = $node?->sponsor_code ?? 'N/A';
    $activeInvest = '$' . number_format($node ? (float)$node->userPackages()->where('status', 'active')->sum('invested_amount') : 0, 2);
    $earningWallet = '$' . number_format($node ? (float)$node->earning_wallet : 0, 2);
    $dailyRoi = '$' . number_format($node ? (float)$node->transactions()->where('type', 'daily_roi')->sum('amount') : 0, 2);
    $directIncome = '$' . number_format($node ? (float)$node->transactions()->whereIn('type', ['direct_commission', 'direct_income'])->sum('amount') : 0, 2);
    $directsCount = $node ? \App\Models\User::where('sponsor_code', $node->referral_code)->count() . ' Members' : '0 Members';
    $joinedDate = $node?->created_at ? $node->created_at->format('Y-m-d') : 'N/A';
@endphp

<li>
    @if ($node)
        <div class="node-card-wrapper inline-block level-{{ $level }}-node-wrapper {{ $isRoot ? 'root-node-wrapper' : '' }}">
            <!-- HOVER TOOLTIP (DESKTOP) -->
            <div class="node-tooltip space-y-2.5 hidden md:block">
                <!-- TOOLTIP COLORFUL HEADER WITH PROFILE AVATAR & COPY CODE -->
                <div class="border-b border-amber-500/30 pb-2.5 flex items-center justify-between gap-2">
                    <div class="flex items-center gap-2.5 min-w-0">
                        <div class="w-10 h-10 rounded-full font-black text-sm flex items-center justify-center shrink-0 shadow-md {{ $isRoot ? 'avatar-3d-gold' : ($isActive ? 'avatar-3d-emerald' : 'avatar-3d-rose') }}">
                            {{ strtoupper(substr($node->name, 0, 1)) }}
                        </div>
                        <div class="min-w-0">
                            <h4 class="text-white font-black font-heading text-sm truncate leading-tight">{{ $node->name }}</h4>
                            <div class="flex items-center gap-1.5 mt-0.5">
                                <span class="text-[11px] text-amber-300 font-mono font-bold">{{ $node->referral_code }}</span>
                                <button type="button" 
                                        onclick="copyReferralCode(event, '{{ $node->referral_code }}')" 
                                        class="px-1.5 py-0.5 rounded bg-amber-500/20 hover:bg-amber-500/40 text-amber-300 text-[9px] font-bold uppercase transition flex items-center gap-1 cursor-pointer"
                                        title="Copy Referral Code">
                                    <span>📋</span> <span>Copy</span>
                                </button>
                            </div>
                        </div>
                    </div>
                    <!-- STATUS BADGE -->
                    <span class="px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider shrink-0 {{ $isActive ? 'bg-emerald-500/20 text-emerald-400 border border-emerald-400/40' : 'bg-rose-500/20 text-rose-400 border border-rose-400/40' }}">
                        {{ $node->status }}
                    </span>
                </div>

                <!-- FIELD ROWS WITH ICONS & COMPLETE DETAILS -->
                <div class="space-y-2 text-xs">
                    <div class="flex justify-between items-center py-1 border-b border-amber-500/10">
                        <span class="text-slate-300 font-semibold flex items-center gap-1.5">
                            <span class="text-amber-400">👤</span> Sponsor:
                        </span>
                        <span class="text-white font-bold truncate max-w-[140px] text-right">{{ $sponsorName }} ({{ $sponsorCode }})</span>
                    </div>

                    <div class="flex justify-between items-center py-1 border-b border-amber-500/10">
                        <span class="text-slate-300 font-semibold flex items-center gap-1.5">
                            <span class="text-amber-400">🧭</span> Position:
                        </span>
                        <span class="text-amber-300 font-black uppercase">{{ $path }}</span>
                    </div>

                    <div class="flex justify-between items-center py-1 border-b border-amber-500/10">
                        <span class="text-slate-300 font-semibold flex items-center gap-1.5">
                            <span class="text-emerald-400">💰</span> Active Capital:
                        </span>
                        <span class="text-emerald-400 font-mono font-bold">{{ $activeInvest }}</span>
                    </div>

                    <div class="flex justify-between items-center py-1 border-b border-amber-500/10">
                        <span class="text-slate-300 font-semibold flex items-center gap-1.5">
                            <span class="text-emerald-400">👛</span> Earning Wallet:
                        </span>
                        <span class="text-emerald-400 font-mono font-bold">{{ $earningWallet }}</span>
                    </div>

                    <div class="flex justify-between items-center py-1 border-b border-amber-500/10">
                        <span class="text-slate-300 font-semibold flex items-center gap-1.5">
                            <span class="text-amber-400">📈</span> Daily ROI Income:
                        </span>
                        <span class="text-amber-400 font-mono font-bold">{{ $dailyRoi }}</span>
                    </div>

                    <div class="flex justify-between items-center py-1 border-b border-amber-500/10">
                        <span class="text-slate-300 font-semibold flex items-center gap-1.5">
                            <span class="text-amber-400">🎁</span> Direct Income:
                        </span>
                        <span class="text-amber-400 font-mono font-bold">{{ $directIncome }}</span>
                    </div>

                    <div class="flex justify-between items-center py-1 border-b border-amber-500/10">
                        <span class="text-slate-300 font-semibold flex items-center gap-1.5">
                            <span class="text-amber-300">👥</span> Direct Members:
                        </span>
                        <span class="text-amber-300 font-mono font-bold">{{ $directsCount }}</span>
                    </div>

                    <div class="flex justify-between items-center py-1">
                        <span class="text-slate-300 font-semibold flex items-center gap-1.5">
                            <span class="text-neutral-400">📅</span> Joined Date:
                        </span>
                        <span class="text-neutral-300 font-mono text-[11px]">{{ $joinedDate }}</span>
                    </div>
                </div>

                <div class="flex justify-center items-center text-[10.5px] pt-2 border-t border-amber-500/20 text-amber-400 font-bold text-center">
                    <span>🔍 Click card to inspect Subtree</span>
                </div>
            </div>

            <!-- NODE CARD FRAME -->
            <div class="tree-node-card-l1 p-2 rounded-xl bg-[#081510]/95 border-2 {{ $isActive ? 'border-emerald-400 shadow-[0_0_12px_rgba(52,211,153,0.3)]' : 'border-[#f3ca52]' }} hover:scale-[1.04] transition-all text-center flex flex-col items-center justify-between shadow-lg cursor-pointer relative group overflow-hidden"
                 onclick="handleCardClick(event, '{{ route($routePrefix . '.network.tree', ['code' => $node->referral_code]) }}', '{{ addslashes($node->name) }}', '{{ $node->referral_code }}', '{{ addslashes($sponsorName) }}', '{{ strtoupper($node->status) }}', '{{ $activeInvest }}', '{{ $earningWallet }}', '{{ $dailyRoi }}', '{{ $directIncome }}', '{{ $directsCount }}', '{{ $joinedDate }}')">
                
                <!-- STATUS DOT AT TOP RIGHT (GREEN FOR ACTIVE, RED FOR INACTIVE) -->
                @if($isActive)
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 border border-slate-950 absolute top-2 right-2 shadow-[0_0_8px_#34d399] z-10 animate-pulse" title="Active User"></span>
                @else
                    <span class="w-2.5 h-2.5 rounded-full bg-rose-500 border border-slate-950 absolute top-2 right-2 shadow-[0_0_8px_#f43f5e] z-10" title="Inactive User"></span>
                @endif

                <!-- CIRCULAR 3D GLOSSY AVATAR -->
                <div class="w-9 h-9 rounded-full {{ $isRoot ? 'avatar-3d-gold' : ($isActive ? 'avatar-3d-emerald' : 'avatar-3d-rose') }} flex items-center justify-center mx-auto shrink-0 mt-0.5 text-xs shadow-md">
                    {{ strtoupper(substr($node->name, 0, 1)) }}
                </div>

                <!-- NAME PILL -->
                <div class="w-full px-2 py-0.5 rounded-full bg-slate-900/90 border border-slate-700/80 text-[11px] font-bold text-white font-heading truncate mx-auto text-center leading-tight">
                    {{ $node->name }}
                </div>

                <!-- SELF ID LINE -->
                <div class="w-full text-center px-1">
                    <span class="text-[10px] font-mono font-bold text-amber-300 tracking-tight block truncate">
                        SelfID: {{ $node->referral_code }}
                    </span>
                </div>

                <!-- SPONSOR ID SECTION -->
                <div class="w-full text-center leading-tight pb-0.5">
                    <div class="text-[9px] text-slate-400 font-bold uppercase tracking-wider">SponsorID:</div>
                    <div class="text-[10px] text-amber-400 font-mono font-bold tracking-tight truncate">{{ $sponsorCode }}</div>
                </div>
            </div>
        </div>
    @else
        <!-- VACANT NODE CARD -->
        <div class="node-card-wrapper inline-block">
            <div class="tree-node-card-l1 p-2 rounded-xl bg-black/70 border-2 border-dashed border-amber-500/50 text-center flex flex-col items-center justify-between relative overflow-hidden">
                <span class="text-[9px] text-amber-300 font-bold block">{{ strtoupper($path) }}</span>
                <div class="w-6 h-6 mx-auto rounded-full border-2 border-dashed border-amber-400 text-amber-400 flex items-center justify-center font-bold text-xs">+</div>
                <div class="w-full px-1 py-0.5 rounded-full bg-black/90 border border-dashed border-amber-500/40 text-[10px] font-bold text-amber-300 truncate">VACANT</div>
                <div class="text-[9px] text-amber-400/70 font-mono font-bold">[ AVAILABLE ]</div>
            </div>
        </div>
    @endif

    @if ($level < 3 || ($node && ($node->left_child || $node->right_child)))
        <ul>
            @include('components.binary-tree-node', ['node' => $node?->left_child, 'level' => $level + 1, 'maxLevel' => $maxLevel, 'path' => '👈 Left', 'routePrefix' => $routePrefix])
            @include('components.binary-tree-node', ['node' => $node?->right_child, 'level' => $level + 1, 'maxLevel' => $maxLevel, 'path' => 'Right 👉', 'routePrefix' => $routePrefix])
        </ul>
    @endif
</li>
