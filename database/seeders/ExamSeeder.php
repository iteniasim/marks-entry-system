<?php

namespace Database\Seeders;

use App\Models\Exam;
use Illuminate\Database\Seeder;

class ExamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $exams = ['First Terminal', 'Second terminal', 'Third Terminal'];

        foreach ($exams as $exam) {
            Exam::create(['name' => $exam]);
        }
    }
}
