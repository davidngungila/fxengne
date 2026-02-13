# 🤖 Automated Trading System

Complete automated trading system with live market data, multiple strategies, signal detection, position sizing, risk management, trade execution, notifications, and performance tracking.

## 🎯 Features

### ✅ Core Capabilities

1. **📊 Pull Live Market Data**
   - Real-time price updates via OANDA API
   - Multiple instruments monitoring
   - WebSocket support ready

2. **🧠 Run Multiple Strategies Simultaneously**
   - EMA Crossover Strategy
   - RSI Oversold/Overbought Strategy
   - MACD Crossover Strategy
   - Support/Resistance Strategy
   - Bollinger Bands Strategy
   - Moving Average Convergence Strategy

3. **🎯 Detect Valid Entry Signals**
   - Confidence-based filtering
   - Cooldown periods to prevent overtrading
   - Spread validation
   - Signal validation engine

4. **📈 Calculate Position Size Automatically**
   - Risk-based position sizing (2% default)
   - Account balance consideration
   - Confidence adjustment
   - Volatility-based adjustments
   - Instrument-specific multipliers

5. **🛑 Set Stop Loss & Take Profit**
   - Automatic SL/TP calculation
   - Risk/reward ratio (2:1 default)
   - Volatility-based adjustments
   - Signal confidence consideration

6. **🤖 Execute Trade via Broker API**
   - OANDA API integration
   - Market order execution
   - Automatic SL/TP placement
   - Trade confirmation

7. **📢 Send Notification (Telegram / Email)**
   - Telegram bot integration
   - Email notifications
   - Trade execution alerts
   - Error notifications
   - Bot status updates

8. **📊 Log Performance & Analytics**
   - Trade logging
   - Performance metrics
   - Win rate tracking
   - Profit/loss analysis
   - Strategy performance

## 🚀 Quick Start

### 1. Configure Environment Variables

Add to `.env`:

```env
# OANDA API (Required)
OANDA_API_KEY=your_api_key
OANDA_ACCOUNT_ID=your_account_id
OANDA_ENVIRONMENT=practice

# Telegram Notifications (Optional)
TELEGRAM_BOT_TOKEN=your_bot_token
TELEGRAM_CHAT_ID=your_chat_id

# Email Notifications (Optional)
NOTIFICATIONS_EMAIL_ENABLED=true
NOTIFICATIONS_EMAIL_ADDRESS=your@email.com
```

### 2. Start the Trading Bot

**Via Command Line:**
```bash
php artisan trading:bot --interval=30
```

**Via API:**
```bash
POST /api/bot/start
{
    "strategies": ["ema_crossover", "rsi_oversold_overbought"],
    "config": {
        "risk_per_trade": 0.02
    }
}
```

**Check Status:**
```bash
GET /api/bot/status
```

**Stop Bot:**
```bash
POST /api/bot/stop
```

## 📋 Available Strategies

### 1. EMA Crossover (`ema_crossover`)
- **Buy Signal**: EMA 9 crosses above EMA 21
- **Sell Signal**: EMA 9 crosses below EMA 21
- **Confidence**: 0.7

### 2. RSI Strategy (`rsi_oversold_overbought`)
- **Buy Signal**: RSI < 30 (oversold)
- **Sell Signal**: RSI > 70 (overbought)
- **Confidence**: 0.6-0.8 (based on RSI level)

### 3. MACD Crossover (`macd_crossover`)
- **Buy Signal**: MACD crosses above signal line
- **Sell Signal**: MACD crosses below signal line
- **Confidence**: 0.75

### 4. Support/Resistance (`support_resistance`)
- **Buy Signal**: Price near support level
- **Sell Signal**: Price near resistance level
- **Confidence**: 0.65

### 5. Bollinger Bands (`bollinger_bands`)
- **Buy Signal**: Price touches lower band
- **Sell Signal**: Price touches upper band
- **Confidence**: 0.7

### 6. Moving Average Convergence (`moving_average_convergence`)
- **Buy Signal**: Golden Cross (EMA 50 > EMA 200)
- **Sell Signal**: Death Cross (EMA 50 < EMA 200)
- **Confidence**: 0.8

## ⚙️ Configuration

### Risk Management

```php
// Position Sizing
- Risk per trade: 2% (default)
- Max position size: 10,000 units
- Min position size: 100 units

// Stop Loss & Take Profit
- Default SL: 1% of entry price
- Default TP: 2% of entry price
- Risk/Reward: 2:1
```

### Signal Detection

