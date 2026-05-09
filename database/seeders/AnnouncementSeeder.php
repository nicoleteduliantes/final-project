<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Announcement;
use App\Models\Organization;
use App\Models\OSA;
use Carbon\Carbon;

class AnnouncementSeeder extends Seeder
{
    public function run(): void
    {
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