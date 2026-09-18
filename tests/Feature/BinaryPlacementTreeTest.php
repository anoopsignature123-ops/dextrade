<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BinaryPlacementTreeTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function test_members_render_below_their_actual_placement_parent(): void
    {
        $root = User::factory()->create(['referral_code' => 'DEX-ROOT']);
        $left = User::factory()->create([
            'referral_code' => 'DEX-LEFT',
            'sponsor_code' => $root->referral_code,
            'placement_parent_code' => $root->referral_code,
            'position' => 'left',
        ]);
        $right = User::factory()->create([
            'referral_code' => 'DEX-RIGHT',
            'sponsor_code' => $root->referral_code,
            'placement_parent_code' => $root->referral_code,
            'position' => 'right',
        ]);
        $downline = User::factory()->create([
            'name' => 'HLM',
            'sponsor_code' => $right->referral_code,
            'placement_parent_code' => $right->referral_code,
            'position' => 'left',
        ]);

        $this->assertSame($right->referral_code, $downline->placement_parent_code);
        $this->assertSame($left->referral_code, User::findAvailablePlacementParentCode($root, 'left'));

        $this->actingAs($root)
            ->get(route('user.network.tree'))
            ->assertOk()
            ->assertSee($left->name)
            ->assertSee($right->name)
            ->assertDontSee('HLM');

        $this->actingAs($right)
            ->get(route('user.network.tree'))
            ->assertOk()
            ->assertSee('HLM');
    }
}
