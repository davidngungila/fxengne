<?php

namespace App\Console\Commands;

use App\Services\TradingBotService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class RunTradingBot extends Command
{
    protected $signature = 'trading:bot {--interval=30 : Interval in seconds between cycles}';
    protected $description = 'Run the automated trading bot';

    protected $tradingBot;

    public function __construct(TradingBotService $tradingBot)
    {
        parent::__construct();
        $this->tradingBot = $tradingBot;
    }

    public function handle()
    {
        $interval = (int) $this->option('interval');
        $interval = max(10, min(300, $interval)); // Between 10 and 300 seconds

        $this->info("Starting automated trading bot (interval: {$interval}s)");

        // Start the bot
        $this->tradingBot->start();

        $this->info('Trading bot is running. Press Ctrl+C to stop.');

        while (true) {
            try {
                if (!$this->tradingBot->isRunning()) {
                    $this->info('Trading bot stopped.');
                    break;
                }

                $this->info('Executing trading cycle...');
                $this->tradingBot->executeCycle();
                
                $this->info("Waiting {$interval} seconds before next cycle...");
                sleep($interval);

            } catch (\Exception $e) {
                $this->error('Error in trading bot: ' . $e->getMessage());
                Log::error('Trading bot error', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                
                sleep($interval);
            }
        }
    }
}

