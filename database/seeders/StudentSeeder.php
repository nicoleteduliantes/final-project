<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Student;
use Illuminate\Support\Facades\Hash;

class StudentSeeder extends Seeder
{
    /* Students seeder for demonstration purposes */
    public function run(): void
    {
        $students = [
            [
                'student_id' => '2025-08075',
                'last_name'  => 'Eduliantes',
                'first_name' => 'Nicole Marie',
                'up_email'   => 'nteduliantes@up.edu.ph',
                'degprog_id' => '12',
                'password'   => Hash::make('student123'),
            ],
            [
                'student_id' => '2025-08076',
                'last_name'  => 'Gellecania',
                'first_name' => 'Ezra Ysabela',
                'up_email'   => 'eggellecania@up.edu.ph',
                'degprog_id' => '12',
                'password'   => Hash::make('student123'),
            ],
            [
                'student_id' => '2025-08077',
                'last_name'  => 'Loon',
                'first_name' => 'Leiry Shane',
                'up_email'   => 'lbloon@up.edu.ph',
                'degprog_id' => '12',
                'password'   => Hash::make('student123'),
            ],
            [
                'student_id' => '2025-08078',
                'last_name'  => 'Caña',
                'first_name' => 'Aiyesha Threa',
                'up_email'   => 'ascaña@up.edu.ph',
                'degprog_id' => '12',
                'password'   => Hash::make('student123'),
            ],
            [
                'student_id' => '2025-08079',
                'last_name'  => 'Sumbeling',
                'first_name' => 'Vonne Chelsea',
                'up_email'   => 'vasumbeling@up.edu.ph',
                'degprog_id' => '12',
                'password'   => Hash::make('student123'),
            ],
            [
                'student_id' => '2025-08080',
                'last_name'  => 'Kawahara',
                'first_name' => 'Shenn Eunice',
                'up_email'   => 'sgkawahara@up.edu.ph',
                'degprog_id' => '12',
                'password'   => Hash::make('student123'),
            ],
        ];

        foreach ($students as $student) {
            Student::updateOrCreate(
                ['student_id' => $student['student_id']],
                $student
            );
        }
    }
}