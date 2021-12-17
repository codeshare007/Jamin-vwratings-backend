<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SettingsSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::table('settings')->insertOrIgnore([
            [
                'key' => 'ads_hits',
                'value' => 1000,
                'description' => 'Amount of hits to see promo page'
            ], [
                'key' => 'announcement_html',
                'value' => null,
                'description' => 'Content of announcement'
            ], [
                'key' => 'announcement_enabled',
                'value' => 0,
                'description' => 'Announcement is enabled'
            ], [
                'key' => 'announcement_timeout',
                'value' => 1440,
                'description' => 'Announcement period in seconds'
            ]
        ]);
    }
}
