<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class TimeLogsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        TimeLog::create([
            'user_id' => 1,
            'start_time' => Carbon::now()->subHours(2),
            'end_time' => Carbon::now(),
        ]);

        // Add more seed data as needed
    }
}
