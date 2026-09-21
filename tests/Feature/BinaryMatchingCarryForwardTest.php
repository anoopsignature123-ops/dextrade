<?php

namespace Tests\Feature;

use App\Models\Package;
use App\Models\User;
use App\Models\UserPackage;
use App\Services\Incomes\MatchingIncomeService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BinaryMatchingCarryForwardTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_option_b_matching_first_pair_1_to_1_and_carry_forward(): void
    {
        $root = User::factory()->create(['status' => 'active', 'referral_code' => 'TEST-ROOT']);

        $leftDirect = User::factory()->create([
            'status' => 'active',
            'referral_code' => 'TEST-LEFT',
            'sponsor_code' => $root->referral_code,
            'placement_parent_code' => $root->referral_code,
            'position' => 'left',
        ]);

        $rightDirect = User::factory()->create([
            'status' => 'active',
            'referral_code' => 'TEST-RIGHT',
            'sponsor_code' => $root->referral_code,
            'placement_parent_code' => $root->referral_code,
            'position' => 'right',
        ]);

        $pkg = Package::first();

        UserPackage::create([
            'user_id' => $root->id,
            'package_id' => $pkg->id ?? 1,
            'invested_amount' => 1000.00,
            'status' => 'active',
        ]);

        // Left volume = $500, Right volume = $200
        UserPackage::create([
            'user_id' => $leftDirect->id,
            'package_id' => $pkg->id ?? 1,
            'invested_amount' => 500.00,
            'status' => 'active',
        ]);

        UserPackage::create([
            'user_id' => $rightDirect->id,
            'package_id' => $pkg->id ?? 1,
            'invested_amount' => 200.00,
            'status' => 'active',
        ]);

        $matchingService = app(MatchingIncomeService::class);

        $this->assertFalse((bool) $root->is_first_pair_matched);

        // First Match (1:1 ratio requirement under Option B)
        $paid = $matchingService->processUserMatching($root, 500.00, 200.00);

        $root->refresh();

        // 1st pair is completed
        $this->assertTrue((bool) $root->is_first_pair_matched);
        $this->assertEquals(200.00, (float) $root->left_matched_bv);
        $this->assertEquals(200.00, (float) $root->right_matched_bv);

        // Left Carry Forward should be $300 ($500 - $200)
        $leftCarry = (float) $root->left_bv - (float) $root->left_matched_bv;
        $this->assertEquals(300.00, $leftCarry);

        // 90% credited to user (10% deducted for upline pool) -> 90% of $20 (10% of $200) = $18.00
        $this->assertEquals(18.00, $paid);
    }
}
