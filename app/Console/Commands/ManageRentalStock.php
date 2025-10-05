<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\StockAvailabilityService;

class ManageRentalStock extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rental:manage-stock {--force : Force processing even if not in production}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process scheduled stock operations for rentals (deductions and returns based on dates)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting rental stock management...');
        
        $stockService = new StockAvailabilityService();
        
        try {
            $result = $stockService->processScheduledStockOperations();
            
            $this->info("Stock operations completed successfully:");
            $this->line("- Stock deductions processed: {$result['deductions_processed']}");
            $this->line("- Stock returns processed: {$result['returns_processed']}");
            
            if ($result['deductions_processed'] > 0 || $result['returns_processed'] > 0) {
                $this->info('Stock levels have been updated based on rental schedules.');
            } else {
                $this->comment('No stock operations were due for processing.');
            }
            
        } catch (\Exception $e) {
            $this->error('Failed to process stock operations: ' . $e->getMessage());
            return 1;
        }
        
        return 0;
    }
}
