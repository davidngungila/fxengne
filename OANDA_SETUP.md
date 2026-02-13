# OANDA API Integration Setup

This guide explains how to set up OANDA API integration for live market data and trade execution in FxEngne.

## Why OANDA?

✅ **Live market data** - Real-time currency pair prices  
✅ **Direct trade execution** - Execute trades programmatically  
✅ **Well-documented REST API** - Easy to integrate  
✅ **Works very well with Laravel** - Perfect for this project  
✅ **Practice & Live environments** - Test before going live  

## Setup Instructions

### 1. Get OANDA API Credentials

1. **Create an OANDA Account**
   - Visit: https://www.oanda.com/
   - Sign up for a practice account (free) or live account

2. **Get API Token**
   - Log in to your OANDA account
   - Go to: **Manage API Access** (in account settings)
   - Generate a new API token
   - Copy the token (you'll need it)

3. **Get Account ID**
   - Your account ID is displayed in your OANDA dashboard
   - It's usually a number like: `101-004-1234567-001`

### 2. Configure Environment Variables

Add these to your `.env` file:

```env
# OANDA API Configuration
OANDA_API_KEY=your_api_token_here
OANDA_ACCOUNT_ID=your_account_id_here
OANDA_ENVIRONMENT=practice
```

**Options for `OANDA_ENVIRONMENT`:**
- `practice` - Use practice/demo account (recommended for testing)
- `live` - Use live trading account (real money)

### 3. Verify Configuration

After adding the credentials, clear Laravel config cache:

```bash
php artisan config:clear
```

### 4. Test the Integration

Visit the **Live Market** page (`/market-tools/live-market`) to see real-time prices from OANDA.

## API Endpoints

The following API endpoints are available:

### Market Data
- `GET /api/market/prices?instruments=EUR_USD,GBP_USD` - Get live prices
- `GET /api/market/instruments` - Get available instruments
- `GET /api/market/account-summary` - Get account summary

### Trade Execution
- `POST /api/trade/execute` - Execute a market order
- `GET /api/trade/open` - Get open trades
- `POST /api/trade/close/{tradeId}` - Close a trade
- `GET /api/trade/history` - Get trade history

## Example: Execute a Trade

```javascript
// Execute a BUY order
fetch('/api/trade/execute', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
    },
    body: JSON.stringify({
        instrument: 'EURUSD',
        units: 1000,
        side: 'BUY',
        stop_loss: 1.0800,
        take_profit: 1.0900
    })
})
.then(response => response.json())
.then(data => console.log(data));
```

## Service Class Usage

The `OandaService` class provides methods for all OANDA operations:

```php
use App\Services\OandaService;

$oanda = app(OandaService::class);

// Get live prices
$prices = $oanda->getPrices(['EUR_USD', 'GBP_USD']);

// Execute order
$result = $oanda->createOrder('EUR_USD', 1000, 'MARKET', 'BUY', 1.0800, 1.0900);

// Get open trades
$trades = $oanda->getOpenTrades();
```

## Features

### Live Market View
- Real-time price updates from OANDA
- Automatic refresh every second
- Falls back to simulated data if OANDA not configured

### Trade Execution
- Market orders
- Stop loss and take profit
- Trade management (close trades)
- Trade history

### Error Handling
- Automatic fallback to simulated data
- Error logging
- User-friendly error messages

## Security Notes

⚠️ **Important Security Considerations:**

1. **Never commit API keys** to version control
2. **Use environment variables** for all credentials
3. **Practice account first** - Test thoroughly before using live account
4. **Rate limiting** - OANDA has rate limits, be mindful of API calls
5. **Error handling** - Always handle API errors gracefully

## Rate Limits

OANDA API rate limits:
- **Practice**: 20 requests per second
- **Live**: 20 requests per second

The integration includes automatic rate limiting and error handling.

## Troubleshooting

### Prices not updating?
- Check API credentials in `.env`
- Verify account ID is correct
- Check Laravel logs: `storage/logs/laravel.log`
- Ensure `OANDA_ENVIRONMENT` matches your account type

### Trade execution fails?
- Verify account has sufficient margin
- Check instrument name format (EUR_USD not EURUSD)
- Review error messages in response
- Check account permissions

### API errors?
- Verify API token is valid
- Check account ID matches your account
- Ensure environment (practice/live) matches account type
- Review OANDA API documentation for specific error codes

## Documentation

- **OANDA API Docs**: https://developer.oanda.com/rest-live-v20/introduction/
- **REST API Reference**: https://developer.oanda.com/rest-live-v20/account-ep/

## Support

For OANDA-specific issues:
- OANDA Support: https://www.oanda.com/contact/
- API Documentation: https://developer.oanda.com/

For FxEngne integration issues:
- Check Laravel logs
- Review error messages
- Verify configuration




