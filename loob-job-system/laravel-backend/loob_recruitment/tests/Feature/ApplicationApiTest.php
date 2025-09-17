<?php

namespace Tests\Feature;

use App\Models\Application;
use App\Models\JobListing;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ApplicationApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        JobListing::create([
            'title' => 'Software Developer',
            'location' => 'Kuala Lumpur, Malaysia',
            'description' => 'Test job description'
        ]);
    }

    public function test_can_submit_application_with_email()
    {
        Mail::fake();
        
        $jobListing = JobListing::first();
        
        $applicationData = [
            'full_name' => 'Ahmad Rahman bin Abdullah',
            'phone' => '+60123456789',
            'email' => 'ahmad.rahman@gmail.com',
            'position' => 'Software Developer',
            'work_experience' => 'Saya mempunyai 5 tahun pengalaman dalam pembangunan perisian dan aplikasi web untuk syarikat-syarikat di Malaysia.',
            'job_listing_id' => $jobListing->id
        ];

        $response = $this->postJson('/api/applications', $applicationData);

        $response->assertStatus(201)
                ->assertJson([
                    'success' => true,
                    'message' => 'Application submitted successfully'
                ])
                ->assertJsonStructure([
                    'application_id'
                ]);

        $this->assertDatabaseHas('applications', [
            'full_name' => 'Ahmad Rahman bin Abdullah',
            'email' => 'ahmad.rahman@gmail.com',
            'status' => 'applied'
        ]);

        Mail::assertSent(\App\Mail\ApplicationConfirmation::class);
    }

    public function test_can_submit_application_without_email()
    {
        Mail::fake();
        
        $jobListing = JobListing::first();
        
        $applicationData = [
            'full_name' => 'Siti Nurhaliza binti Mohd Ali',
            'phone' => '+60187654321',
            'position' => 'Software Developer',
            'work_experience' => 'Saya mempunyai 3 tahun pengalaman dalam pembangunan web dan e-commerce untuk syarikat tempatan.',
            'job_listing_id' => $jobListing->id
        ];

        $response = $this->postJson('/api/applications', $applicationData);

        $response->assertStatus(201)
                ->assertJson([
                    'success' => true,
                    'message' => 'Application submitted successfully'
                ]);

        $this->assertDatabaseHas('applications', [
            'full_name' => 'Siti Nurhaliza binti Mohd Ali',
            'phone' => '+60187654321',
            'email' => null
        ]);

        Mail::assertNothingSent();
    }

    public function test_application_validation_fails_with_missing_required_fields()
    {
        $response = $this->postJson('/api/applications', []);

        $response->assertStatus(422)
                ->assertJsonValidationErrors([
                    'full_name',
                    'phone', 
                    'position',
                    'work_experience',
                    'job_listing_id'
                ]);
    }

    public function test_can_check_application_status_by_phone()
    {
        $application = Application::create([
            'full_name' => 'Lim Wei Ming',
            'phone' => '+60123456789',
            'position' => 'Software Developer',
            'work_experience' => 'Pengalaman 2 tahun sebagai developer di syarikat fintech Malaysia.',
            'status' => 'screening',
            'job_listing_id' => JobListing::first()->id
        ]);

        $response = $this->getJson('/api/applications/status?phone=' . urlencode('+60123456789'));

        $response->assertStatus(200)
                ->assertJson([
                    'success' => true,
                    'status' => 'screening',
                    'application_id' => $application->id,
                    'position' => 'Software Developer'
                ]);
    }

    public function test_can_fetch_all_applications()
    {
        Application::create([
            'full_name' => 'Raj Kumar a/l Subramaniam',
            'phone' => '+60198765432',
            'position' => 'Backend Developer',
            'work_experience' => 'Pengalaman 4 tahun dalam pembangunan API dan sistem database untuk syarikat e-commerce Malaysia.',
            'job_listing_id' => JobListing::first()->id
        ]);

        Application::create([
            'full_name' => 'Nurul Aina binti Hassan',
            'phone' => '+60176543210',
            'email' => 'nurul.aina@hotmail.com',
            'position' => 'UI/UX Designer',
            'work_experience' => 'Fresh graduate dengan portfolio projek design untuk startup Malaysia.',
            'job_listing_id' => JobListing::first()->id
        ]);

        $response = $this->getJson('/api/applications');

        $response->assertStatus(200)
                ->assertJson([
                    'success' => true
                ])
                ->assertJsonStructure([
                    'data' => [
                        '*' => [
                            'id',
                            'full_name',
                            'phone',
                            'email',
                            'position',
                            'work_experience',
                            'status',
                            'created_at',
                            'updated_at'
                        ]
                    ]
                ]);

        $this->assertCount(2, $response->json('data'));
    }
}
