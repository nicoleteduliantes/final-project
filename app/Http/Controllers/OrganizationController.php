<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\Membership;
use Illuminate\Http\Request;

class OrganizationController extends Controller
{
    public function index()
    {
        // Fetch all organizations from the DB
        $organizations = Organization::all();

        // Return them as JSON (Laravel does this automatically)
        return response()->json($organizations);
    }

    public function checkMembership()
    {
        // Get the current student's ID
        $studentId = auth()->user()->student_id; 

        // Fetch orgs 
        $organizations = Organization::all()->map(function ($org) use ($studentId) {
            
            $isMember = Membership::where('org_id', $org->org_id)
                ->where('student_id', $studentId)
                ->exists();

            $org->checkMembership = $isMember;
            
            return $org;
        });

        // Return to Vue
        return response()->json($organizations);
    }
}