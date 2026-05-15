<?php

namespace App\Http\Controllers;

use App\Models\Membership;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/* =========================
    APPLICATION (ORG SIDE) 
   =========================*/
class OrgApplicationController extends Controller
{
/* display applications */
 public function index()
    {
    
    $orgId = auth()->user()->org_id;
    $applications = Membership::with(['applicationDetail', 'student.degreeProgram.college' ])
                /* ensures that it will display applications for THIS ORG only */
                       ->where('org_id', $orgId)
                       ->get();

        return response()->json($applications);
}

/* Decide application (Accepted/Rejected) */
public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Accepted,Rejected',
            'position' => 'required|string'
        ]);

        $membership = Membership::with('applicationDetail')->findOrFail($id);

        /* ensures the logged in org actually owns the application */
        if ($membership->org_id !== auth()->user()->org_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
    }

        $updateData = [
        'status' => $request->status,
        'position'=>$request->position,
        'expiration' => now()->addYear(), //sets membership expiration to 1 year
    ];

    if ($request->status === 'Accepted') {
        $updateData['assigned_committee'] = $membership->applicationDetail->applied_committee;
    } else {
        $updateData['assigned_committee'] = null;
    }

    $membership->update($updateData);

    return response()->json(['message' => 'Application processed successfully!']);
}

/* ========================
    MEMBERSHIP (ORG SIDE)
   ======================== */

/* Show Accepted applicants (Now Members) */
public function show (){
    $orgId = auth()->user()->org_id;

    $members = Membership::with(['student', 'applicationDetail'])
        ->where('org_id', $orgId)
        ->where('status', 'Accepted')
        ->get();

    return response()->json($members);
}

/* Delete a Member */
public function destroy($id)
{
    try {
        $membership = Membership::where('membership_id', $id)->first();

        if (!$membership) {
            return response()->json([
                'message' => 'Member not found'
            ], 404);
        }

        if ($membership->org_id !== auth()->user()->org_id) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }

        $membership->delete();

        return response()->json([
            'message' => 'Member removed successfully'
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Server error',
            'error' => $e->getMessage()
        ], 500);
    }
}
}


