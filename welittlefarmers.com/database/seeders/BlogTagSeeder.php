<?php

namespace Database\Seeders;

use App\Models\BlogTag;
use Illuminate\Database\Seeder;

class BlogTagSeeder extends Seeder
{
    public function run()
    {
        $tags = [
            'Robotics',
            'AI',
            'Sustainability',
            'Smart Farming',
            'Agriculture',
            'Youth Education',
            'Technology',
            'Innovation',
            'Environment'
        ];

        foreach ($tags as $tag) {
            BlogTag::firstOrCreate(['name' => $tag]);
        }
    }
}