```php
// Signal Validation
- Minimum confidence: 0.6 (60%)
- Max signals per instrument: 1
- Cooldown period: 5 minutes
- Spread validation: Max 0.1%
```

## 📊 Performance Tracking

### Metrics Tracked

- Total Trades
- Winning Trades
- Losing Trades
- Win Rate (%)
- Total Profit
- Average Profit
- Largest Win
- Largest Loss
- Profit Factor

### Access Performance Data

```php
$performanceTracker = app(\App\Services\PerformanceTracker::class);
$summary = $performanceTracker->getSummary();
```

## 🔔 Notifications

### Telegram Setup

1. Create a bot via [@BotFather](https://t.me/botfather)
2. Get your bot token
3. Get your chat ID (send message to bot, then visit: `https://api.telegram.org/bot<TOKEN>/getUpdates`)
4. Add to `.env`:
   ```env
   TELEGRAM_BOT_TOKEN=your_token
   TELEGRAM_CHAT_ID=your_chat_id
   ```

### Email Setup

1. Configure Laravel mail settings
2. Add to `.env`:
   ```env
   NOTIFICATIONS_EMAIL_ENABLED=true
   NOTIFICATIONS_EMAIL_ADDRESS=your@email.com
   ```

## 🛠️ API Endpoints

### Bot Control

- `GET /api/bot/status` - Get bot status
- `POST /api/bot/start` - Start bot
- `POST /api/bot/stop` - Stop bot
- `POST /api/bot/execute-cycle` - Execute one cycle manually

### Request Examples

**Start Bot:**
```json
POST /api/bot/start
{
    "strategies": ["ema_crossover", "rsi_oversold_overbought"],
    "config": {
        "risk_per_trade": 0.02
    }
}
```

**Response:**
```json
{
    "success": true,
    "message": "Trading bot started successfully",
    "data": {
        "running": true,
        "active_strategies": 2,
        "strategies": ["ema_crossover", "rsi_oversold_overbought"],
        "performance": {
            "total_trades": 0,
            "win_rate": 0
        }
    }
}
```

## 🔄 Trading Cycle

1. **Pull Market Data** - Fetch live prices for monitored instruments
2. **Run Strategies** - Execute all active strategies simultaneously
3. **Detect Signals** - Validate and filter trading signals
4. **Calculate Position** - Determine optimal position size
5. **Calculate Risk** - Set stop loss and take profit
6. **Execute Trade** - Place order via OANDA API
7. **Send Notification** - Alert via Telegram/Email
8. **Log Performance** - Record trade and update metrics

## 📈 Monitoring

### View Performance

```php
$bot = app(\App\Services\TradingBotService::class);
$status = $bot->getStatus();

// Returns:
// - Running status
// - Active strategies
// - Performance metrics
// - Last update time
```

### Logs

All trading activity is logged to Laravel logs:
```bash
tail -f storage/logs/laravel.log
```

## ⚠️ Important Notes

1. **Practice Account First**: Always test with practice account before live trading
2. **Risk Management**: Never risk more than 2% per trade
3. **Monitor Regularly**: Check bot status and performance regularly
4. **Set Limits**: Configure daily loss limits and maximum exposure
5. **Backtest First**: Test strategies with historical data before live trading

## 🔒 Security

- API keys stored in environment variables
- CSRF protection on all API routes
- Input validation on all endpoints
- Error logging without exposing sensitive data
- Rate limiting on API calls

## 📚 Files Structure

```
app/Services/
├── TradingBotService.php      # Main bot orchestrator
├── StrategyEngine.php          # Strategy execution engine
├── SignalDetector.php          # Signal validation
├── PositionSizer.php           # Position size calculation
├── RiskManager.php             # Risk management (SL/TP)
├── NotificationService.php     # Telegram/Email notifications
└── PerformanceTracker.php      # Performance analytics

app/Http/Controllers/
└── AutomatedTradingController.php  # API endpoints

app/Console/Commands/
└── RunTradingBot.php           # CLI command to run bot
```

## 🚀 Next Steps

1. **Backtest Strategies**: Test strategies with historical data
2. **Optimize Parameters**: Fine-tune strategy parameters
3. **Add More Strategies**: Implement additional trading strategies
4. **WebSocket Integration**: Real-time price updates
5. **Dashboard**: Create web interface for monitoring
6. **Alerts**: Set up custom alert conditions

## 📞 Support

For issues or questions:
- Check Laravel logs: `storage/logs/laravel.log`
- Review OANDA API documentation
- Test with practice account first




