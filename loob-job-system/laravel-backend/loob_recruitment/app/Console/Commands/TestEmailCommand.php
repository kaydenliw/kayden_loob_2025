<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Application;
use App\Models\JobListing;
use App\Mail\ApplicationConfirmation;
use Illuminate\Support\Facades\Mail;

class TestEmailCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:email {email? : Email address to send test to}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test email confirmation functionality';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🧪 Testing Email Confirmation Functionality');
        $this->info('==========================================');

        $email = $this->argument('email') ?? $this->ask('Enter email address to test with', 'test@example.com');

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->error('❌ Invalid email format');
            return 1;
        }

        $this->info("\n📋 Current Mail Configuration:");
        $this->line("Driver: " . config('mail.default'));
        $this->line("From: " . config('mail.from.address'));

        $jobListing = JobListing::first();
        if (!$jobListing) {
            $this->error('❌ No job listings found. Run: php artisan db:seed --class=JobListingSeeder');
            return 1;
        }

        $this->info("\n📋 Creating Test Application...");
        
        $application = Application::create([
            'job_listing_id' => $jobListing->id,
            'full_name' => 'Test User (Email Test)',
            'email' => $email,
            'phone' => '+60123456789',
            'position' => $jobListing->title,
            'work_experience' => json_encode([
                [
                    'company' => 'Test Company',
                    'position' => 'Test Engineer',
                    'duration' => '1 year',
                    'description' => 'Testing email functionality'
                ]
            ])
        ]);

        $this->info("✅ Test application created with ID: {$application->id}");

        $this->info("\n📧 Sending Confirmation Email...");
        
        try {
            Mail::to($application->email)->send(new ApplicationConfirmation($application));
            $this->info("✅ Email sent successfully to: {$application->email}");

            $mailDriver = config('mail.default');
            $this->info("\n📍 Check your email here:");
            
            switch ($mailDriver) {
                case 'log':
                    $logFile = storage_path('logs/laravel.log');
                    $this->line("📁 Log file: {$logFile}");
                    $this->line("💡 Tip: tail -f {$logFile} | grep -A 20 'ApplicationConfirmation'");
                    break;
                    
                case 'array':
                    $this->line("💾 Email stored in memory (for testing only)");
                    break;
                    
                case 'smtp':
                    $this->line("📧 Check your SMTP service inbox");
                    if (str_contains(config('mail.mailers.smtp.host', ''), 'mailtrap')) {
                        $this->line("🪤 Mailtrap: https://mailtrap.io/inboxes");
                    }
                    break;
                    
                default:
                    $this->line("📬 Check your configured mail service");
            }

            $this->info("\n📄 Email Content Preview:");
            $mailable = new ApplicationConfirmation($application);
            $this->line("Subject: " . $mailable->envelope()->subject);
            $this->line("To: {$application->email}");
            $this->line("Application ID: {$application->id}");
            $this->line("Position: {$application->position}");

        } catch (\Exception $e) {
            $this->error("❌ Failed to send email: " . $e->getMessage());
            return 1;
        }

        if ($this->confirm("\n🧹 Delete test application data?", true)) {
            $application->delete();
            $this->info("✅ Test application deleted");
        } else {
            $this->info("📝 Test application kept (ID: {$application->id})");
        }

        $this->info("\n🎯 Next Steps:");
        $this->line("1. Check your email/logs for the confirmation");
        $this->line("2. Test with Flutter app: Submit application with email");
        $this->line("3. Verify Flutter shows 'confirmation email sent' message");

        return 0;
    }
}
