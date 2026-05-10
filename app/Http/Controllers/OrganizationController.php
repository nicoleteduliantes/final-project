<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\Membership;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

    public function show()
    {
        return response()->json(Auth::user());
    }

    public function updateStatus(Request $request)
    {
        $request->validate([
            'application_status' => 'required|in:open,closed'
        ]);
        
        $org = auth()->user(); 

        if (!$org) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $org->update([
            'application_status' => $request->application_status
        ]);

        return response()->json([
            'message' => 'Status updated!',
            'new_status' => $org->application_status
        ]);
    }
}