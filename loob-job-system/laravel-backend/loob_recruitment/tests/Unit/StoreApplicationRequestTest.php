<?php

namespace Tests\Unit;

use App\Http\Requests\StoreApplicationRequest;
use App\Models\JobListing;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class StoreApplicationRequestTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        JobListing::create([
            'title' => 'Test Job',
            'location' => 'Test Location',
            'description' => 'Test Description'
        ]);
    }

    public function test_validation_passes_with_valid_data()
    {
        $request = new StoreApplicationRequest();
        $jobListing = JobListing::first();
        
        $data = [
            'full_name' => 'John Doe',
            'phone' => '+1234567890',
            'email' => 'john@example.com',
            'position' => 'Software Developer',
            'work_experience' => 'I have 5 years of experience',
            'job_listing_id' => $jobListing->id
        ];

        $validator = Validator::make($data, $request->rules());
        
        $this->assertTrue($validator->passes());
    }

    public function test_validation_passes_without_email()
    {
        $request = new StoreApplicationRequest();
        $jobListing = JobListing::first();
        
        $data = [
            'full_name' => 'Jane Doe',
            'phone' => '+1987654321',
            'position' => 'Designer',
            'work_experience' => 'I have design experience',
            'job_listing_id' => $jobListing->id
        ];

        $validator = Validator::make($data, $request->rules());
        
        $this->assertTrue($validator->passes());
    }

    public function test_validation_fails_without_required_fields()
    {
        $request = new StoreApplicationRequest();
        
        $data = [];

        $validator = Validator::make($data, $request->rules());
        
        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('full_name', $validator->errors()->toArray());
        $this->assertArrayHasKey('phone', $validator->errors()->toArray());
        $this->assertArrayHasKey('position', $validator->errors()->toArray());
        $this->assertArrayHasKey('work_experience', $validator->errors()->toArray());
        $this->assertArrayHasKey('job_listing_id', $validator->errors()->toArray());
    }

    public function test_authorization_returns_true()
    {
        $request = new StoreApplicationRequest();
        
        $this->assertTrue($request->authorize());
    }
}
