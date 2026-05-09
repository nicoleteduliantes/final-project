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
            'password' => Hash::make('superadmin123'), 
        ]);

        $this->call([
            OrganizationSeeder::class,
        ]);

        $this->call([
        DegreeProgramSeeder::class,
        ]);

        $this->call([
        StudentSeeder::class,
        ]);
        $org = Organization::first();
        $osa = OSA::first();

        if ($org) {
            Announcement::create([
                'title'       => 'Org First Announcement',
                'content'     => 'This is a test announcement from the ' . $org->org_name,
                'date_posted' => Carbon::now()->toDateString(),
                'org_id'      => $org->org_id,
                'osa_id'      => null,
            ]);
        }

        if ($osa) {
            Announcement::create([
                'title'       => 'Official OSA Memo',
                'content'     => 'Please submit your activity permits by Friday afternoon.',
                'date_posted' => Carbon::now()->toDateString(),
                'org_id'      => null,
                'osa_id'      => $osa->osa_id,
            ]);
        }
    }
}