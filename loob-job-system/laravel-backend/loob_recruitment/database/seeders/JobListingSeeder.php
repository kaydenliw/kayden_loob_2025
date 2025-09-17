<?php

namespace Database\Seeders;

use App\Models\JobListing;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JobListingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jobs = [
            [
                'title' => 'Barista - Tealive',
                'location' => 'Kuala Lumpur, Malaysia',
                'description' => 'Join Loob Holding\'s flagship brand Tealive as a Barista! We are looking for enthusiastic individuals to prepare and serve premium beverages while delivering exceptional customer service. Experience our fast-paced F&B environment across 1,000+ outlets. No prior experience required - we provide comprehensive training. Competitive salary with performance incentives.',
            ],
            [
                'title' => 'Area Manager - F&B Operations',
                'location' => 'Kota Damansara, Selangor',
                'description' => 'Loob Holding is seeking an experienced Area Manager to oversee multiple F&B outlets across Selangor. Lead a team of store managers, ensure operational excellence, and drive sales growth for our 9 F&B brands. Minimum 3 years supervisory experience in retail or F&B industry. Company car and comprehensive benefits package included.',
            ],
            [
                'title' => 'Digital Marketing Executive',
                'location' => 'Kota Damansara, Selangor',
                'description' => 'Drive digital marketing initiatives for Loob Holding\'s portfolio of F&B brands serving 5 million+ consumers monthly. Manage social media campaigns, content creation, and online advertising across Malaysia, Singapore, and other markets. Experience with Facebook Ads, Google Ads, and social media management required.',
            ],
            [
                'title' => 'Store Manager - Tealive',
                'location' => 'Various Locations - Malaysia',
                'description' => 'Lead a Tealive outlet as Store Manager! Manage daily operations, staff scheduling, inventory control, and customer satisfaction for one of Malaysia\'s fastest-growing beverage chains. Previous F&B management experience preferred. Opportunity to grow within our expanding network across 3 continents.',
            ],
            [
                'title' => 'Supply Chain Coordinator',
                'location' => 'Kota Damansara, Selangor',
                'description' => 'Join Loob Holding\'s supply chain team to ensure seamless operations across 1,000+ outlets. Coordinate inventory management, vendor relationships, and logistics for our F&B brands. Background in supply chain, logistics, or operations preferred. Work with international suppliers and distributors across Asia.',
            ],
            [
                'title' => 'Brand Development Manager',
                'location' => 'Kota Damansara, Selangor',
                'description' => 'Shape the future of Loob Holding\'s F&B brands! Lead brand strategy, product development, and market expansion initiatives. Work closely with franchisees and international partners to grow our presence across Southeast Asia. MBA or relevant degree with 5+ years brand management experience in FMCG or F&B industry.',
            ],
        ];

        foreach ($jobs as $job) {
            JobListing::create($job);
        }
    }
}
