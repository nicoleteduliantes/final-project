<?php

namespace App\Http\Controllers;

use App\Models\Organization;
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
}