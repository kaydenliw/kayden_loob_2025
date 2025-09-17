<?php

/**
 * Email Functionality Testing Script
 * 
 * This script tests the email confirmation functionality
 * Run: php artisan tinker --execute="require 'test-email-functionality.php';"
 */

use App\Models\Application;
use App\Models\JobListing;
use App\Mail\ApplicationConfirmation;
use Illuminate\Support\Facades\Mail;

echo "🧪 Testing Email Confirmation Functionality\n";
echo "==========================================\n\n";

// Test 1: Check if mail configuration is working
echo "📋 Test 1: Mail Configuration Check\n";
echo "Current mail driver: " . config('mail.default') . "\n";
echo "Mail from address: " . config('mail.from.address') . "\n";
echo "Mail from name: " . config('mail.from.name') . "\n\n";

// Test 2: Create a test application with email
echo "📋 Test 2: Create Test Application with Email\n";

// First, ensure we have a job listing
$jobListing = JobListing::first();
if (!$jobListing) {
    echo "❌ No job listings found. Please run seeders first.\n";
    echo "Run: php artisan db:seed --class=JobListingSeeder\n\n";
    return;
}

// Create test application data
$testApplicationData = [
    'job_listing_id' => $jobListing->id,
    'full_name' => 'Test User',
    'email' => 'test@example.com',
    'phone_number' => '+1234567890',
    'position' => $jobListing->title,
    'experience_level' => 'mid',
    'availability' => 'immediate',
    'cover_letter' => 'This is a test application for email functionality.',
    'resume_text' => 'Test resume content...',
    'skills' => json_encode(['PHP', 'Laravel', 'Flutter']),
    'education' => json_encode([
        'degree' => 'Bachelor of Science',
        'field' => 'Computer Science',
        'school' => 'Test University',
        'graduation_year' => '2020'
    ]),
    'work_experience' => json_encode([
        [
            'company' => 'Test Company',
            'position' => 'Developer',
            'duration' => '2 years',
            'description' => 'Test work experience'
        ]
    ])
];

try {
    // Create the application
    $application = Application::create($testApplicationData);
    echo "✅ Test application created with ID: {$application->id}\n";
    
    // Test 3: Send confirmation email
    echo "📋 Test 3: Send Confirmation Email\n";
    
    if ($application->email) {
        // Use Mail facade to send email
        Mail::to($application->email)->send(new ApplicationConfirmation($application));
        echo "✅ Confirmation email sent to: {$application->email}\n";
        
        // Check based on mail driver
        $mailDriver = config('mail.default');
        switch ($mailDriver) {
            case 'log':
                echo "📁 Check email content in: " . storage_path('logs/laravel.log') . "\n";
                break;
            case 'array':
                echo "💾 Email stored in memory (check Mail::fake() assertions)\n";
                break;
            case 'smtp':
                echo "📧 Check your SMTP service (Mailtrap, Gmail, etc.)\n";
                break;
        }
    } else {
        echo "❌ No email address provided in application\n";
    }
    
    echo "\n📋 Test 4: Verify Email Content\n";
    
    // Create a mailable instance to test content
    $mailable = new ApplicationConfirmation($application);
    
    echo "Email Subject: " . $mailable->envelope()->subject . "\n";
    echo "Email View: emails.application-confirmation\n";
    echo "Application Data Available in Email:\n";
    echo "  - Full Name: {$application->full_name}\n";
    echo "  - Position: {$application->position}\n";
    echo "  - Application ID: {$application->id}\n";
    echo "  - Created At: {$application->created_at->format('F j, Y \\a\\t g:i A')}\n\n";
    
    echo "✅ All tests completed successfully!\n\n";
    
    // Clean up test data
    echo "🧹 Cleaning up test data...\n";
    $application->delete();
    echo "✅ Test application deleted\n\n";
    
} catch (Exception $e) {
    echo "❌ Error during testing: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}

echo "🎯 Next Steps for Flutter App Testing:\n";
echo "1. Start Laravel server: php artisan serve\n";
echo "2. Open Flutter app and navigate to job applications\n";
echo "3. Fill out application form with a valid email address\n";
echo "4. Submit the application\n";
echo "5. Check for email confirmation based on your mail driver\n";
echo "6. Verify the Flutter app shows 'confirmation email sent' message\n\n";

echo "🔧 Mail Driver Options:\n";
echo "- log: Check storage/logs/laravel.log\n";
echo "- array: Use Mail::fake() in tests\n";
echo "- smtp: Configure with Mailtrap or real SMTP\n\n";
