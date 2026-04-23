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
        Schema::create('application_details', function (Blueprint $table) {
        $table->id('application_id'); 

        $table->foreignId('membership_id')
              ->constrained('memberships', 'membership_id')
              ->onDelete('cascade');

        $table->text('cover_letter');
        $table->text('skills')->nullable();
        $table->text('previous_experience')->nullable();

        $table->string('applied_committee');
        $table->date('date_applied');

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('application_details');
    }
};
