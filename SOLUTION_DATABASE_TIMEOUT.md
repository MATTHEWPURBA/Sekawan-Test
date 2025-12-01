# Solution for Database Timeout Error

## ✅ Issues Fixed

1. **Database Connection Timeout** - Added timeout configurations
2. **Xdebug Warning** - Instructions to fix (optional)

## Changes Made

### 1. Database Configuration (`config/database.php`)
- Added `connect_timeout` setting (10 seconds)
- Added `PDO::ATTR_TIMEOUT` option (10 seconds)
- Added `PDO::ATTR_PERSISTENT => false` to prevent connection pooling issues

### 2. Environment Variables (`.env`)
- Added `DB_CONNECT_TIMEOUT=10`
- Added `DB_TIMEOUT=10`

### 3. PHP Timeout Settings (`public/index.php`)
- Added `set_time_limit(60)` to increase execution time
- Added `ini_set('default_socket_timeout', 30)` for socket connections

## Test Results

✅ Database connection is now working successfully!
- Connection time: ~3.7 seconds
- PostgreSQL version confirmed: 17.6

## Fix Xdebug Warning (Optional)

The xdebug warning doesn't affect functionality but can be fixed:

### Option 1: Comment out duplicate xdebug line
Edit `/opt/homebrew/etc/php/8.4/php.ini`:
```ini
; zend_extension="xdebug.so"  ; Comment this line (line 1)
```

Keep the correct configuration at line 1853:
```ini
zend_extension="/opt/homebrew/Cellar/php/8.4.5_1/pecl/20240924/xdebug.so"
```

### Option 2: Install xdebug properly
```bash
pecl install xdebug
```

### Option 3: Disable xdebug (if not needed)
Comment out both xdebug lines in php.ini if you don't need debugging.

## Testing

Run the test script to verify connection:
```bash
php test-db-connection.php
```

## Additional Troubleshooting

If you still experience timeout issues:

1. **Check Neon Database Status**
   - Log into your Neon dashboard
   - Ensure the database is not paused
   - Neon pauses inactive databases on free tier

2. **Use Direct Connection (instead of pooler)**
   - In your Neon dashboard, get the direct connection string
   - Update `DB_HOST` in `.env` with the direct connection URL

3. **Increase Timeout Values**
   - Increase `DB_CONNECT_TIMEOUT` and `DB_TIMEOUT` in `.env` if needed
   - Increase `set_time_limit()` value in `public/index.php`

4. **Check Network/Firewall**
   - Ensure port 5432 is not blocked
   - Check if VPN or firewall is interfering

## Notes

- The connection test shows it's working, but initial connections to Neon can take 3-5 seconds
- This is normal for cloud databases, especially on the first connection
- Subsequent connections should be faster due to connection pooling
