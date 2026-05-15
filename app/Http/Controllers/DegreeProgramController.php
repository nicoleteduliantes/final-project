<?php

namespace App\Http\Controllers;

use App\Models\DegreeProgram;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;


/* ============================ 
    DEGREE PROGRAMS (OSA SIDE) 
   ============================ */

class DegreeProgramController extends Controller
{
    
     /* Fetch all degree programs for the frontend dropdown. */
     
    public function index(): JsonResponse
    {
       
        $programs = DegreeProgram::all();

        return response()->json($programs);
    }

  
    public function getByCollege($collegeId): JsonResponse
    {
        $programs = DegreeProgram::where('college_id', $collegeId)->get();
        
        return response()->json($programs);
    }

    public function getProgramsWithColleges()
    {
        
    /* Load degree programs with their parent college */
        $programs = DegreeProgram::with('college')->get()->map(function ($program) {
            return [
                'id'           => $program->degprog_id,
                'degprog_name' => $program->degprog_name,
                'college'      => $program->college ? $program->college->college_name : 'N/A',
            ];
        });

        return response()->json($programs);
    }


}