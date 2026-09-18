<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('placement_parent_code')->nullable()->after('sponsor_code');
            $table->index(['placement_parent_code', 'position']);
        });

        $users = DB::table('users')->orderBy('created_at')->orderBy('id')->get();

        foreach ($users as $user) {
            if (! $user->sponsor_code || ! $user->position) {
                continue;
            }

            $placementParentCode = $this->findPlacementParentCode($user->sponsor_code, $user->position);

            DB::table('users')
                ->where('id', $user->id)
                ->update(['placement_parent_code' => $placementParentCode]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['placement_parent_code', 'position']);
            $table->dropColumn('placement_parent_code');
        });
    }

    private function findPlacementParentCode(string $sponsorCode, string $position): string
    {
        $queue = [$sponsorCode];

        while ($queue !== []) {
            $candidateCode = array_shift($queue);
            $hasPosition = DB::table('users')
                ->where('placement_parent_code', $candidateCode)
                ->where('position', $position)
                ->exists();

            if (! $hasPosition) {
                return $candidateCode;
            }

            $queue = array_merge(
                $queue,
                DB::table('users')
                    ->where('placement_parent_code', $candidateCode)
                    ->orderBy('created_at')
                    ->orderBy('id')
                    ->pluck('referral_code')
                    ->all(),
            );
        }

        return $sponsorCode;
    }
};
