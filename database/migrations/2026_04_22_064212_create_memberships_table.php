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
        Schema::create('memberships', function (Blueprint $table) {
            $table->id('membership_id'); 

            $table->foreignId('org_id')
                ->constrained('organizations', 'org_id')
                ->onDelete('cascade');

            $table->string('student_id');

            $table->foreign('student_id')
                ->references('student_id')
                ->on('students')
                ->onDelete('cascade');

            $table->string('position');
            $table->date('expiration')->nullable();

            $table->enum('status', ['Accepted', 'Rejected', 'Expired']);

            $table->string('assigned_committee')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('memberships');
    }
};
