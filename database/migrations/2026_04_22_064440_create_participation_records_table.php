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
        Schema::create('participation_records', function (Blueprint $table) {
            $table->string('student_id');
            $table->foreign('student_id')
                ->references('student_id')
                ->on('students')
                ->onDelete('cascade');

            $table->foreignId('event_id')
                ->constrained('events', 'event_id')
                ->onDelete('cascade');

            $table->enum('record_status', ['Participant', 'Volunteer']);

            $table->primary(['student_id', 'event_id']);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('participation_records');
    }
};
