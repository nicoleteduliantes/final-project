<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Organization;
use Illuminate\Support\Facades\Hash; // For the password attribute
use Carbon\Carbon; // For the expiration attribute (makes it easier to handle time)

class OrganizationSeeder extends Seeder
{
    public function run(): void
    {
        $organizations = [
            ['UP Mindanao Koro Kantahanay', 'Cultural', 'The official university choir of the University of the Philippines Mindanao; committed to a lifelong celebration of Mindanawon culture and the arts through singing.'],
            ['UP Mindanao Debate Varsity', 'Civic', 'The Resident Debate Team of the University of the Philippines Mindanao'],
            ['Himati', 'Civic', 'Himati is the official student publication of the University of the Philippines Mindanao. Est. 1996.'],
            ['UP Mindanao Agipo', 'Cultural', 'The Official Drumming Organization of the University of the Philippines Mindanao.'],
            ['UP Mindanao Communicator’s Guild (CommGuild)', 'Academic', 'The academic organization of the BA Communication and Media Arts program.'],
            ['UP Literary Society (LitSoc)', 'Academic', 'The officially recognized course-based student organization of the BA English program.'],
            ['DUGONG-Anthro', 'Academic', 'The academic organization of the BS Anthropology in the University of the Philippines Mindanao.'],
            ['Architecture Student Council (ASC)', 'Academic', 'The Architecture Student Council is the officially recognized course-based student organization of the BS Architecture.'],
            ['Siyensa ng Isports at Grupo ng mga Atleta (SIGA UPMIN)', 'Academic', 'An academic organization promoting educational and personal growth through awareness of health.'],
            ['Society of Agribusiness Economists (SoABE)', 'Academic', 'A program-based organization composed of BS Agribusiness Economics students.'],
            ['UP Mindanao Biological Society', 'Academic', 'Empowers biology students to become agents of change through science, research, and conservation.'],
            ['Society of Math Majors (SMM)', 'Academic', 'The duly recognized academic organization for BS Applied Mathematics students.'],
            ['PAFT Lambda Chapter', 'Academic', 'The official student organization of Food Technology students.'],
            ['SPARCS', 'Academic', 'The official tech community for Computer Science students.'],
            ['UP Mindanao Community of Data Experts (CODEX)', 'Academic', 'The official academic organization for BS Data Science students.'],
            ['UP Mindanao Dance Ensemble', 'Cultural', 'Dedicated to promoting cultural dances, dance sports, and modern styles.'],
            ['Siki ni Obli', 'Sports', 'The Official Football Organization of the University of the Philippines Mindanao.'],
            ['UP Labyog', 'Sports', 'The Official Frisbee Organization of the University of the Philippines Mindanao.'],
            ['Dasig UPMin', 'Sports', 'The Official Badminton Organization of the University of the Philippines Mindanao.'],
            ['Atletika – UP Mindanao', 'Sports', 'Dedicated to promoting athletics, physical fitness, and overall wellness.'],
            ['UP Salida', 'Cultural', 'The first film organization in Mindanao recognized as a member of the FDCP.'],
            ['AMPLI', 'Cultural', 'The organization of Musicians, Producers, Lyricist, and Instrumentalist (Bands Union).'],
            ['UP Mindanao Bidlisiw', 'Cultural', 'Organization for visual artists that aims to spark creativity and fuel love for arts.'],
            ['UP Youth to the Nation (UPY2DN)', 'Cultural', 'Christ-centered organization bringing national transformation.'],
            ['UPMAMS', 'Cultural', 'The organization of Muslim students in the University of the Philippines Mindanao.'],
            ['UP Legal Studies Society', 'Academic', 'Exclusive pre-law organization preparing students for the legal profession.'],
            ['UPMIN APLS', 'Academic', 'Empowering undergraduate students to mold minds for pro-people lawyering.'],
            ['UPMIN LeaDS', 'Civic', 'A socio-academic organization of DOST Scholars bringing science closer to the people.'],
            ['UAPSA', 'Academic', 'Student auxiliary of the United Architects of the Philippines.'],
            ['UPMIN YM', 'Civic', 'Mapping Community of the University of the Philippines Mindanao.'],
            ['UP Mindanao Mentefuwaley', 'Civic', 'The first and only LGBTQIA+ organization in the university.'],
            ['UPMIN CRCY', 'Civic', 'University of the Philippines Mindanao youth organization of Red Cross Volunteers.'],
            ['Youth For Animals (YFA-UPMIN)', 'Civic', 'Cluster of the PAWS dedicated to fostering compassion towards animals.'],
            ['UP MAROON EMERGENCY RESPONSE TEAM', 'Civic', 'The first-ever emergency and disaster response team of UP Mindanao.'],
            ['UP Beta Sigma Fraternity and Sorority', 'Fraternities/Sororities', 'Brotherhood and sisterhood organization.'],
            ['Alpha Phi Omega (APO Lambda Nu)', 'Fraternities/Sororities', 'Service-oriented fraternity and sorority.'],
        ];

        foreach ($organizations as $org) {
            Organization::create([
                'org_name'    => $org[0],
                'category'    => $org[1],
                'description' => $org[2],
                'password'    => Hash::make('admin123'),
                'expiration'  => Carbon::now()->addYear(),
                'status'      => 'Registered', // Updated to match your ENUM
            ]);
        }
    }
}