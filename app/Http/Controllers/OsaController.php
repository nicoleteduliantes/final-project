<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class OsaController extends Controller
{
    public function registerOrganization(Request $request)
    {
        // Validate incoming data
        $validated = $request->validate([
            'name'        => 'required|string|max:255|unique:organizations,name',
            'category'    => 'required|string|max:100',
            'description' => 'nullable|string',
        ]);

        //Create the record
        $organization = Organization::create([
            'name'        => $validated['name'],
            'category'    => $validated['category'],
            'description' => $validated['description'] ?? null,
            'password'    => Hash::make('org_admin_2026'),     // Default password
        ]);

        //Return response with the auto-generated org_id
        return response()->json([
            'status'  => 'success',
            'message' => 'Organization registered successfully!',
            'data'    => [
                'org_id'           => $organization->org_id, 
                'name'             => $organization->name,
                'category'         => $organization->category,
                'default_password' => 'org_admin123'
            ]
        ], 201);
    }

    public function test() {
        return response()->json([
            'message' => 'hello world'
        ]);
    }
}
