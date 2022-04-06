<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            UserSeeder::class,
            GradeSeeder::class,
            StudentSeeder::class,
            SubjectSeeder::class,
            ExamSeeder::class,
            MarkGradingSeeder::class,
        ]);
    }
}
