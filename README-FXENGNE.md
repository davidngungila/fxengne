# FxEngne - Personal Trading System

A comprehensive personal trading system built with Laravel 12, Tailwind CSS 4, and Chart.js.

## 🚀 Features

### Core Modules

1. **Dashboard** - Account overview, performance summary, equity curves
2. **Trading** - Open trades, trade history, manual trade entry
3. **Strategies** - Create and manage trading strategies
4. **Risk Management** - Risk settings, daily limits, drawdown protection
5. **Analytics** - Performance reports, pair analysis, risk metrics
6. **Trading Journal** - Trade notes, mistake tracking, weekly reviews
7. **Signals** - Active signals, signal history, alerts
8. **Backtesting** - Historical simulation, strategy comparison
9. **Broker & Execution** - Broker connection, API settings, execution logs
10. **Market Tools** - Economic calendar, spread monitor, trading sessions
11. **Profile** - Account settings, security, notifications

## 📦 Installation

1. **Install PHP dependencies:**
   ```bash
   composer install
   ```

2. **Install NPM dependencies:**
   ```bash
   npm install
   ```

3. **Environment Setup:**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Build assets:**
   ```bash
   npm run build
   ```

   Or for development:
   ```bash
   npm run dev
   ```

5. **Run migrations (when database is set up):**
   ```bash
   php artisan migrate
   ```

## 🎨 Structure

### Sidebar Navigation

The sidebar includes 11 main sections:
- 🏠 Dashboard
- 📊 Trading
- 🧠 Strategies
- ⚖️ Risk Management
- 📈 Analytics
- 📒 Trading Journal
- 🔔 Signals
- 🔄 Backtesting
- ⚙️ Broker & Execution
- 📰 Market Tools
- 👤 Profile

### Routes

All routes are defined in `routes/web.php` with proper prefixes and naming conventions.

### Controllers

Controllers are located in `app/Http/Controllers/`:
- `DashboardController`
- `TradingController`
- `StrategyController`
- `RiskManagementController`
- `AnalyticsController`
- `JournalController`
- `SignalController`
- `BacktestingController`
- `BrokerController`
- `MarketToolsController`
- `ProfileController`

### Views

Views are organized in `resources/views/`:
- `layouts/app.blade.php` - Main layout with sidebar
- `dashboard/` - Dashboard views
- `trading/` - Trading views
- `strategies/` - Strategy views
- `risk/` - Risk management views
- `analytics/` - Analytics views
- `journal/` - Journal views
- `signals/` - Signal views
- `backtesting/` - Backtesting views
- `broker/` - Broker views
- `market-tools/` - Market tools views
- `profile/` - Profile views

## 🎯 Next Steps

1. **Database Setup:**
   - Create migrations for accounts, trades, strategies, etc.
   - Set up models and relationships

2. **Authentication:**
   - Implement Laravel authentication
   - Add middleware for protected routes

3. **Broker Integration:**
   - Connect to MT5/MT4 API or broker API
   - Implement trade execution logic

4. **Real-time Updates:**
   - Set up WebSockets or polling for live data
   - Implement real-time chart updates

5. **Backend Logic:**
   - Implement strategy engine
   - Add risk management calculations
   - Create backtesting engine

## 🛠️ Technologies Used

- **Laravel 12** - PHP framework
- **Tailwind CSS 4** - Utility-first CSS framework
- **Chart.js** - Chart library for visualizations
- **Vite** - Build tool
- **Lucide Icons** - Icon library (via SVG)

## 📝 Notes

- The system is designed for personal use
- Focus on risk management and discipline tracking
- All views are responsive and support dark mode
- Sidebar is collapsible on mobile devices

## 🔒 Security

- Implement authentication before going live
- Use environment variables for sensitive data
- Enable CSRF protection (already included)
- Add rate limiting for API endpoints

## 📄 License

Personal use only.


