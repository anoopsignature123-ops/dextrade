<?php

namespace Tests\Feature;

use App\Models\Package;
use App\Models\Setting;
use App\Models\User;
use App\Models\UserPackage;
use App\Services\Incomes\DirectIncomeService;
use App\Services\Incomes\MatchingIncomeService;
use App\Services\Incomes\RoiIncomeService;
use App\Services\User\DepositService;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class DexTradeIncomeEngineTest extends TestCase
{
    use DatabaseTransactions;

    protected Package $package;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RoleSeeder::class);

        $this->package = Package::create([
            'name' => 'Dex Trade Package',
            'min_amount' => 10.00,
            'max_amount' => 100000.00,
            'daily_roi' => 0.50,
            'duration_days' => 400,
            'total_return_multiplier' => 2.00,
            'status' => 'active',
            'description' => 'Dex Trade Package Test',
        ]);
    }

    public function test_roi_income_0_5_percent_and_2x_non_working_cap(): void
    {
        $user = User::factory()->create([
            'status' => 'active',
            'is_bot_active' => true,
            'earning_wallet' => 0.00,
        ]);

        $userPackage = UserPackage::create([
            'user_id' => $user->id,
            'package_id' => $this->package->id,
            'invested_amount' => 100.00, // 2X Cap = $200
            'daily_roi' => 0.50,
            'daily_roi_amount' => 0.50,
            'duration_days' => 400,
            'total_return_amount' => 200.00,
            'paid_roi_amount' => 0.00,
            'status' => 'active',
        ]);

        $roiService = app(RoiIncomeService::class);
        $credited = $roiService->processSinglePackageRoi($userPackage);

        $this->assertEquals(0.50, round($credited, 2));
        $this->assertEquals(0.50, round($user->fresh()->earning_wallet, 2));

        // Test non-working 2X capping limit
        $userPackage->update(['paid_roi_amount' => 199.80]);
        $credited2 = $roiService->processSinglePackageRoi($userPackage);

        $this->assertEquals(0.20, round($credited2, 2));
        $this->assertEquals(0.70, round($user->fresh()->earning_wallet, 2));
        $this->assertEquals('completed', $userPackage->fresh()->status);
    }

    public function test_roi_income_skipped_when_bot_inactive(): void
    {
        $user = User::factory()->create([
            'status' => 'active',
            'is_bot_active' => false,
            'earning_wallet' => 0.00,
        ]);

        $userPackage = UserPackage::create([
            'user_id' => $user->id,
            'package_id' => $this->package->id,
            'invested_amount' => 100.00,
            'daily_roi' => 0.50,
            'daily_roi_amount' => 0.50,
            'duration_days' => 400,
            'total_return_amount' => 200.00,
            'paid_roi_amount' => 0.00,
            'status' => 'active',
        ]);

        $roiService = app(RoiIncomeService::class);
        $credited = $roiService->processSinglePackageRoi($userPackage);

        $this->assertEquals(0.00, $credited);
        $this->assertEquals(0.00, $user->fresh()->earning_wallet);

        // Activate bot and test distribution now succeeds
        $user->update(['is_bot_active' => true]);
        $userPackage->load('user');
        $creditedActive = $roiService->processSinglePackageRoi($userPackage);

        $this->assertEquals(0.50, round($creditedActive, 2));
        $this->assertEquals(0.50, round($user->fresh()->earning_wallet, 2));
    }

    public function test_direct_income_10_percent_and_8x_working_cap(): void
    {
        $sponsor = User::factory()->create([
            'referral_code' => 'DEX-1111111',
            'status' => 'active',
            'earning_wallet' => 0.00,
        ]);

        // Give sponsor a $100 package (8X Working Cap = $800)
        UserPackage::create([
            'user_id' => $sponsor->id,
            'package_id' => $this->package->id,
            'invested_amount' => 100.00,
            'daily_roi' => 0.50,
            'daily_roi_amount' => 0.50,
            'duration_days' => 400,
            'total_return_amount' => 200.00,
            'paid_roi_amount' => 0.00,
            'status' => 'active',
        ]);

        $purchaser = User::factory()->create([
            'sponsor_code' => 'DEX-1111111',
            'status' => 'active',
        ]);

        $userPackage = UserPackage::create([
            'user_id' => $purchaser->id,
            'package_id' => $this->package->id,
            'invested_amount' => 500.00, // 10% = $50
            'daily_roi' => 0.50,
            'daily_roi_amount' => 2.50,
            'duration_days' => 400,
            'total_return_amount' => 1000.00,
            'paid_roi_amount' => 0.00,
            'status' => 'active',
        ]);

        $directService = app(DirectIncomeService::class);
        $commission = $directService->distributeDirectCommission($purchaser, $userPackage, 500.00);

        $this->assertEquals(50.00, $commission);
        $this->assertEquals(50.00, $sponsor->fresh()->earning_wallet);
    }

    public function test_binary_matching_10_percent_with_2_to_1_requirement(): void
    {
        $user = User::factory()->create([
            'referral_code' => 'DEX-MAIN',
            'status' => 'active',
            'earning_wallet' => 0.00,
        ]);

        UserPackage::create([
            'user_id' => $user->id,
            'package_id' => $this->package->id,
            'invested_amount' => 1000.00,
            'status' => 'active',
        ]);

        // 1st Left direct referral
        User::factory()->create([
            'sponsor_code' => 'DEX-MAIN',
            'position' => 'left',
            'status' => 'active',
        ]);

        // Right direct referral
        User::factory()->create([
            'sponsor_code' => 'DEX-MAIN',
            'position' => 'right',
            'status' => 'active',
        ]);

        $matchingService = app(MatchingIncomeService::class);

        // 1 Left & 1 Right (1:1 only) => Fails 2:1 requirement ($0 paid)
        $noMatching = $matchingService->processUserMatching($user, 5000.00, 3000.00);
        $this->assertEquals(0.00, $noMatching);

        // Add 2nd Left direct referral (now 2 Left, 1 Right = 2:1 ratio met)
        User::factory()->create([
            'sponsor_code' => 'DEX-MAIN',
            'position' => 'left',
            'status' => 'active',
        ]);

        // Power Leg $5,000, Weaker Leg $3,000 => Matched $3,000 => 10% = $300
        // 10% deducted for Upline ($30), Net to user = $270
        $netMatching = $matchingService->processUserMatching($user, 5000.00, 3000.00);

        $this->assertEquals(270.00, $netMatching);
        $this->assertEquals(270.00, $user->fresh()->earning_wallet);
    }

    public function test_left_and_right_registration_and_referral_link_generation(): void
    {
        $sponsor = User::factory()->create([
            'referral_code' => 'DEX-SPONSOR1',
            'status' => 'active',
        ]);

        $leftUser = User::create([
            'role_id' => 2,
            'name' => 'Left Member',
            'email' => 'left@dextrade.com',
            'mobile' => '9876543210',
            'referral_code' => 'DEX-LEFT01',
            'sponsor_code' => 'DEX-SPONSOR1',
            'position' => 'left',
            'status' => 'inactive',
            'password' => Hash::make('password123'),
        ]);

        $rightUser = User::create([
            'role_id' => 2,
            'name' => 'Right Member',
            'email' => 'right@dextrade.com',
            'mobile' => '9876543211',
            'referral_code' => 'DEX-RIGHT01',
            'sponsor_code' => 'DEX-SPONSOR1',
            'position' => 'right',
            'status' => 'inactive',
            'password' => Hash::make('password123'),
        ]);

        $this->assertNotNull($leftUser);
        $this->assertEquals('left', $leftUser->position);
        $this->assertEquals('DEX-SPONSOR1', $leftUser->sponsor_code);

        $this->assertNotNull($rightUser);
        $this->assertEquals('right', $rightUser->position);

        // Verify Left & Right child tree resolution on sponsor
        $this->assertEquals($leftUser->id, $sponsor->leftChild()->id);
        $this->assertEquals($rightUser->id, $sponsor->rightChild()->id);
    }

    public function test_payment_gateway_simulation_mode_and_setting_configuration(): void
    {
        Setting::setValue('payment_test_mode', 'true');
        Setting::setValue('usdt_wallet_address', '0xTESTWALLETHASH123456');

        $this->assertTrue(Setting::isPaymentTestMode());
        $this->assertEquals('0xTESTWALLETHASH123456', Setting::getUsdtWalletAddress());

        $user = User::factory()->create(['deposit_wallet' => 0.00]);
        $depositService = app(DepositService::class);

        $result = $depositService->createCustomFund($user, 100.00);
        $this->assertTrue($result['success']);
        $deposit = $result['deposit'];

        $isVerified = $depositService->verifyAndProcessDeposit($deposit);
        $this->assertTrue($isVerified);
        $this->assertEquals('approved', $deposit->fresh()->status);
        $this->assertEquals(100.00, $user->fresh()->deposit_wallet);
    }
}
