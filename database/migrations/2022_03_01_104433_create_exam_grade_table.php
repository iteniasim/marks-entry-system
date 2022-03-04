<?php

use App\Models\Exam;
use App\Models\Grade;
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
        Schema::create('exam_grade', function (Blueprint $table) {
            $table->id();
            $table->year('year');
            $table->foreignIdFor(Exam::class)->constrained();
            $table->foreignIdFor(Grade::class)->constrained();
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
        Schema::dropIfExists('exam_grade');
    }
};
