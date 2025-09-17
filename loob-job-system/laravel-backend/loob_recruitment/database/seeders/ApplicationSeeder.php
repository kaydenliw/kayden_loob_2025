<?php

namespace Database\Seeders;

use App\Models\Application;
use App\Models\JobListing;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ApplicationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $positions = [
            'Software Engineer',
            'Product Manager', 
            'UX Designer',
            'Data Scientist',
            'Marketing Manager',
            'Sales Representative'
        ];

        $statuses = ['applied', 'screening', 'interview', 'offer', 'rejected'];

        $names = [
            'Ahmad bin Abdullah', 'Siti Nurhaliza', 'Lim Wei Ming', 'Priya Devi',
            'Muhammad Hafiz', 'Tan Mei Lin', 'Raj Kumar', 'Nur Aisyah',
            'Chen Kai Hong', 'Fatimah binti Hassan', 'Ravi Chandran', 'Lee Xin Yi'
        ];

        $emails = [
            'ahmad.abdullah@email.com', 'siti.nurhaliza@email.com', 'lim.weiming@email.com',
            'priya.devi@email.com', 'muhammad.hafiz@email.com', 'tan.meilin@email.com',
            'raj.kumar@email.com', 'nur.aisyah@email.com', 'chen.kaihong@email.com',
            'fatimah.hassan@email.com', 'ravi.chandran@email.com', 'lee.xinyi@email.com'
        ];

        $coverLetters = [
            'I am excited to apply for this position. With my background in technology and passion for innovation, I believe I would be a great fit for your team.',
            'I am writing to express my strong interest in this role. My experience in the tech industry and skills align perfectly with your requirements.',
            'I am thrilled to submit my application for this opportunity. My proven track record and dedication make me an ideal candidate.',
            'With great enthusiasm, I am applying for this position. I am confident that my expertise will contribute significantly to your organization\'s growth.',
            'I am eager to bring my skills and experience to your team. This role represents the perfect next step in my career journey.'
        ];

        $jobListings = JobListing::all();
        
        if ($jobListings->isEmpty()) {
            $this->command->info('No job listings found. Creating some first...');
            $jobListingSeeder = new JobListingSeeder();
            $jobListingSeeder->run();
            $jobListings = JobListing::all();
        }
        for ($i = 0; $i < 25; $i++) {
            $randomJobListing = $jobListings->random();
            
            Application::create([
                'full_name' => $names[array_rand($names)],
                'email' => $emails[array_rand($emails)],
                'phone' => '+60' . rand(100000000, 199999999),
                'position' => $randomJobListing->title,
                'work_experience' => $coverLetters[array_rand($coverLetters)],
                'status' => $statuses[array_rand($statuses)],
                'job_listing_id' => $randomJobListing->id, // Link to actual job listing
                'created_at' => now()->subDays(rand(0, 30)),
                'updated_at' => now()->subDays(rand(0, 15)),
            ]);
        }
    }
}
