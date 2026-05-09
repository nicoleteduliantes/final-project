<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use App\Models\Membership;

class MembershipController extends Controller
{
    // GET all memberships for logged-in user
    public function index(): JsonResponse
    {
        $userId = auth()->id();

        Membership::where('student_id', $userId)
        ->where('status', 'Accepted')
        ->where('expiration', '<', now())
        ->update(['status' => 'Expired']);

        $memberships = Membership::with(['organization', 'applicationDetail'])
            ->where('student_id', $userId)
            ->get();

        return response()->json($memberships);
    }

    // DELETE member (REMOVE from organization)
    public function destroy($id): JsonResponse
    {
        try {
            $membership = Membership::where('membership_id', $id)->first();

            if (!$membership) {
                return response()->json([
                    'message' => 'Member not found'
                ], 404);
            }

            $membership->delete();

            return response()->json([
                'message' => 'Member removed successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Server error while deleting member',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}