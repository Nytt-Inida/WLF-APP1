<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SiteSetting;

class SiteSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Default site settings
        $defaultSettings = [
            'sticky_bar_enabled' => '0',
            'sticky_bar_price' => '1',
            'sticky_bar_coupon_code' => '',
            'sticky_bar_discount_percent' => '',

        ];

        foreach ($defaultSettings as $key => $value) {
            SiteSetting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }
    }
}


