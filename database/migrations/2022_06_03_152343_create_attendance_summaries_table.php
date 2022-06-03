<?php

use App\Models\Exam;
use App\Models\Grade;
use App\Models\Student;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendance_summaries', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Student::class);
            $table->foreignIdFor(Grade::class);
            $table->foreignIdFor(Exam::class);
            $table->year('year');
            $table->unsignedBigInteger('working_days');
            $table->unsignedBigInteger('absent_days');
            $table->unsignedBigInteger('present_days');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attendance_summaries');
    }
};
