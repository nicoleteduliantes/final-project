<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\College;
use App\Models\DegreeProgram;

class DegreeProgramSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
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
            $college = College::firstOrCreate([
                'college_name' => $collegeName
            ]);

            foreach ($degreeList as $programName) {
                DegreeProgram::firstOrCreate([
                    'degprog_name' => $programName,
                    'college_id'   => $college->college_id
                ]);
            }
        }
    }
}