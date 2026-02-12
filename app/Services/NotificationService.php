<?php

namespace App\Services;

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
        $message = $this->formatTradeMessage($tradeData);
        
        $this->send($title, $tradeData);
    }

    /**
     * Format trade message
     */
    protected function formatTradeMessage($tradeData)
    {
        $message = "<b>✅ Trade Executed</b>\n\n";
        $message .= "📊 <b>Instrument:</b> {$tradeData['instrument']}\n";
        $message .= "📈 <b>Direction:</b> {$tradeData['direction']}\n";
        $message .= "💰 <b>Units:</b> {$tradeData['units']}\n";
        $message .= "💵 <b>Entry Price:</b> {$tradeData['entry_price']}\n";
        $message .= "🛑 <b>Stop Loss:</b> {$tradeData['stop_loss']}\n";
        $message .= "🎯 <b>Take Profit:</b> {$tradeData['take_profit']}\n";
        $message .= "📊 <b>Strategy:</b> {$tradeData['strategy']}\n";
        
        return $message;
    }
}

