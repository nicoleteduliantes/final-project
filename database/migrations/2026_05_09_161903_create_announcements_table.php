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
        Schema::create('announcements', function (Blueprint $table) {
            $table->id('announcement_id');
            $table->string('title');
            $table->text('content');
            $table->date('date_posted');
            
            $table->unsignedInteger('org_id')->nullable();
            $table->unsignedInteger('osa_id')->nullable();

            $table->foreign('org_id')->references('org_id')->on('organizations')->onDelete('cascade');
            $table->foreign('osa_id')->references('osa_id')->on('osa')->onDelete('cascade');
            
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('announcements');
    }
};
