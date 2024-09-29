<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id');
            $table->string('student_code')->unique()->nullable();
            $table->string('name');
            $table->string('student_nrc_code')->nullable();
            $table->string('student_nrc_no')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('grade_10_desk_id')->nullable();
            $table->integer('grade_10_total_mark')->nullable();
            $table->string('grade_10_passed_year')->nullable();
            $table->string('father_name')->nullable();
            $table->string('father_nrc_code')->nullable();
            $table->string('father_nrc_no')->nullable();
            $table->string('mother_name')->nullable();
            $table->string('mother_nrc_code')->nullable();
            $table->string('mother_nrc_no')->nullable();
            $table->string('student_phone')->nullable();
            $table->string('parent_phone')->nullable();
            $table->string('address')->nullable();
            $table->text('note')->nullable();
            $table->unsignedInteger('created_by');
            $table->unsignedInteger('updated_by')->nullable();
            $table->unsignedInteger('deleted_by')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
