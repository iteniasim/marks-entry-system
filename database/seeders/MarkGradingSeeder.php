<?php

namespace Database\Seeders;

use App\Models\MarkGrading;
use Illuminate\Database\Seeder;

class MarkGradingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $markGradingData = [
            [
                'lower_mark_limit' => 90,
                'upper_mark_limit' => 100,
                'gpa' => '4.0',
                'grade' => 'A+',
                'description' => 'Outstanding',
            ],
            [
                'lower_mark_limit' => 80,
                'upper_mark_limit' => 89,
                'gpa' => '3.6',
                'grade' => 'A',
                'description' => 'Excellent',
            ],
            [
                'lower_mark_limit' => 70,
                'upper_mark_limit' => 79,
                'gpa' => '3.2',
                'grade' => 'B+',
                'description' => 'Very Good',
            ],
            [
                'lower_mark_limit' => 60,
                'upper_mark_limit' => 69,
                'gpa' => '2.8',
                'grade' => 'B',
                'description' => 'Good',
            ],
            [
                'lower_mark_limit' => 50,
                'upper_mark_limit' => 59,
                'gpa' => '2.4',
                'grade' => 'C+',
                'description' => 'Satisfactory',
            ],
            [
                'lower_mark_limit' => 40,
                'upper_mark_limit' => 49,
                'gpa' => '2.0',
                'grade' => 'C',
                'description' => 'Acceptable',
            ],
            [
                'lower_mark_limit' => 35,
                'upper_mark_limit' => 39,
                'gpa' => '1.6',
                'grade' => 'D',
                'description' => 'Basic',
            ],
            [
                'lower_mark_limit' => 0,
                'upper_mark_limit' => 34,
                'gpa' => '-',
                'grade' => 'NG',
                'description' => 'Not Graded',
            ],
        ];

        foreach ($markGradingData as $markGrade) {
            MarkGrading::create($markGrade);
        }
    }
}
