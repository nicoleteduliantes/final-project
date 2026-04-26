<?php

namespace App\Models;
namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StudentController extends Controller
{
    public function store(Request $request)
    {
        // 1. Validate the incoming request data
        $validatedData = $request->validate([
            'student_id'     => 'required|string|unique:students,student_id',
            'first_name'     => 'required|string|max:255',
            'last_name'      => 'required|string|max:255',
            'up_email'       => 'required|email|unique:students,up_email',
            'admission_date' => 'required|date',
            'degprog_id'      => 'required|exists:degprogs,degprog_id',
            'password'      => 'required|string|max:255',
        ]);

        // 2. Create the student record
        // This uses the $fillable array we defined in the Model
        $student = Student::create($validatedData);

        // 3. Return a response
        return response()->json([
            'message' => 'Student registered successfully!',
            'student' => $student
        ], 201);
    }

    public function login(Request $request)
    {
        // 1. Validate that the email was sent
        $credentials = $request->validate([
            'email' => 'required|email'
        ]);

        // 2. Find the student by the 'up_email' column
        $student = Student::where('up_email', $credentials['email'])->first();

        // 3. Check if student exists
        if (!$student) {
            return response()->json([
                'message' => 'Student not found.'
            ], 404);
        }

        // 4. Return the student data
        return response()->json([
            'message' => 'Login successful!',
            'student' => $student
        ], 200);
    }

    public function index()
    {
        return response()->json([
            [
            'degprog_id' => 1,
            'degprog_name' => 'BS Compsci'
            ]
            
        ], 200);
    }
}