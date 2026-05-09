<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StudentController extends Controller
{
    protected $allowedDomain = 'up.edu.ph'; // allowable domain for the up email address

    // Registration function
    public function store(Request $request)
    {

    //For student id validation
    $currentYear = date('Y'); // Gets the current year 2026

        // Validate the incoming request data
        $validated = $request->validate(
            [
            'student_id'     => ['required', 
                                'string',
                                'unique:students,student_id',
                                'regex: /^20\d{2}-\d{5}$/',
                                    function ($attribute, $value, $fail) use ($currentYear) {
                                    $year = (int) substr($value, 0, 4);
                                    if ($year > $currentYear) {
                                        $fail("Student ID is invalid.");
                                    }
                                },
                                ], //check student id format
            'first_name'     => 'required|string|max:255',
            'last_name'      => 'required|string|max:255',
            'up_email'       => ['required',
                                'email',
                                'unique:students,up_email',
                                'regex:/^[a-zA-Z0-9._%+-]+@' . preg_quote($this->allowedDomain) . '$/i'], //checks for correct domain in up email
            'degprog_id'     => 'required|exists:degprogs,degprog_id',
            'password'       => 'required|string|min:8',
        ],
        
        // Custom error messages
        ['student_id.regex' => 'The Student ID must follow the format 20XX-XXXXX.',
        'student_id.unique' => 'This Student ID is already registered.',
        'up_email.regex'  => "Please use your official @{$this->allowedDomain} email address.",
        'up_email.unique' => 'This email is already registered.',
        'up_email.email'  => "Please use your official @{$this->allowedDomain} email address.",]

        );

        // Create student record
        $student = Student::create([
            'student_id'     => $validated['student_id'],
            'first_name'     => $validated['first_name'],
            'last_name'      => $validated['last_name'],
            'up_email'       => $validated['up_email'],
            'degprog_id'     => $validated['degprog_id'],
            'password'       => Hash::make($validated['password']), 
        ]);

        // Return a response 
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

    // Log in function
    public function login(Request $request)
{
    // Validate user input
    $request->validate([
        'email'    => 'required|email',
        'password' => 'required|string'
    ]);

    if (!str_ends_with($request->email, "@" . $this->allowedDomain)) {
            return response()->json([
                'message' => "Please use your official @{$this->allowedDomain} account."
            ], 403);
        }

    // Prepare credentials for the guard
    $credentials = [
        'up_email' => $request->email,
        'password' => $request->password
    ];

    // Attempt login using the 'student' guard
    if (auth()->guard('student')->attempt($credentials)) {
        $student = auth()->guard('student')->user();

        // Generate Token
        $token = $student->createToken('student_token')->plainTextToken;

        return response()->json([
            'status'  => 'success',
            'message' => 'Login successful!',
            'token'   => $token,
            'data'    => [
                'student_id' => $student->student_id,
                'name'       => $student->first_name,
                'email'      => $student->up_email
            ]
        ], 200);
    }

    // Fail Response
    return response()->json([
        'status'  => 'error',
        'message' => 'Invalid credentials.'
    ], 401);
}
    //SHOW ALL EVENTS IN STUDENT DASHBOARD
     public function index()
    {
    
    $events = Event::with('organization')
                       ->where('date', '>=', now()) //ensures that it will display upcoming events only
                       ->orderBy('date', 'asc')
                       ->get();

        return response()->json($events);
}
}