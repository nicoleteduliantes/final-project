<?php

namespace App\Http\Controllers;

use App\Models\Membership;
use App\Models\ApplicationDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApplicationController extends Controller
{
    public function store(Request $request)
    {
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
            // This will send the EXACT error message to your browser console
            return response()->json([
                'error' => $e->getMessage(),
                'line' => $e->getLine()
            ], 500);
        }
    }
}