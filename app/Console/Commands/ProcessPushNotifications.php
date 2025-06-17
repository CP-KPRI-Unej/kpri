<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Log;

class ProcessPushNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:process';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process due push notifications';

    /**
     * The notification service instance.
     *
     * @var \App\Services\NotificationService
     */
    protected $notificationService;

    /**
     * Create a new command instance.
     *
     * @param  \App\Services\NotificationService  $notificationService
     * @return void
     */
    public function __construct(NotificationService $notificationService)
    {
        parent::__construct();
        $this->notificationService = $notificationService;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Processing due notifications...');
        
        try {
            $count = $this->notificationService->processDueNotifications();
            
            $this->info("Successfully processed {$count} notifications.");
            
            return 0;
        } catch (\Exception $e) {
            $this->error('Failed to process notifications: ' . $e->getMessage());
            Log::error('Failed to process notifications: ' . $e->getMessage());
            
            return 1;
        }
    }
} 