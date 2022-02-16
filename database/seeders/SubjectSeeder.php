<?php

namespace Database\Seeders;

use App\Models\Grade;
use App\Models\Subject;
use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $grades = Grade::all();

        $grades->each(function ($grade) {
            Subject::factory(7)->create([
                'grade_id' => $grade->id,
            ]);
        });
    }
}
