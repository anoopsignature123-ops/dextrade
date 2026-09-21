<?php

namespace Tests\Feature;

use App\Models\Package;
use App\Models\User;
use App\Models\UserPackage;
use App\Services\Incomes\DirectIncomeService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InactiveSponsorDirectIncomeTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_inactive_sponsor_receives_10_percent_direct_income(): void
    {
        $pkg = Package::first();

        // Inactive Sponsor with active package so working cap > 0
        $sponsor = User::factory()->create([
            'status' => 'inactive',
            'referral_code' => 'SPONSOR-INACTIVE',
            'earning_wallet' => 0.00,
        ]);

        UserPackage::create([
            'user_id' => $sponsor->id,
            'package_id' => $pkg->id ?? 1,
            'invested_amount' => 1000.00,
            'status' => 'active',
        ]);

        $purchaser = User::factory()->create([
            'status' => 'active',
            'referral_code' => 'PURCHASER-01',
            'sponsor_code' => $sponsor->referral_code,
        ]);

        $userPkg = UserPackage::create([
            'user_id' => $purchaser->id,
            'package_id' => $pkg->id ?? 1,
            'invested_amount' => 500.00,
            'status' => 'active',
        ]);

        $directService = app(DirectIncomeService::class);
        $commission = $directService->distributeDirectCommission($purchaser, $userPkg, 500.00);

        // 10% of $500 = $50
        $this->assertEquals(50.00, $commission);
        $this->assertEquals(50.00, (float) $sponsor->fresh()->earning_wallet);
    }

    public function test_inactive_user_cannot_request_withdrawal(): void
    {
        $user = User::factory()->create([
            'status' => 'inactive',
            'earning_wallet' => 100.00,
        ]);

        $response = $this->actingAs($user)->post(route('user.withdrawals.store'), [
            'amount' => 50,
            'usdt_address' => '0x1234567890abcdef1234567890abcdef12345678',
        ]);

        $response->assertSessionHas('error');
        $this->assertEquals(100.00, (float) $user->fresh()->earning_wallet);
    }

    public function test_active_user_can_request_withdrawal(): void
    {
        $user = User::factory()->create([
            'status' => 'active',
            'earning_wallet' => 100.00,
        ]);

        $response = $this->actingAs($user)->post(route('user.withdrawals.store'), [
            'amount' => 50,
            'usdt_address' => '0x1234567890abcdef1234567890abcdef12345678',
        ]);

        $response->assertRedirect(route('user.withdrawals.history'));
        $this->assertEquals(50.00, (float) $user->fresh()->earning_wallet);
    }
}
