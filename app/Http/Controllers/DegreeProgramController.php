<?php

namespace App\Http\Controllers;

use App\Models\DegreeProgram;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class DegreeProgramController extends Controller
{
    /**
     * Fetch all degree programs for the frontend dropdown.
     */
    public function index(): JsonResponse
    {
        // Fetch all programs from the 'degprogs' table
        $programs = DegreeProgram::all();

        // Return in a clean JSON format
        return response()->json($programs);
    }

    /**
     * Optional: Fetch programs filtered by college
     */
    public function getByCollege($collegeId): JsonResponse
    {
        $programs = DegreeProgram::where('college_id', $collegeId)->get();
        
        return response()->json($programs);
    }
}