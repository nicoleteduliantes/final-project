<?php

namespace App\Http\Controllers;

use App\Models\Membership;
use Illuminate\Http\JsonResponse;

class MembershipController extends Controller
{
    // Fetch all memberships with their related organization names.
    public function index(): JsonResponse
    {
    // Gets the ID of the currently logged-in user/student
    $userId = auth()->id();

    // Gets the current memberships of the user
    $memberships = Membership::with(['organization', 'applicationDetail'])
        ->where('student_id', $userId) 
        ->get();

    return response()->json($memberships);
    }
}