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
        Schema::create('major_registers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id');
            $table->foreignId('student_id');
            $table->string('aprove_no')->nullable();
            $table->string('ar_wa_tha_no')->nullable();
            $table->string('type')->nullable();
            $table->string('major')->nullable();
            $table->string('get_university')->nullable();
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
        Schema::dropIfExists('major_registers');
    }
};
