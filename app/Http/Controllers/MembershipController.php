<?php

namespace App\Http\Controllers;

use App\Models\Membership;
use Illuminate\Http\JsonResponse;

class MembershipController extends Controller
{
    // Fetch all memberships with their related organization names.
    public function index(): JsonResponse
    {
        //  With(organization) matches the function name in the Membership model
        $memberships = Membership::with('organization')->get();

        return response()->json($memberships);
    }
}