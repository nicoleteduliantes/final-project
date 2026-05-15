<?php

namespace Database\Seeders;

use App\Models\Osa;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /* Database seeder for demonstration purposes */
    public function run(): void
    {
        /* OSA credentials seeder */
        Osa::create([
            'osa_id'   => 220422, // Your chosen manual ID 
            'password' => Hash::make('superadmin123'), 
        ]);

        /* Organizations seeder call */
        $this->call([
            OrganizationSeeder::class,
        ]);

        /* Degprogs and colleges seeder call */
        $this->call([
        DegreeProgramSeeder::class,
        ]);

        /* Students seeder call */
        $this->call([
        StudentSeeder::class,
        ]);

        /* Announcements seeder call */
        $this->call([
        AnnouncementSeeder::class,
        ]);
    }
}