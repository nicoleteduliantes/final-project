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
        Schema::create('events', function (Blueprint $table) {
        $table->id('event_id'); 

       $table->foreignId('org_id')
          ->constrained('organizations', 'org_id')
          ->onDelete('cascade');

        $table->string('event_name');
        $table->string('event_host');
        $table->text('description')->nullable();
        $table->string('event_logo')->nullable();
        $table->date('date');
        $table->string('location');
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
