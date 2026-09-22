<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\Incomes\RoiIncomeService;
use Illuminate\Console\Command;

class IncomeProcessRoiCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'income:process-roi {user? : Target User ID, Email, or Referral Code (e.g. DEX-1339361)} {--fill-missing : Process all missing historical ROI days from activation date}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Manually process Daily ROI Income (0.5%) for a specific target user or all active users';

    /**
     * Execute the console command.
     */
    public function handle(RoiIncomeService $roiService): int
    {
        $userInput = $this->argument('user');

        if ($userInput) {
            $users = User::where('id', $userInput)
                ->orWhere('email', $userInput)
                ->orWhere('referral_code', $userInput)
                ->get();
        } else {
            $users = User::where('status', 'active')->where('is_bot_active', true)->get();
        }

        if ($users->isEmpty()) {
            $this->error("No active user(s) found matching selection criteria: {$userInput}");

            return Command::FAILURE;
        }

        $this->info('Starting Manual 0.5% Daily ROI Processing for '.$users->count().' user(s)...');

        $rows = [];
        $totalRoiPaid = 0.0;

        foreach ($users as $user) {
            $amountCredited = $roiService->processUserDailyRoi($user);
            $freshUser = $user->fresh();

            if ($amountCredited > 0 || $userInput) {
                $totalRoiPaid += $amountCredited;
                $rows[] = [
                    'user' => $freshUser->name.' ('.$freshUser->referral_code.')',
                    'status' => strtoupper($freshUser->status),
                    'bot_active' => $freshUser->is_bot_active ? 'YES' : 'NO',
                    'daily_roi_credited' => '$'.number_format($amountCredited, 2),
                    'earning_wallet' => '$'.number_format((float) $freshUser->earning_wallet, 2),
                ];
            }
        }

        if (! empty($rows)) {
            $this->table(
                ['User / Referral', 'Status', 'Bot Active', 'Daily ROI Credited', 'Earning Wallet'],
                $rows
            );
            $this->info('Successfully processed Daily ROI Income! Total credited: $'.number_format($totalRoiPaid, 2));
        } else {
            $this->info('No active packages found requiring ROI yield distribution.');
        }

        return Command::SUCCESS;
    }
}
