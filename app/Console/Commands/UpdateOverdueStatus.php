<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Borrowing;
use Carbon\Carbon;

class UpdateOverdueStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'borrowings:update-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates the status of overdue borrowings to "late".';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Find all borrowings that have not been returned and are overdue.
        // The query remains the same as your `overdue()` function.
        $overdueBorrowings = Borrowing::whereNull('return_date')
                                     ->where('due_date', '<', now())
                                     ->get();

        // Loop through each overdue record and update the status.
        foreach ($overdueBorrowings as $borrowing) {
            if ($borrowing->status !== 'late') {
                $borrowing->update(['status' => 'late']);
            }
        }

        $this->info('Borrowing statuses updated successfully.');
        return Command::SUCCESS;
    }
}
