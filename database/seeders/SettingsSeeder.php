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
                'value' => 6,
                'description' => 'Amount of hits to see promo page',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ], [
                'key' => 'announcement_html',
                'value' => null,
                'description' => 'Content of announcement',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ], [
                'key' => 'announcement_enabled',
                'value' => 1,
                'description' => 'Announcement is enabled',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ], [
                'key' => 'announcement_timeout',
                'value' => 24,
                'description' => 'Announcement period',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        ]);
    }
}
