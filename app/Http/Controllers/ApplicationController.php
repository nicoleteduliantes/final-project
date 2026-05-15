<?php

namespace App\Http\Controllers;

use App\Models\ApplicationDetail;
use App\Models\Membership;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/* ============================
    APPLICATION (STUDENT SIDE) 
   ============================*/

class ApplicationController extends Controller
{
    public function store(Request $request)
    {

    $org = Organization::find($request->org_id);

    if (!$org) {
        return response()->json(['message' => 'Organization not found.'], 404);
    }

    /* Block the application if the application_status is not 'Open'*/
    if ($org->application_status !== 'open') {
        return response()->json([
            'status' => 'error',
            'message' => 'Sorry, this organization is currently not accepting applications.'
        ], 403); 
    }

        try {
            return DB::transaction(function () use ($request) {
                $membership = Membership::create([
                    'org_id' => $request->org_id,
                    'student_id' => auth()->user()->student_id,
                    'status' => 'Pending',
                    'position' => 'Applicant',
                ]);

                ApplicationDetail::create([
                    'membership_id' => $membership->membership_id,
                    'cover_letter' => $request->cover_letter,
                    'skills' => $request->skills,
                    'previous_experience' => $request->previous_experience,
                    'applied_committee' => $request->applied_committee,
                    'date_applied' => $request->date_applied,
                ]);

                return response()->json(['message' => 'Success'], 201);
            });
        } catch (\Exception $e) {
           
            return response()->json([
                'error' => $e->getMessage(),
                'line' => $e->getLine()
            ], 500);
        }
    }
}