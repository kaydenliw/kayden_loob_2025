<?php

namespace Tests\Unit;

use App\Models\Application;
use App\Models\JobListing;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApplicationModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_application_has_correct_fillable_fields()
    {
        $application = new Application();
        
        $expectedFillable = [
            'full_name',
            'phone', 
            'email',
            'position',
            'work_experience',
            'status',
            'job_listing_id'
        ];

        $this->assertEquals($expectedFillable, $application->getFillable());
    }

    public function test_application_has_default_status()
    {
        $jobListing = JobListing::create([
            'title' => 'Software Developer - Loob Holding',
            'location' => 'Kuala Lumpur, Malaysia',
            'description' => 'Peluang kerjaya sebagai pembangun perisian di syarikat teknologi terkemuka Malaysia'
        ]);

        $application = Application::create([
            'full_name' => 'Aminah binti Ismail',
            'phone' => '+60123456789',
            'position' => 'Software Developer',
            'work_experience' => 'Fresh graduate dari Universiti Malaya dengan pengalaman internship di syarikat teknologi.',
            'job_listing_id' => $jobListing->id
        ]);

        $this->assertEquals('applied', $application->fresh()->status);
    }

    public function test_application_accessor_methods()
    {
        $jobListing = JobListing::create([
            'title' => 'Full Stack Developer - Loob Holding',
            'location' => 'Petaling Jaya, Selangor',
            'description' => 'Jawatan untuk pembangun full stack yang berpengalaman dalam React dan Laravel'
        ]);

        $application = Application::create([
            'full_name' => 'Chen Wei Loon',
            'phone' => '+60187654321',
            'email' => 'chenweiloon@gmail.com',
            'position' => 'Full Stack Developer',
            'work_experience' => 'Pengalaman 3 tahun sebagai full stack developer di syarikat startup Malaysia.',
            'job_listing_id' => $jobListing->id
        ]);

        $this->assertEquals('Chen Wei Loon', $application->applicant_name);
        $this->assertEquals('chenweiloon@gmail.com', $application->applicant_email);
        $this->assertEquals('Pengalaman 3 tahun sebagai full stack developer di syarikat startup Malaysia.', $application->cover_letter);
    }

    public function test_application_belongs_to_job_listing()
    {
        $jobListing = JobListing::create([
            'title' => 'Backend Developer - Loob Holding',
            'location' => 'Cyberjaya, Selangor',
            'description' => 'Jawatan untuk pembangun backend yang mahir dalam Laravel dan database optimization'
        ]);

        $application = Application::create([
            'full_name' => 'Rajesh Kumar a/l Subramaniam',
            'phone' => '+60198765432',
            'position' => 'Backend Developer',
            'work_experience' => 'Pengalaman 2 tahun dalam pembangunan API menggunakan Laravel dan Node.js.',
            'job_listing_id' => $jobListing->id
        ]);

        $this->assertInstanceOf(JobListing::class, $application->jobListing);
        $this->assertEquals($jobListing->id, $application->jobListing->id);
    }
}