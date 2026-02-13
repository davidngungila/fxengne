<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\NotificationCheckerService;

class CheckNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for new trading events and create notifications';

    /**
     * Execute the console command.
     */
    public function handle(NotificationCheckerService $checkerService)
    {
        $this->info('Checking for new notifications...');
        
        try {
            $checkerService->runAllChecks();
            $this->info('Notification check completed successfully.');
        } catch (\Exception $e) {
            $this->error('Error checking notifications: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }
}

            $checkerService->runAllChecks();
            $this->info('Notification check completed successfully.');
        } catch (\Exception $e) {
            $this->error('Error checking notifications: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }
}
