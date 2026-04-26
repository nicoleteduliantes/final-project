<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StudentController extends Controller
{
    public function store(Request $request)
    {
        // 1. Validate the incoming request data
        $validated = $request->validate([
            'student_id'     => 'required|string|unique:students,student_id',
            'first_name'     => 'required|string|max:255',
            'last_name'      => 'required|string|max:255',
            'up_email'       => 'required|email|unique:students,up_email',
            'admission_date' => 'required|date',
            'degprog_id'     => 'required|exists:degprogs,degprog_id',
            'password'       => 'required|string|min:8',
        ]);

        // 2. Create the student record
        $student = Student::create([
            'student_id'     => $validated['student_id'],
            'first_name'     => $validated['first_name'],
            'last_name'      => $validated['last_name'],
            'up_email'       => $validated['up_email'],
            'admission_date' => $validated['admission_date'],
            'degprog_id'     => $validated['degprog_id'],
            'password'       => Hash::make($validated['password']), 
        ]);

        // 3. Return a response in the format similar to your OsaController
        return response()->json([
            'status'  => 'success',
            'message' => 'Student registered successfully!',
            'data'    => [
                'student_id' => $student->student_id,
                'full_name'  => $student->first_name . ' ' . $student->last_name,
                'email'      => $student->up_email,
            ]
        ], 201);
    }

    public function login(Request $request)
    {
        // 1. Validate that the email was sent
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string'
        ]);

        // 2. Find the student
        $student = Student::where('up_email', $request->email)->first();

        // 3. Check if student exists AND password matches
        if (!$student || !Hash::check($request->password, $student->password)) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Invalid credentials.'
            ], 401);
        }

        // 4. Return successful response
        return response()->json([
            'status'  => 'success',
            'message' => 'Login successful!',
            'data'    => [
                'student_id' => $student->student_id,
                'name'       => $student->first_name
            ]
        ], 200);
    }

    public function index()
    {
        return response()->json([
            'message' => 'Hello World'
        ]);
    }
}