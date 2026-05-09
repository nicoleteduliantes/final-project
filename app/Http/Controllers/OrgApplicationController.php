<?php

namespace App\Http\Controllers;

use App\Models\Membership;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrgApplicationController extends Controller
{
//SHOW THE APPLICATIONS
 public function index()
    {
    
    $orgId = auth()->user()->org_id;
    $applications = Membership::with(['applicationDetail', 'student.degreeProgram.college' ])
                       ->where('org_id', $orgId) //ensures that it will display applications for THIS ORG only
                       ->where('status', 'Pending')//shows only applications that are still 'pending'
                       ->get();

        return response()->json($applications);
}

//DECIDE APPLICATION (ACCEPTED/REJECTED)
public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Accepted,Rejected',
            'position' => 'required|string'
        ]);

        $membership = Membership::with('applicationDetail')->findOrFail($id);

        //added security, ensures the logged in org actually owns the application
        if ($membership->org_id !== auth()->user()->org_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
    }

        $updateData = [
        'status' => $request->status,
        'position'=>$request->position,
        'expiration' => now()->addYear(), //sets membership expiration to 1 year
    ];

    //applied committee becomes assigned committee
    if ($request->status === 'Accepted') {
        $updateData['assigned_committee'] = $membership->applicationDetail->applied_committee;
    } else {
        // If rejected, ensure assigned_committee is null
        $updateData['assigned_committee'] = null;
    }

    $membership->update($updateData);

    return response()->json(['message' => 'Application processed successfully!']);
}

//SHOW ACCEPTED MEMBERS
public function show (){
    $orgId = auth()->user()->org_id;

    $members = Membership::with(['student', 'applicationDetail'])
        ->where('org_id', $orgId)
        ->where('status', 'Accepted') // only shows those already accepted
        ->get();

    return response()->json($members);
}
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


