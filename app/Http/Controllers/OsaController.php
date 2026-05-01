<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class OsaController extends Controller
{
    public function store(Request $request)
    {
        // Validate incoming data
        $request->validate([
            'org_name'    => 'required|string|max:255|unique:organizations,org_name',
            'category'    => 'required|in:Academic,Civic,Cultural,Sports,Fraternities/Sororities,Other',
            'description' => 'nullable|string',
        ]);

        //Create the record
        $organization = Organization::create([
            'org_name'    => $request->org_name,
            'category'    => $request->category,
            'description' => $request->description,
            'password'    => Hash::make("org_admin123"), //set a default password
        ]);

        //Return response with the auto-generated org_id
        return response()->json([
            'status'  => 'success',
            'message' => 'Organization registered successfully!',
            'org_id'  => $organization->org_id
        ], 201);
    }
}
