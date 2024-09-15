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
        Schema::create('universities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id');
            $table->foreignId('student_id');
            $table->string('type');
            $table->string('major');
            $table->string('year_of_attendance');
            $table->string('last_desk_id')->nullable();
            $table->string('current_desk_id')->nullable();
            $table->json('desk_id_history')->nullable();
            $table->boolean('assignment_a')->default(false);
            $table->boolean('assignment_b')->default(false);
            $table->text('note')->nullable();
            $table->string('custom_column_1')->nullable();
            $table->string('custom_column_2')->nullable();
            $table->string('custom_column_3')->nullable();
            $table->string('custom_column_4')->nullable();
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
        Schema::dropIfExists('universities');
    }
};
