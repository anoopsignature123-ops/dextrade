@props(['node' => null, 'level' => 0, 'maxLevel' => PHP_INT_MAX, 'path' => 'Root Node', 'routePrefix' => 'user'])

@php
    $isRoot = $level === 0;
    $isActive = $node && $node->status === 'active';
    $memberName = trim((string) ($node?->name ?? '')) ?: 'Member';
    
    $sponsorName = $node?->sponsor?->name ?? 'N/A';
    $sponsorCode = $node?->sponsor_code ?? 'N/A';
    $sponsorEmail = $node?->sponsor?->email ?? 'N/A';
    $activeInvest = '$' . number_format($node ? (float) $node->userPackages->where('status', 'active')->sum('invested_amount') : 0, 2);
    $earningWallet = '$' . number_format($node ? (float)$node->earning_wallet : 0, 2);
    $dailyRoi = '$' . number_format($node ? (float) $node->transactions->where('type', 'daily_roi')->sum('amount') : 0, 2);
    $directIncome = '$' . number_format($node ? (float) $node->transactions->whereIn('type', ['direct_commission', 'direct_income'])->sum('amount') : 0, 2);
    $directsCount = $node ? ($node->direct_members_count ?? 0) . ' Members' : '0 Members';
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
                            {{ strtoupper(substr($memberName, 0, 1)) }}
                        </div>
                        <div class="min-w-0">
                            <h4 class="text-white font-black font-heading text-sm truncate leading-tight">{{ $memberName }}</h4>
                            <div class="flex items-center gap-1.5 mt-0.5">
                                <span class="text-[11px] text-amber-300 font-mono font-bold">{{ $node->referral_code }}</span>
                                <button type="button" 
                                        onclick="copyReferralCode(event, '{{ $node->referral_code }}')" 
                                        class="w-5 h-5 rounded bg-amber-500/20 hover:bg-amber-500/40 text-amber-300 transition flex items-center justify-center cursor-pointer"
                                        title="Copy member ID"
                                        aria-label="Copy member ID">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-3 h-3" aria-hidden="true"><rect x="9" y="9" width="11" height="11" rx="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg>
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
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-3.5 h-3.5 text-amber-400 shrink-0"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg> Sponsor:
                        </span>
                        <span class="text-white font-bold truncate max-w-[140px] text-right">{{ $sponsorName }} ({{ $sponsorCode }})</span>
                    </div>

                    <div class="flex justify-between items-center py-1 border-b border-amber-500/10">
                        <span class="text-slate-300 font-semibold flex items-center gap-1.5">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-3.5 h-3.5 text-amber-400 shrink-0"><circle cx="12" cy="12" r="10"></circle><polygon points="16.24 7.76 14.12 14.12 7.76 16.24 9.88 9.88 16.24 7.76"></polygon></svg> Position:
                        </span>
                        <span class="text-amber-300 font-black uppercase">{{ $path }}</span>
                    </div>

                    <div class="flex justify-between items-center py-1 border-b border-amber-500/10">
                        <span class="text-slate-300 font-semibold flex items-center gap-1.5">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-3.5 h-3.5 text-emerald-400 shrink-0"><path d="M21.21 15.89A10 10 0 1 1 8 2.83"></path><path d="M22 12A10 10 0 0 0 12 2v10z"></path></svg> Active Capital:
                        </span>
                        <span class="text-emerald-400 font-mono font-bold">{{ $activeInvest }}</span>
                    </div>

                    <div class="flex justify-between items-center py-1 border-b border-amber-500/10">
                        <span class="text-slate-300 font-semibold flex items-center gap-1.5">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-3.5 h-3.5 text-emerald-400 shrink-0"><path d="M21 12V7H5a2 2 0 0 1 0-4h14v4"></path><path d="M3 5v14a2 2 0 0 0 2 2h16v-5"></path><path d="M18 12a2 2 0 0 0 0 4h4v-4Z"></path></svg> Earning Wallet:
                        </span>
                        <span class="text-emerald-400 font-mono font-bold">{{ $earningWallet }}</span>
                    </div>

                    <div class="flex justify-between items-center py-1 border-b border-amber-500/10">
                        <span class="text-slate-300 font-semibold flex items-center gap-1.5">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-3.5 h-3.5 text-amber-400 shrink-0"><polyline points="22 7 13.5 15.5 8.5 10.5 2 17"></polyline><polyline points="16 7 22 7 22 13"></polyline></svg> Daily ROI Income:
                        </span>
                        <span class="text-amber-400 font-mono font-bold">{{ $dailyRoi }}</span>
                    </div>

                    <div class="flex justify-between items-center py-1 border-b border-amber-500/10">
                        <span class="text-slate-300 font-semibold flex items-center gap-1.5">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-3.5 h-3.5 text-yellow-400 shrink-0"><rect x="3" y="8" width="18" height="13" rx="2"></rect><path d="M12 8v13"></path><path d="M19 12v7a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2v-7"></path><path d="M7.5 8a2.5 2.5 0 0 1 0-5A4.8 4.8 0 0 1 12 8a4.8 4.8 0 0 1 4.5-5 2.5 2.5 0 0 1 0 5"></path></svg> Direct Income:
                        </span>
                        <span class="text-amber-400 font-mono font-bold">{{ $directIncome }}</span>
                    </div>

                    <div class="flex justify-between items-center py-1 border-b border-amber-500/10">
                        <span class="text-slate-300 font-semibold flex items-center gap-1.5">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-3.5 h-3.5 text-sky-400 shrink-0"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M22 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg> Direct Members:
                        </span>
                        <span class="text-amber-300 font-mono font-bold">{{ $directsCount }}</span>
                    </div>

                    <div class="flex justify-between items-center py-1">
                        <span class="text-slate-300 font-semibold flex items-center gap-1.5">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-3.5 h-3.5 text-neutral-400 shrink-0"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"></rect><line x1="16" x2="16" y1="2" y2="6"></line><line x1="8" x2="8" y1="2" y2="6"></line><line x1="3" x2="21" y1="10" y2="10"></line></svg> Joined Date:
                        </span>
                        <span class="text-neutral-300 font-mono text-[11px]">{{ $joinedDate }}</span>
                    </div>
                </div>

                <div class="flex justify-center items-center gap-1 text-[10.5px] pt-2 border-t border-amber-500/20 text-amber-400 font-bold text-center">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-3.5 h-3.5"><circle cx="11" cy="11" r="8"></circle><path d="m21 21-4.3-4.3"></path></svg>
                    <span>Click card to inspect Subtree</span>
                </div>
            </div>

            <!-- NODE CARD FRAME -->
            <div class="tree-node-card-l1 p-2 rounded-xl bg-[#081510]/95 border-2 {{ $isActive ? 'border-emerald-400 shadow-[0_0_12px_rgba(52,211,153,0.3)]' : 'border-[#f3ca52]' }} hover:scale-[1.04] transition-all text-center flex flex-col items-center justify-between shadow-lg cursor-pointer relative group overflow-hidden"
                 onclick="handleCardClick(event, '{{ route($routePrefix . '.network.tree', ['code' => $node->referral_code]) }}', '{{ addslashes($memberName) }}', '{{ $node->referral_code }}', '{{ addslashes($sponsorName) }}', '{{ $sponsorCode }}', '{{ addslashes($sponsorEmail) }}', '{{ strtoupper($node->status) }}', '{{ $activeInvest }}', '{{ $earningWallet }}', '{{ $dailyRoi }}', '{{ $directIncome }}', '{{ $directsCount }}', '{{ $joinedDate }}')">
                
                <!-- STATUS DOT AT TOP RIGHT (GREEN FOR ACTIVE, RED FOR INACTIVE) -->
                @if($isActive)
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 border border-slate-950 absolute top-2 right-2 shadow-[0_0_8px_#34d399] z-10 animate-pulse" title="Active User"></span>
                @else
                    <span class="w-2.5 h-2.5 rounded-full bg-rose-500 border border-slate-950 absolute top-2 right-2 shadow-[0_0_8px_#f43f5e] z-10" title="Inactive User"></span>
                @endif

                <!-- CIRCULAR 3D GLOSSY AVATAR -->
                <div class="w-9 h-9 rounded-full {{ $isRoot ? 'avatar-3d-gold' : ($isActive ? 'avatar-3d-emerald' : 'avatar-3d-rose') }} flex items-center justify-center mx-auto shrink-0 mt-0.5 text-xs shadow-md">
                    {{ strtoupper(substr($memberName, 0, 1)) }}
                </div>

                <div class="md:hidden w-full text-center text-[10px] font-black text-white truncate leading-tight">
                    {{ $memberName }}
                </div>

                <!-- MEMBER ID WITH COPY ACTION -->
                <div class="w-full flex items-center justify-center gap-1 px-0.5">
                    <span class="min-w-0 text-[10px] font-mono font-black text-amber-300 tracking-tight block truncate">
                        {{ $node->referral_code }}
                    </span>
                    <button type="button"
                            onclick="copyReferralCode(event, '{{ $node->referral_code }}')"
                            class="shrink-0 w-5 h-5 rounded border border-amber-300/60 bg-amber-400/15 text-amber-200 transition hover:bg-amber-400 hover:text-slate-950 flex items-center justify-center"
                            title="Copy member ID"
                            aria-label="Copy member ID">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-3 h-3" aria-hidden="true"><rect x="9" y="9" width="11" height="11" rx="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg>
                    </button>
                </div>

                <!-- SPONSOR ID SECTION -->
                <div class="hidden md:block w-full text-center leading-tight pb-0.5">
                    <div class="text-[9px] text-slate-400 font-bold uppercase tracking-wider">SponsorID:</div>
                    <div class="text-[10px] text-amber-400 font-mono font-bold tracking-tight truncate">{{ $sponsorCode }}</div>
                </div>
            </div>
            @if ($level === $maxLevel && ($node->left_child || $node->right_child))
                <a href="{{ route($routePrefix . '.network.tree', ['code' => $node->referral_code]) }}"
                   class="tree-view-more inline-flex items-center justify-center gap-1 rounded-lg border border-amber-400/60 bg-amber-500/15 px-2 py-1 text-[9px] font-black uppercase tracking-wide text-amber-200 transition hover:bg-amber-400 hover:text-slate-950 focus:outline-none focus:ring-2 focus:ring-amber-300"
                   title="Open this member's next tree level">
                    View more <span aria-hidden="true">→</span>
                </a>
            @endif
        </div>
    @else
        <!-- VACANT NODE CARD -->
        <div class="node-card-wrapper inline-block" data-tree-vacant="true">
            <div class="tree-node-card-l1 p-2 rounded-xl bg-black/70 border-2 border-dashed border-amber-500/50 text-center flex flex-col items-center justify-between relative overflow-hidden">
                <span class="text-[9px] text-amber-300 font-bold block">{{ strtoupper($path) }}</span>
                <div class="w-6 h-6 mx-auto rounded-full border-2 border-dashed border-amber-400 text-amber-400 flex items-center justify-center font-bold text-xs">+</div>
                <div class="w-full px-1 py-0.5 rounded-full bg-black/90 border border-dashed border-amber-500/40 text-[10px] font-bold text-amber-300 truncate">VACANT</div>
                <div class="text-[9px] text-amber-400/70 font-mono font-bold">[ AVAILABLE ]</div>
            </div>
        </div>
    @endif

    @if ($node && $level < $maxLevel)
        <ul>
            @include('components.binary-tree-node', ['node' => $node?->left_child, 'level' => $level + 1, 'maxLevel' => $maxLevel, 'path' => '👈 Left', 'routePrefix' => $routePrefix])
            @include('components.binary-tree-node', ['node' => $node?->right_child, 'level' => $level + 1, 'maxLevel' => $maxLevel, 'path' => 'Right 👉', 'routePrefix' => $routePrefix])
        </ul>
    @endif
</li>
