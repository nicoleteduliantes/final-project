<?php

namespace Database\Seeders;

use App\Models\College;
use App\Models\DegreeProgram;
use App\Models\Organization;
use App\Models\Osa;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $academicData = [
            'CHSS' => [
                'BS Architecture', 'MA Urban and Regional Planning', 'BA English (Creative Writing)',
                'BA Communication and Media Arts', 'AA Sports Studies', 'B Sports Science',
                'Diploma in Exercise and Sports Science', 'BS Anthropology', 'MS Human Movement Science'
            ],
            'CSM' => [
                'BS Applied Mathematics', 'BS Biology', 'BS Computer Science',
                'BS Food Technology', 'BS Data Science', 'MS Food Science', 'MS Biology'
            ],
            'SOM' => [
                'BS Agribusiness Economics', 'Master in Management', 'PhD in Research'
            ],
        ];

        foreach ($academicData as $collegeName => $degreeList) {
            // Create the College
            $college = College::create([
                'college_name' => $collegeName
            ]);

            foreach ($degreeList as $programName) {
                DegreeProgram::create([
                    'degprog_name' => $programName,
                    'college_id'   => $college->college_id
                ]);
            }
        }

        Osa::create([
            'osa_id'   => 220422, // Your chosen manual ID 
            'password' => Hash::make('admin123'), 
        ]);

        //Seeded org account for testing
        Organization::create([
            'org_name' => 'Test Org',
            'password' => Hash::make('pass123'),
            'category' => 'Academic'
        ]);
    }
}