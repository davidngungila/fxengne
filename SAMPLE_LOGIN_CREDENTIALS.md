# Sample Login Credentials

## Admin Account
- **Email:** admin@fxengne.com
- **Password:** admin123
- **Role:** Admin
- **Access:** Full system access including user management

## Trader Accounts

### Primary Trader
- **Email:** trader@fxengne.com
- **Password:** trader123
- **Role:** Trader
- **Access:** Trading features, strategies, analytics, journal

### Additional Traders
- **Email:** john@fxengne.com
- **Password:** password123
- **Role:** Trader

- **Email:** jane@fxengne.com
- **Password:** password123
- **Role:** Trader

## How to Seed Database

Run the following command to create sample users:

```bash
php artisan db:seed --class=UserSeeder
```

Or run all seeders:

```bash
php artisan db:seed
```

## Security Note

⚠️ **IMPORTANT:** These are sample credentials for development/testing purposes only. 
Change all passwords in production environments!



