<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Minishlink\WebPush\VAPID;

class GenerateVapidKeys extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:generate-keys';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate VAPID keys for push notifications';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Generating VAPID keys...');
        
        try {
            $vapidKeys = VAPID::createVapidKeys();
            
            $this->info('VAPID keys generated successfully!');
            $this->info('Add the following lines to your .env file:');
            $this->newLine();
            $this->line('VAPID_PUBLIC_KEY="' . $vapidKeys['publicKey'] . '"');
            $this->line('VAPID_PRIVATE_KEY="' . $vapidKeys['privateKey'] . '"');
            $this->line('VAPID_SUBJECT="mailto:admin@example.com"');
            $this->newLine();
            $this->info('Replace the email address with your own.');
            
            return 0;
        } catch (\Exception $e) {
            $this->error('Failed to generate VAPID keys: ' . $e->getMessage());
            
            return 1;
        }
    }
} 