<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'role_id',
        'name',
        'email',
        'mobile',
        'wallet_address',
        'referral_code',
        'sponsor_code',
        'placement_parent_code',
        'position',
        'status',
        'is_bot_active',
        'bot_activated_at',
        'deposit_wallet',
        'earning_wallet',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'activated_at' => 'datetime',
            'is_bot_active' => 'boolean',
            'bot_activated_at' => 'datetime',
            'password' => 'hashed',
            'deposit_wallet' => 'decimal:2',
            'earning_wallet' => 'decimal:2',
        ];
    }

    public function deposits(): HasMany
    {
        return $this->hasMany(Deposit::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function withdrawals(): HasMany
    {
        return $this->hasMany(Withdrawal::class);
    }

    public function userPackages(): HasMany
    {
        return $this->hasMany(UserPackage::class);
    }

    /**
     * Relationship with Role model.
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Relationship with Sponsor User by referral_code.
     */
    public function sponsor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sponsor_code', 'referral_code');
    }

    /**
     * Relationship for all direct referrals sponsored by this user.
     */
    public function directMembers(): HasMany
    {
        return $this->hasMany(User::class, 'sponsor_code', 'referral_code');
    }

    /**
     * Members positioned directly below this user in the binary placement tree.
     */
    public function placementChildren(): HasMany
    {
        return $this->hasMany(User::class, 'placement_parent_code', 'referral_code');
    }

    /**
     * Find the first open slot in the requested leg below a sponsor.
     */
    public static function findAvailablePlacementParentCode(self $sponsor, string $position): string
    {
        $queue = collect([$sponsor->referral_code]);

        while ($queue->isNotEmpty()) {
            $candidateCode = $queue->shift();
            $hasPosition = static::where('placement_parent_code', $candidateCode)
                ->where('position', $position)
                ->exists();

            if (! $hasPosition) {
                return $candidateCode;
            }

            $queue = $queue->merge(
                static::where('placement_parent_code', $candidateCode)
                    ->oldest()
                    ->pluck('referral_code'),
            );
        }

        return $sponsor->referral_code;
    }

    /**
     * Placement Left Child node in binary tree graph.
     */
    public function leftChild(): ?User
    {
        $placementLeft = User::where('placement_parent_code', $this->referral_code)
            ->where('position', 'left')
            ->orderBy('id', 'asc')
            ->first();

        if ($placementLeft) {
            return $placementLeft;
        }

        return User::where('sponsor_code', $this->referral_code)
            ->where(function ($q) {
                $q->whereNull('position')->orWhere('position', 'left');
            })
            ->orderBy('id', 'asc')
            ->first();
    }

    /**
     * Placement Right Child node in binary tree graph.
     */
    public function rightChild(): ?User
    {
        $left = $this->leftChild();

        $placementRight = User::where('placement_parent_code', $this->referral_code)
            ->where('position', 'right')
            ->when($left, function ($q) use ($left) {
                $q->where('id', '!=', $left->id);
            })
            ->orderBy('id', 'asc')
            ->first();

        if ($placementRight) {
            return $placementRight;
        }

        return User::where('sponsor_code', $this->referral_code)
            ->when($left, function ($q) use ($left) {
                $q->where('id', '!=', $left->id);
            })
            ->where('position', 'right')
            ->orderBy('id', 'asc')
            ->first();
    }

    /**
     * Get all downline team user IDs recursively for a given branch node.
     */
    public function getBranchUserIds(): array
    {
        $ids = [$this->id];
        $children = User::where('placement_parent_code', $this->referral_code)
            ->orWhere('sponsor_code', $this->referral_code)
            ->get();

        foreach ($children as $child) {
            if (! in_array($child->id, $ids, true)) {
                $ids = array_merge($ids, $child->getBranchUserIds());
            }
        }

        return array_values(array_unique($ids));
    }

    /**
     * Get Power Leg Volume & Remaining (Weaker) Leg Volume for 50:50 matching/salary rules.
     */
    public function getLegVolumeStatsAttribute(): array
    {
        $directs = User::where('sponsor_code', $this->referral_code)->get();

        if ($directs->isEmpty()) {
            return [
                'power_leg' => 0.00,
                'remaining_leg' => 0.00,
                'total_team' => 0.00,
                'power_leg_formatted' => '$0.00',
                'remaining_leg_formatted' => '$0.00',
                'total_team_formatted' => '$0.00',
            ];
        }

        $legVolumes = [];
        foreach ($directs as $direct) {
            $branchUserIds = $direct->getBranchUserIds();
            $legVolume = (float) UserPackage::whereIn('user_id', $branchUserIds)
                ->where('status', 'active')
                ->sum('invested_amount');

            $legVolumes[] = $legVolume;
        }

        rsort($legVolumes);

        $powerLeg = $legVolumes[0] ?? 0.00;
        $remainingLeg = array_sum(array_slice($legVolumes, 1));
        $totalTeam = $powerLeg + $remainingLeg;

        return [
            'power_leg' => $powerLeg,
            'remaining_leg' => $remainingLeg,
            'total_team' => $totalTeam,
            'power_leg_formatted' => '$'.number_format($powerLeg, 2),
            'remaining_leg_formatted' => '$'.number_format($remainingLeg, 2),
            'total_team_formatted' => '$'.number_format($totalTeam, 2),
        ];
    }

    /**
     * Get Power Leg team stats.
     */
    public function getLeftLegStatsAttribute(): array
    {
        $stats = $this->leg_volume_stats;
        $business = $stats['power_leg'];
        $allDirects = User::where('sponsor_code', $this->referral_code)->get();

        return [
            'active' => $allDirects->where('status', 'active')->count(),
            'inactive' => $allDirects->where('status', 'inactive')->count(),
            'total' => $allDirects->count(),
            'business' => '$'.number_format($business, 2),
            'raw_business' => $business,
        ];
    }

    /**
     * Get Remaining Leg (Weaker Leg) team stats.
     */
    public function getRightLegStatsAttribute(): array
    {
        $stats = $this->leg_volume_stats;
        $business = $stats['remaining_leg'];
        $allDirects = User::where('sponsor_code', $this->referral_code)->get();

        return [
            'active' => $allDirects->where('status', 'active')->count(),
            'inactive' => $allDirects->where('status', 'inactive')->count(),
            'total' => $allDirects->count(),
            'business' => '$'.number_format($business, 2),
            'raw_business' => $business,
        ];
    }

    /**
     * Helper to check if user is Admin.
     */
    public function isAdmin(): bool
    {
        return $this->role_id === 1 || ($this->role && $this->role->slug === 'admin');
    }

    public function matchingRoiContracts(): HasMany
    {
        return $this->hasMany(UserMatchingRoiContract::class);
    }

    public function salaries(): HasMany
    {
        return $this->hasMany(UserSalary::class);
    }

    /**
     * Get Total Active Package Investment Amount.
     */
    public function getTotalActiveInvestmentAttribute(): float
    {
        return (float) $this->userPackages()->where('status', 'active')->sum('invested_amount');
    }

    /**
     * Non-Working Income Cap Limit (2X / 200% of Active Investment).
     */
    public function getNonWorkingIncomeCapAttribute(): float
    {
        return $this->total_active_investment * 2.00;
    }

    /**
     * Working Income Cap Limit (8X / 800% of Active Investment).
     */
    public function getWorkingIncomeCapAttribute(): float
    {
        return $this->total_active_investment * 8.00;
    }

    /**
     * Total Non-Working Income (ROI) Earned.
     */
    public function getTotalNonWorkingEarnedAttribute(): float
    {
        return (float) $this->transactions()
            ->where('trx_type', '+')
            ->where('type', 'daily_roi')
            ->sum('amount');
    }

    /**
     * Total Working Income Earned.
     */
    public function getTotalWorkingEarnedAttribute(): float
    {
        return (float) $this->transactions()
            ->where('trx_type', '+')
            ->whereIn('type', [
                'direct_commission',
                'matching_income',
                'referral_roi',
                'matching_roi',
                'upline_matching',
                'salary_income',
                'level_income',
                'booster_bonus',
            ])
            ->sum('amount');
    }

    /**
     * Remaining Non-Working Income Cap Room.
     */
    public function getRemainingNonWorkingCapAttribute(): float
    {
        return max(0.00, $this->non_working_income_cap - $this->total_non_working_earned);
    }

    /**
     * Remaining Working Income Cap Room.
     */
    public function getRemainingWorkingCapAttribute(): float
    {
        return max(0.00, $this->working_income_cap - $this->total_working_earned);
    }

    /**
     * Generate unique random referral code (e.g., DEX-0967542).
     */
    public static function generateReferralCode(): string
    {
        do {
            $code = 'DEX-'.str_pad((string) rand(100000, 9999999), 7, '0', STR_PAD_LEFT);
        } while (static::where('referral_code', $code)->exists());

        return $code;
    }
}
