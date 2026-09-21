<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->decimal('left_bv', 15, 2)->default(0.00)->after('deposit_wallet');
            $table->decimal('right_bv', 15, 2)->default(0.00)->after('left_bv');
            $table->decimal('left_matched_bv', 15, 2)->default(0.00)->after('right_bv');
            $table->decimal('right_matched_bv', 15, 2)->default(0.00)->after('left_matched_bv');
            $table->boolean('is_first_pair_matched')->default(false)->after('right_matched_bv');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'left_bv',
                'right_bv',
                'left_matched_bv',
                'right_matched_bv',
                'is_first_pair_matched',
            ]);
        });
    }
};
