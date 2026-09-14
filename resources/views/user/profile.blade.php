@extends('user.layouts.app')

@section('title', 'My Profile & Account Settings')

@section('content')
<div class="w-full space-y-6 font-sans">
    
    <!-- Top Header Banner (Matching PDF Deep Emerald & Gold Theme 100%) -->
    <div class="ng-banner-title p-6 sm:p-8 flex flex-col lg:flex-row items-start lg:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="pdf-num-badge">👤</span>
                <span class="text-xs text-amber-400 font-extrabold tracking-[3px] uppercase">DEX TRADE MEMBER PORTAL</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-black text-white font-heading">MY PROFILE & ACCOUNT SETTINGS</h1>
            <p class="text-xs text-neutral-300 mt-1">Manage personal details, set withdrawal wallet address, and update security password.</p>
        </div>

        <div class="flex items-center gap-3">
            <div class="px-5 py-3 rounded-2xl bg-black/80 border-2 border-amber-400/80 text-amber-300 text-xs font-bold font-mono flex items-center gap-2 shadow-xl shrink-0">
                <i data-lucide="shield-check" class="w-4 h-4 text-emerald-400"></i>
                <span>Status: <strong class="{{ $user->status === 'active' ? 'text-emerald-400' : 'text-amber-400' }} text-xs font-black uppercase">{{ $user->status === 'active' ? 'ACTIVE MEMBER' : 'INACTIVE MEMBER' }}</strong></span>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="p-4 rounded-xl bg-emerald-500/20 border border-emerald-500/50 text-emerald-400 text-xs font-bold flex items-center gap-2">
            <i data-lucide="check-circle" class="w-4 h-4 text-emerald-400"></i> {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="p-4 rounded-xl bg-rose-500/20 border border-rose-500/50 text-rose-300 text-xs font-bold space-y-1">
            @foreach($errors->all() as $error)
                <div class="flex items-center gap-2">
                    <i data-lucide="alert-circle" class="w-4 h-4 text-rose-400 shrink-0"></i> {{ $error }}
                </div>
            @endforeach
        </div>
    @endif

    <!-- Profile Summary Overview Card -->
    <div class="p-6 rounded-3xl pdf-package-card space-y-6">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 pb-6 border-b border-amber-500/20">
            <div class="flex items-center gap-4">
                <!-- Bright 3D Gold Initial Badge -->
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-amber-300 via-yellow-400 to-amber-600 text-black font-black text-2xl flex items-center justify-center shadow-xl border-2 border-amber-200 shrink-0">
                    <span class="text-black font-black drop-shadow-sm">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                </div>
                <div>
                    <h2 class="text-xl font-black text-white font-heading">{{ $user->name }}</h2>
                    <p class="text-xs text-neutral-300 font-mono">{{ $user->email }} • {{ $user->mobile ?? 'Mobile Not Set' }}</p>
                    <div class="flex flex-wrap items-center gap-2 mt-1">
                        <span class="px-2.5 py-0.5 rounded bg-black/80 border border-amber-500/40 text-amber-300 font-mono font-bold text-[10px]">CODE: {{ $user->referral_code }}</span>
                        <span class="px-2.5 py-0.5 rounded bg-black/80 border border-emerald-500/40 text-emerald-400 font-mono font-bold text-[10px]">DEPOSIT: ${{ number_format($user->deposit_wallet, 2) }}</span>
                        <span class="px-2.5 py-0.5 rounded bg-black/80 border border-amber-500/40 text-amber-300 font-mono font-bold text-[10px]">EARNING: ${{ number_format($user->earning_wallet, 2) }}</span>
                    </div>
                </div>
            </div>

            <!-- Quick Withdrawal Shortcut -->
            <a href="{{ route('user.withdrawals.index') }}" class="px-5 py-2.5 rounded-full pdf-gold-ribbon text-xs font-black uppercase tracking-wider shadow-lg hover:scale-105 transition flex items-center gap-2 text-black shrink-0">
                <i data-lucide="arrow-up-right" class="w-4 h-4 text-black font-black"></i>
                <span class="text-black font-black">Request Payout &rarr;</span>
            </a>
        </div>

        <!-- Account Meta Grid Overview -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 font-mono text-xs">
            <div class="p-3.5 rounded-2xl bg-black/80 border border-amber-500/30">
                <span class="text-neutral-400 block text-[10px] uppercase font-sans font-bold">Sponsor Name:</span>
                @if($user->sponsor)
                    <strong class="text-amber-300 font-black text-sm block truncate">{{ $user->sponsor->name }}</strong>
                    <span class="text-[10px] text-neutral-400">Code: {{ $user->sponsor_code ?? $user->sponsor->referral_code }}</span>
                @elseif($user->sponsor_code)
                    <strong class="text-amber-300 font-black text-sm block truncate">{{ $user->sponsor_code }}</strong>
                    <span class="text-[10px] text-neutral-400">Code: {{ $user->sponsor_code }}</span>
                @else
                    <strong class="text-neutral-400 font-bold text-sm block truncate">No Sponsor</strong>
                    <span class="text-[10px] text-neutral-400">Code: N/A</span>
                @endif
            </div>

            <div class="p-3.5 rounded-2xl bg-black/80 border border-amber-500/30">
                <span class="text-neutral-400 block text-[10px] uppercase font-sans font-bold">Registration Date:</span>
                <strong class="text-white font-black text-sm block">{{ $user->created_at ? $user->created_at->format('M d, Y') : 'N/A' }}</strong>
                <span class="text-[10px] text-neutral-400">{{ $user->created_at ? $user->created_at->format('h:i A') : '' }}</span>
            </div>

            <div class="p-3.5 rounded-2xl bg-black/80 border border-amber-500/30">
                <span class="text-neutral-400 block text-[10px] uppercase font-sans font-bold">Withdrawal Wallet Status:</span>
                @if($user->wallet_address)
                    <strong class="text-emerald-400 font-black text-sm block uppercase truncate" title="{{ $user->wallet_address }}">SAVED & READY</strong>
                    <span class="text-[10px] text-emerald-400 truncate block">⚡ {{ Str::limit($user->wallet_address, 18) }}</span>
                @else
                    <strong class="text-amber-400 font-black text-sm block uppercase">NOT SET YET</strong>
                    <span class="text-[10px] text-amber-400 block">Add address below for instant payouts</span>
                @endif
            </div>
        </div>
    </div>

    <!-- DUAL REFERRAL LINK COPY CARDS (LEFT & RIGHT BINARY POSITION CARDS) -->
    <div class="p-6 rounded-3xl pdf-package-card space-y-4">
        <div class="flex items-center gap-3 border-b border-amber-500/20 pb-3">
            <div class="w-10 h-10 rounded-2xl pdf-gold-badge text-black font-black text-sm flex items-center justify-center shadow-md shrink-0">
                🔗
            </div>
            <div>
                <h3 class="text-base font-black text-white font-heading uppercase">OFFICIAL MEMBER REFERRAL LINKS</h3>
                <p class="text-xs text-neutral-300">Share your official Team A (Left) or Team B (Right) referral links to build your network</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- LEFT REFERRAL LINK (TEAM A / LEFT POSITION) -->
            <div class="p-4 rounded-2xl bg-black/80 border border-amber-500/40 space-y-2">
                <span class="text-[10px] font-extrabold text-amber-400 uppercase tracking-wider block">LEFT REFERRAL LINK (TEAM A)</span>
                <div class="flex flex-col sm:flex-row items-center gap-2.5">
                    <input type="text" id="leftRefInput" readonly value="{{ url('/user/register?sponsor='.$user->referral_code.'&position=left') }}" class="flex-1 w-full px-3.5 py-2 rounded-xl bg-black border border-amber-500/40 text-amber-300 font-mono text-xs truncate focus:outline-none">
                    <button type="button" onclick="navigator.clipboard.writeText(document.getElementById('leftRefInput').value); showToast('Copied!', 'Left Leg (Team A) referral link copied.', 'success');" class="px-4 py-2 rounded-xl pdf-gold-ribbon font-black text-xs uppercase tracking-wider transition shrink-0 shadow-lg flex items-center justify-center gap-1.5 whitespace-nowrap text-black cursor-pointer">
                        <i data-lucide="copy" class="w-3.5 h-3.5 text-black font-black"></i>
                        <span class="text-black font-black">Copy Left Link</span>
                    </button>
                </div>
            </div>

            <!-- RIGHT REFERRAL LINK (TEAM B / RIGHT POSITION) -->
            <div class="p-4 rounded-2xl bg-black/80 border border-emerald-500/40 space-y-2">
                <span class="text-[10px] font-extrabold text-emerald-400 uppercase tracking-wider block">RIGHT REFERRAL LINK (TEAM B)</span>
                <div class="flex flex-col sm:flex-row items-center gap-2.5">
                    <input type="text" id="rightRefInput" readonly value="{{ url('/user/register?sponsor='.$user->referral_code.'&position=right') }}" class="flex-1 w-full px-3.5 py-2 rounded-xl bg-black border border-emerald-500/40 text-emerald-300 font-mono text-xs truncate focus:outline-none">
                    <button type="button" onclick="navigator.clipboard.writeText(document.getElementById('rightRefInput').value); showToast('Copied!', 'Right Leg (Team B) referral link copied.', 'success');" class="px-4 py-2 rounded-xl pdf-gold-ribbon font-black text-xs uppercase tracking-wider transition shrink-0 shadow-lg flex items-center justify-center gap-1.5 whitespace-nowrap text-black cursor-pointer">
                        <i data-lucide="copy" class="w-3.5 h-3.5 text-black font-black"></i>
                        <span class="text-black font-black">Copy Right Link</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- FORMS IN COL-SM-6 SIDE-BY-SIDE (PROFILE & WITHDRAWAL ADDRESS LEFT 50% | CHANGE PASSWORD RIGHT 50%) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 w-full items-start">
        
        <!-- LEFT FORM (col-sm-6): Update Profile & Withdrawal USDT (BEP20) Wallet Address -->
        <div class="p-6 rounded-3xl pdf-package-card space-y-5 flex flex-col justify-between w-full">
            <div class="space-y-4">
                <div class="flex items-center gap-3 border-b border-amber-500/20 pb-3">
                    <div class="w-10 h-10 rounded-2xl pdf-gold-badge text-black font-black text-sm flex items-center justify-center shadow-md shrink-0">
                        💳
                    </div>
                    <div>
                        <h3 class="text-base font-black text-white font-heading uppercase">PROFILE & PAYOUT SETTINGS</h3>
                        <p class="text-xs text-neutral-300">Update personal info and saved Withdrawal Wallet Address</p>
                    </div>
                </div>

                <form action="{{ route('user.profile.update') }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div class="space-y-1">
                        <label class="text-xs font-bold text-amber-400 uppercase tracking-wider">Full Name *</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" placeholder="Enter your full name..." class="w-full px-4 py-2.5 rounded-xl bg-black/80 border border-amber-500/40 text-white font-semibold text-xs focus:outline-none focus:border-amber-400" required>
                    </div>

                    @if($user->status === 'active')
                        <div class="space-y-1">
                            <label class="text-xs font-bold text-amber-400 uppercase tracking-wider">Email Address <span class="text-[10px] text-neutral-400 font-normal">(Read-only for Active Members)</span></label>
                            <input type="email" value="{{ $user->email }}" readonly class="w-full px-4 py-2.5 rounded-xl bg-black/60 border border-neutral-700 text-neutral-400 font-mono text-xs cursor-not-allowed">
                        </div>

                        <div class="space-y-1">
                            <label class="text-xs font-bold text-amber-400 uppercase tracking-wider">Mobile Number <span class="text-[10px] text-neutral-400 font-normal">(Read-only for Active Members)</span></label>
                            <input type="text" value="{{ $user->mobile }}" readonly class="w-full px-4 py-2.5 rounded-xl bg-black/60 border border-neutral-700 text-neutral-400 font-mono text-xs cursor-not-allowed">
                        </div>
                    @else
                        <div class="space-y-1">
                            <label class="text-xs font-bold text-amber-400 uppercase tracking-wider">Email Address *</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" placeholder="Enter email address..." class="w-full px-4 py-2.5 rounded-xl bg-black/80 border border-amber-500/40 text-white font-mono text-xs focus:outline-none focus:border-amber-400" required>
                        </div>

                        <div class="space-y-1">
                            <label class="text-xs font-bold text-amber-400 uppercase tracking-wider">Mobile Number *</label>
                            <input type="text" name="mobile" value="{{ old('mobile', $user->mobile) }}" placeholder="Enter mobile phone number..." class="w-full px-4 py-2.5 rounded-xl bg-black/80 border border-amber-500/40 text-white font-mono text-xs focus:outline-none focus:border-amber-400" required>
                        </div>
                    @endif

                    <!-- WITHDRAWAL USDT (BEP20) WALLET ADDRESS -->
                    <div class="space-y-1 p-3.5 rounded-2xl bg-black/90 border-2 border-amber-500/50">
                        <div class="flex items-center justify-between mb-1">
                            <label class="text-xs font-black text-amber-400 uppercase tracking-wider flex items-center gap-1">
                                💎 USDT (BEP20) WITHDRAWAL ADDRESS
                            </label>
                            @if($user->wallet_address)
                                <span class="text-[9px] text-emerald-400 font-mono font-bold">AUTO-FETCH ACTIVE</span>
                            @endif
                        </div>
                        <input type="text" name="wallet_address" value="{{ old('wallet_address', $user->wallet_address) }}" placeholder="Enter USDT BEP20 Wallet Address (0x...)" class="w-full px-4 py-2.5 rounded-xl bg-black border border-amber-500/40 text-amber-300 font-mono text-xs focus:outline-none focus:border-amber-400">
                        <p class="text-[10px] text-neutral-400 mt-1">Saved address will automatically auto-fill whenever you make a withdrawal request!</p>
                    </div>

                    <div class="space-y-1">
                        <label class="text-xs font-bold text-amber-400 uppercase tracking-wider">Referral Code <span class="text-[10px] text-neutral-400 font-normal">(Read-only)</span></label>
                        <input type="text" value="{{ $user->referral_code }}" readonly class="w-full px-4 py-2.5 rounded-xl bg-black/60 border border-neutral-700 text-amber-300 font-mono font-bold text-xs cursor-not-allowed">
                    </div>

                    <div class="pt-2 flex justify-end">
                        <button type="submit" class="px-6 py-2.5 rounded-full pdf-gold-ribbon font-black text-xs uppercase tracking-wider shadow-lg hover:scale-105 transition flex items-center gap-2 text-black">
                            <i data-lucide="save" class="w-4 h-4 text-black font-black"></i>
                            <span class="text-black font-black">Save Profile & Wallet Address</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- RIGHT FORM (col-sm-6): Change Account Password with Eye Toggle & Placeholders -->
        <div class="p-6 rounded-3xl pdf-package-card space-y-5 flex flex-col justify-between w-full">
            <div class="space-y-4">
                <div class="flex items-center gap-3 border-b border-amber-500/20 pb-3">
                    <div class="w-10 h-10 rounded-2xl pdf-gold-badge text-black font-black text-sm flex items-center justify-center shadow-md shrink-0">
                        🔒
                    </div>
                    <div>
                        <h3 class="text-base font-black text-white font-heading uppercase">CHANGE ACCOUNT PASSWORD</h3>
                        <p class="text-xs text-neutral-300">Ensure account security by updating your login password</p>
                    </div>
                </div>

                <form action="{{ route('user.password.update') }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <!-- Current Password Field with Eye Toggle & Placeholder -->
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-amber-400 uppercase tracking-wider">Current Password *</label>
                        <div class="relative flex items-center">
                            <input type="password" id="current_password" name="current_password" placeholder="Enter your current password..." class="w-full pl-4 pr-12 py-2.5 rounded-xl bg-black/80 border border-amber-500/40 text-white font-semibold text-xs focus:outline-none focus:border-amber-400" required>
                            <button type="button" onclick="togglePasswordVisibility('current_password', 'eye_icon_current')" class="absolute right-3 top-1/2 -translate-y-1/2 p-1.5 z-20 text-amber-400 hover:text-amber-200 focus:outline-none" title="Toggle Password Visibility">
                                <svg id="eye_icon_current" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#f3ca52" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5 text-amber-400 block">
                                    <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z" stroke="#f3ca52"></path>
                                    <circle cx="12" cy="12" r="3" stroke="#f3ca52"></circle>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- New Password Field with Eye Toggle & Placeholder -->
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-amber-400 uppercase tracking-wider">New Password *</label>
                        <div class="relative flex items-center">
                            <input type="password" id="new_password" name="password" placeholder="Enter new password (min 6 characters)..." class="w-full pl-4 pr-12 py-2.5 rounded-xl bg-black/80 border border-amber-500/40 text-white font-semibold text-xs focus:outline-none focus:border-amber-400" required>
                            <button type="button" onclick="togglePasswordVisibility('new_password', 'eye_icon_new')" class="absolute right-3 top-1/2 -translate-y-1/2 p-1.5 z-20 text-amber-400 hover:text-amber-200 focus:outline-none" title="Toggle Password Visibility">
                                <svg id="eye_icon_new" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#f3ca52" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5 text-amber-400 block">
                                    <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z" stroke="#f3ca52"></path>
                                    <circle cx="12" cy="12" r="3" stroke="#f3ca52"></circle>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Confirm New Password Field with Eye Toggle & Placeholder -->
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-amber-400 uppercase tracking-wider">Confirm New Password *</label>
                        <div class="relative flex items-center">
                            <input type="password" id="confirm_password" name="password_confirmation" placeholder="Confirm new password..." class="w-full pl-4 pr-12 py-2.5 rounded-xl bg-black/80 border border-amber-500/40 text-white font-semibold text-xs focus:outline-none focus:border-amber-400" required>
                            <button type="button" onclick="togglePasswordVisibility('confirm_password', 'eye_icon_confirm')" class="absolute right-3 top-1/2 -translate-y-1/2 p-1.5 z-20 text-amber-400 hover:text-amber-200 focus:outline-none" title="Toggle Password Visibility">
                                <svg id="eye_icon_confirm" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#f3ca52" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5 text-amber-400 block">
                                    <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z" stroke="#f3ca52"></path>
                                    <circle cx="12" cy="12" r="3" stroke="#f3ca52"></circle>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="pt-2 flex justify-end">
                        <button type="submit" class="px-6 py-2.5 rounded-full pdf-gold-ribbon font-black text-xs uppercase tracking-wider shadow-lg hover:scale-105 transition flex items-center gap-2 text-black">
                            <i data-lucide="key-round" class="w-4 h-4 text-black font-black"></i>
                            <span class="text-black font-black">Update Security Password</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>

</div>

<script>
    function togglePasswordVisibility(inputId, svgId) {
        const input = document.getElementById(inputId);
        const svg = document.getElementById(svgId);

        if (!input || !svg) return;

        if (input.type === 'password') {
            input.type = 'text';
            svg.innerHTML = `<path d="M9.88 9.88a3 3 0 1 0 4.24 4.24" stroke="#f3ca52" stroke-width="2.5"></path><path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68" stroke="#f3ca52" stroke-width="2.5"></path><path d="M6.61 6.61A13.52 13.52 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61" stroke="#f3ca52" stroke-width="2.5"></path><line x1="2" y1="2" x2="22" y2="22" stroke="#f3ca52" stroke-width="2.5"></line>`;
        } else {
            input.type = 'password';
            svg.innerHTML = `<path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z" stroke="#f3ca52" stroke-width="2.5"></path><circle cx="12" cy="12" r="3" stroke="#f3ca52" stroke-width="2.5"></circle>`;
        }
    }
</script>
@endsection
