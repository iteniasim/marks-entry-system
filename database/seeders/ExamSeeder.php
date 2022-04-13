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
        $exams = [['First Terminal', 10, 0], ['Second terminal', 20, 0], ['Third Terminal', 30, 1]];

        foreach ($exams as $exam) {
            Exam::create([
                'name' => $exam[0],
                'final_evaluation_percentage' => $exam[1],
                'is_final' => $exam[2],
            ]);
        }
    }
}
