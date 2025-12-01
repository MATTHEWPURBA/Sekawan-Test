<?php
/**
 * Database Connection Test Script
 * 
 * Run this script to test your database connection:
 * php test-db-connection.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "Testing database connection...\n";
echo "Host: " . env('DB_HOST') . "\n";
echo "Database: " . env('DB_DATABASE') . "\n";
echo "Username: " . env('DB_USERNAME') . "\n";
echo "SSL Mode: " . env('DB_SSLMODE') . "\n\n";

try {
    $start = microtime(true);
    DB::connection()->getPdo();
    $time = round((microtime(true) - $start) * 1000, 2);
    
    echo "✅ Connection successful! (took {$time}ms)\n";
    
    // Test a simple query
    $result = DB::select('SELECT version()');
    echo "✅ Query test successful!\n";
    echo "PostgreSQL version: " . $result[0]->version . "\n";
    
} catch (\Exception $e) {
    echo "❌ Connection failed!\n";
    echo "Error: " . $e->getMessage() . "\n";
    echo "Error Code: " . $e->getCode() . "\n";
    
    if (strpos($e->getMessage(), 'timeout') !== false) {
        echo "\n💡 Troubleshooting tips:\n";
        echo "1. Check if your Neon database is active (not paused)\n";
        echo "2. Verify your database credentials in .env\n";
        echo "3. Check your network/firewall settings\n";
        echo "4. Try using the direct connection URL instead of pooler\n";
    }
    
    exit(1);
}
