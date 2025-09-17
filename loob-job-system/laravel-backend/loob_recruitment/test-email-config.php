<?php

/**
 * Email Testing Configuration Script
 * 
 * This script helps you test email functionality with different drivers
 * Run: php test-email-config.php
 */

require_once __DIR__ . '/vendor/autoload.php';

use App\Models\Application;
use App\Mail\ApplicationConfirmation;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Application as LaravelApp;

// Bootstrap Laravel
$app = new LaravelApp(
    $_ENV['APP_BASE_PATH'] ?? dirname(__DIR__)
);

$app->singleton(
    Illuminate\Contracts\Http\Kernel::class,
    App\Http\Kernel::class
);

$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    App\Console\Kernel::class
);

$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    App\Exceptions\Handler::class
);

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

echo "🧪 Email Testing Configuration\n";
echo "==============================\n\n";

// Test different mail configurations
$configurations = [
    'log' => [
        'description' => 'Log Driver (emails saved to storage/logs/laravel.log)',
        'config' => ['default' => 'log']
    ],
    'array' => [
        'description' => 'Array Driver (emails stored in memory for testing)',
        'config' => ['default' => 'array']
    ],
    'mailtrap' => [
        'description' => 'Mailtrap SMTP (safe email testing service)',
        'config' => [
            'default' => 'smtp',
            'mailers.smtp.host' => 'sandbox.smtp.mailtrap.io',
            'mailers.smtp.port' => 2525,
            'mailers.smtp.username' => 'YOUR_MAILTRAP_USERNAME',
            'mailers.smtp.password' => 'YOUR_MAILTRAP_PASSWORD',
        ]
    ]
];

foreach ($configurations as $driver => $info) {
    echo "📧 {$driver}: {$info['description']}\n";
}

echo "\n" . str_repeat("=", 50) . "\n";
echo "To test emails:\n";
echo "1. Update your .env file with one of the configurations above\n";
echo "2. Run: php artisan config:clear\n";
echo "3. Submit a test application through your Flutter app\n";
echo "4. Check the appropriate location for the email\n\n";

echo "📱 Flutter App Testing Steps:\n";
echo "1. Start your Laravel server: php artisan serve\n";
echo "2. Start your Flutter app\n";
echo "3. Submit an application WITH an email address\n";
echo "4. Check for confirmation email based on your mail driver\n\n";
