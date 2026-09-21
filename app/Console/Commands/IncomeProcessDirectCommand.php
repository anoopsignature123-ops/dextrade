<?php

namespace App\Console\Commands;

use App\Models\Transaction;
use App\Models\User;
use App\Models\UserPackage;
use App\Services\Incomes\DirectIncomeService;
use Illuminate\Console\Command;

class IncomeProcessDirectCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'income:process-direct {user? : Optional User ID, Email, or Referral Code of sponsor to target}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Manually process backdated 10% Direct Referral Commissions for active package investments (including inactive sponsors)';

    /**
     * Execute the console command.
     */
    public function handle(DirectIncomeService $directService): int
    {
        $userInput = $this->argument('user');

        if ($userInput) {
            $sponsors = User::where('id', $userInput)
                ->orWhere('email', $userInput)
                ->orWhere('referral_code', $userInput)
                ->get();
        } else {
            $sponsors = User::all();
        }

        if ($sponsors->isEmpty()) {
            $this->error('No users found matching your selection criteria.');

            return Command::FAILURE;
        }

        $this->info('Starting Manual Direct Income Calculation for '.$sponsors->count().' sponsor(s)...');

        $rows = [];
        $totalProcessedCount = 0;
        $totalAmountDistributed = 0.0;

        foreach ($sponsors as $sponsor) {
            // Find direct members sponsored by this user
            $directs = User::where('sponsor_code', $sponsor->referral_code)->get();

            if ($directs->isEmpty()) {
                continue;
            }

            foreach ($directs as $direct) {
                // Find all active packages for this direct member
                $packages = UserPackage::where('user_id', $direct->id)->get();

                foreach ($packages as $pkg) {
                    // Check if 10% direct commission transaction already exists for this package investment
                    $alreadyProcessed = Transaction::where('user_id', $sponsor->id)
                        ->where('type', 'direct_commission')
                        ->where('reference_id', $pkg->id)
                        ->exists();

                    if (! $alreadyProcessed && (float) $pkg->invested_amount > 0) {
                        $commissionPaid = $directService->distributeDirectCommission($direct, $pkg, (float) $pkg->invested_amount);

                        if ($commissionPaid > 0) {
                            $totalProcessedCount++;
                            $totalAmountDistributed += $commissionPaid;

                            $rows[] = [
                                'sponsor' => $sponsor->name.' ('.$sponsor->referral_code.')',
                                'sponsor_status' => strtoupper($sponsor->status),
                                'direct_member' => $direct->name.' ('.$direct->referral_code.')',
                                'package_amount' => '$'.number_format((float) $pkg->invested_amount, 2),
                                'direct_income' => '$'.number_format($commissionPaid, 2),
                                'earning_wallet' => '$'.number_format((float) $sponsor->fresh()->earning_wallet, 2),
                            ];
                        }
                    }
                }
            }
        }

        if (empty($rows)) {
            $this->info('All direct commission records are up to date! No uncredited direct commissions found.');
        } else {
            $this->table(
                ['Sponsor', 'Status', 'Direct Member', 'Package Investment', 'Direct Income', 'Earning Wallet'],
                $rows
            );
            $this->info("Successfully processed {$totalProcessedCount} direct commission(s) totaling \$".number_format($totalAmountDistributed, 2).'!');
        }

        return Command::SUCCESS;
    }
}
