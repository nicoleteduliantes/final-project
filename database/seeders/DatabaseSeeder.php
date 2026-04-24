<?php

namespace Database\Seeders;

use App\Models\Osa;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

   public function run(): void
{
    Osa::create([
        'osa_id'   => 220422, // Your chosen manual ID 
        'password' => Hash::make('osa_admin_2026'), 
    ]);
}
}
