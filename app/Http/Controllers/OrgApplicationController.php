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
    $applications = Membership::with(['applicationDetail', 'student' ])
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

        $membership = Membership::where('membership_id', $id)->firstOrFail();

        //added security, ensures the logged in org actually owns the application
        if ($membership->org_id !== auth()->user()->org_id) {
        return response()->json(['error' => 'Unauthorized'], 403);
    }

    $membership->update([
        'status' => $request->status,
        'position'=>$request->position
    ]);

    return response()->json(['message' => 'Application updated successfully']);
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

}

