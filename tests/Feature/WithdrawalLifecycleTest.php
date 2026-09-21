<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Withdrawal;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WithdrawalLifecycleTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_three_step_withdrawal_lifecycle_pending_to_approved_to_completed(): void
    {
        $admin = User::where('role_id', 1)->first() ?? User::factory()->create(['role_id' => 1, 'status' => 'active']);
        $user = User::factory()->create([
            'role_id' => 2,
            'status' => 'active',
            'earning_wallet' => 100.00,
        ]);

        // Step 1: User submits a $50 withdrawal request (Status: PENDING)
        $response = $this->actingAs($user)->post(route('user.withdrawals.store'), [
            'amount' => 50.00,
            'usdt_address' => '0x1234567890abcdef1234567890abcdef12345678',
        ]);

        $response->assertRedirect(route('user.withdrawals.history'));

        $withdrawal = Withdrawal::where('user_id', $user->id)->first();
        $this->assertNotNull($withdrawal);
        $this->assertEquals('pending', $withdrawal->status);
        $this->assertEquals(50.00, (float) $withdrawal->amount);
        $this->assertEquals(45.00, (float) $withdrawal->net_amount);
        $this->assertEquals(50.00, (float) $user->fresh()->earning_wallet);

        // Step 2: Admin approves the request (Status: PENDING -> APPROVED)
        $approveResponse = $this->actingAs($admin)->post(route('admin.withdrawals.approve', $withdrawal->id));
        $approveResponse->assertRedirect();

        $withdrawal->refresh();
        $this->assertEquals('approved', $withdrawal->status);

        // Step 3: Admin completes the request with Txn Hash (Status: APPROVED -> COMPLETED)
        $completeResponse = $this->actingAs($admin)->post(route('admin.withdrawals.complete', $withdrawal->id), [
            'txn_hash' => '0x999888777666555444333222111000',
            'admin_remark' => 'Transferred via USDT BEP20 Hash: 0x999888777666555444333222111000',
        ]);
        $completeResponse->assertRedirect();

        $withdrawal->refresh();
        $this->assertEquals('completed', $withdrawal->status);
        $this->assertEquals('0x999888777666555444333222111000', $withdrawal->txn_hash);
    }

    public function test_rejection_refunds_earning_wallet(): void
    {
        $admin = User::where('role_id', 1)->first() ?? User::factory()->create(['role_id' => 1, 'status' => 'active']);
        $user = User::factory()->create([
            'role_id' => 2,
            'status' => 'active',
            'earning_wallet' => 100.00,
        ]);

        // User submits $40 request
        $this->actingAs($user)->post(route('user.withdrawals.store'), [
            'amount' => 40.00,
            'usdt_address' => '0x1234567890abcdef1234567890abcdef12345678',
        ]);

        $this->assertEquals(60.00, (float) $user->fresh()->earning_wallet);
        $withdrawal = Withdrawal::where('user_id', $user->id)->first();

        // Admin rejects request
        $this->actingAs($admin)->post(route('admin.withdrawals.reject', $withdrawal->id), [
            'admin_remark' => 'Invalid USDT address.',
        ]);

        $withdrawal->refresh();
        $this->assertEquals('rejected', $withdrawal->status);

        // Wallet refunded back to $100
        $this->assertEquals(100.00, (float) $user->fresh()->earning_wallet);
    }

    public function test_minimum_withdrawal_amount_validation_five_dollars(): void
    {
        $user = User::factory()->create([
            'role_id' => 2,
            'status' => 'active',
            'earning_wallet' => 50.00,
        ]);

        // Attempting $4 (under $5 min) should trigger validation error
        $response = $this->actingAs($user)->post(route('user.withdrawals.store'), [
            'amount' => 4.00,
            'usdt_address' => '0x1234567890abcdef1234567890abcdef12345678',
        ]);

        $response->assertSessionHasErrors('amount');
        $this->assertEquals(0, Withdrawal::count());

        // Submitting $5 (exact min) should pass validation and create withdrawal
        $validResponse = $this->actingAs($user)->post(route('user.withdrawals.store'), [
            'amount' => 5.00,
            'usdt_address' => '0x1234567890abcdef1234567890abcdef12345678',
        ]);

        $validResponse->assertRedirect(route('user.withdrawals.history'));
        $this->assertEquals(1, Withdrawal::count());
        $this->assertEquals(45.00, (float) $user->fresh()->earning_wallet);
    }
}
