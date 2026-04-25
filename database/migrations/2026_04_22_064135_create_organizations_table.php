<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('organizations', function (Blueprint $table) {
            $table->id('org_id'); 

            $table->string('org_name');

            $table->enum('category', [
                'Academic', 'Civic', 'Cultural', 'Sports', 'Fraternities/Sororities', 'Other'
            ]);

            $table->text('description');
            $table->string('password');

            $table->timestamps();
        });
        // set the auto-increment of org_id starting point to 202601
    DB::statement("ALTER TABLE organizations AUTO_INCREMENT = 202601;");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organizations');
    }
};
