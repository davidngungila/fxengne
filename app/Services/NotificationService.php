<?php

namespace App\Services;

use App\Models\Notification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

class NotificationService
{
    protected $telegramEnabled = false;
    protected $emailEnabled = false;
    protected $telegramBotToken;
    protected $telegramChatId;

    public function __construct()
    {
        $this->telegramBotToken = config('services.telegram.bot_token');
        $this->telegramChatId = config('services.telegram.chat_id');
        $this->telegramEnabled = !empty($this->telegramBotToken) && !empty($this->telegramChatId);
        $this->emailEnabled = config('services.notifications.email_enabled', false);
    }

    /**
     * Create and send notification
     */
    public function create($type, $title, $message, $data = [], $severity = 'info')
    {
        // Store in database
        $notification = Notification::create([
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'data' => $data,
            'severity' => $severity,
        ]);

        // Send external notifications
        $this->send($title, $data);

        return $notification;
    }

    /**
     * Send notification via Telegram and/or Email
     */
    public function send($title, $data = [])
    {
        $message = $this->formatMessage($title, $data);

        // Send to Telegram
        if ($this->telegramEnabled) {
            $this->sendTelegram($message);
        }

        // Send to Email
        if ($this->emailEnabled) {
            $this->sendEmail($title, $message);
        }

        // Always log
        Log::info('Trading notification', [
            'title' => $title,
            'data' => $data
        ]);
    }

    /**
     * Send Telegram message
     */
    protected function sendTelegram($message)
    {
        try {
            $url = "https://api.telegram.org/bot{$this->telegramBotToken}/sendMessage";
            
            $response = Http::post($url, [
                'chat_id' => $this->telegramChatId,
                'text' => $message,
                'parse_mode' => 'HTML'
            ]);

            if (!$response->successful()) {
                Log::error('Failed to send Telegram notification', [
                    'response' => $response->body()
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Error sending Telegram notification', [
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Send Email notification
     */
    protected function sendEmail($subject, $message)
    {
        try {
            $email = config('services.notifications.email_address');
            
            if (!$email) {
                return;
            }

            Mail::raw($message, function ($mail) use ($email, $subject) {
                $mail->to($email)
                     ->subject($subject);
            });
        } catch (\Exception $e) {
            Log::error('Error sending email notification', [
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Format message for notifications
     */
    protected function formatMessage($title, $data)
    {
        $message = "<b>{$title}</b>\n\n";

        foreach ($data as $key => $value) {
            $keyFormatted = ucwords(str_replace('_', ' ', $key));
            
            if (is_array($value)) {
                $value = json_encode($value, JSON_PRETTY_PRINT);
            }
            
            $message .= "<b>{$keyFormatted}:</b> {$value}\n";
        }

        $message .= "\n<i>Time: " . now()->toDateTimeString() . "</i>";

        return $message;
    }

    /**
     * Send trade execution notification
     */
    public function sendTradeNotification($tradeData)
    {
        $title = "✅ Trade Executed";
        $message = "Trade executed: {$tradeData['instrument']} {$tradeData['direction']} {$tradeData['units']} units at {$tradeData['entry_price']}";
        
        return $this->create(
            'trade_executed',
            $title,
            $message,
            $tradeData,
            'success'
        );
    }

    /**
     * Send trade closed notification
     */
    public function sendTradeClosedNotification($tradeData)
    {
        $pl = $tradeData['realized_pl'] ?? 0;
        $severity = $pl >= 0 ? 'success' : 'danger';
        $title = $pl >= 0 ? "✅ Trade Closed (Profit)" : "❌ Trade Closed (Loss)";
        $message = "Trade closed: {$tradeData['instrument']} - P/L: $" . number_format($pl, 2);
        
        return $this->create(
            'trade_closed',
            $title,
            $message,
            $tradeData,
            $severity
        );
    }

    /**
     * Send signal notification
     */
    public function sendSignalNotification($signalData)
    {
        $title = "📊 New Trading Signal";
        $message = "New {$signalData['type']} signal for {$signalData['instrument']} - Strength: {$signalData['strength']}%";
        
        return $this->create(
            'signal_generated',
            $title,
            $message,
            $signalData,
            'info'
        );
    }

    /**
     * Send risk alert notification
     */
    public function sendRiskAlert($alertData)
    {
        $title = "⚠️ Risk Alert";
        $message = $alertData['message'] ?? "Risk threshold reached";
        
        return $this->create(
            'risk_alert',
            $title,
            $message,
            $alertData,
            'warning'
        );
    }

    /**
     * Send drawdown alert
     */
    public function sendDrawdownAlert($drawdownData)
    {
        $title = "📉 Drawdown Alert";
        $message = "Current drawdown: {$drawdownData['drawdown']}% - Limit: {$drawdownData['limit']}%";
        
        return $this->create(
            'drawdown_alert',
            $title,
            $message,
            $drawdownData,
            'warning'
        );
    }

    /**
     * Send daily limit alert
     */
    public function sendDailyLimitAlert($limitData)
    {
        $title = "⚠️ Daily Limit Alert";
        $message = "Daily {$limitData['type']} limit reached: {$limitData['used']} / {$limitData['limit']}";
        
        return $this->create(
            'daily_limit_alert',
            $title,
            $message,
            $limitData,
            'warning'
        );
    }
}
