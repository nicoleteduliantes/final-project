<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    /* =========================
       PUBLIC FEED (STUDENTS)
    ========================== */
    public function index()
{
    $user = auth()->user();

    if (!$user) {
        return response()->json([
            'message' => 'Unauthenticated'
        ], 401);
    }

    $appliedOrgIds = \App\Models\ApplicationDetail::whereHas('membership', function ($q) use ($user) {
        $q->where('student_id', $user->student_id);
    })
    ->with('membership')
    ->get()
    ->pluck('membership.org_id')
    ->unique();

    $announcements = Announcement::with('organization')
        ->where(function ($query) use ($appliedOrgIds) {

            $query->where('audience', 'all');

            if ($appliedOrgIds->isNotEmpty()) {
                $query->orWhere(function ($q) use ($appliedOrgIds) {
                    $q->where('audience', 'applicants')
                      ->whereIn('org_id', $appliedOrgIds);
                });
            }
        })
        ->latest()
        ->get();

    return response()->json($announcements);
}

    /* =========================
       ORG / OSA OWN POSTS
    ========================== */
    public function myAnnouncements()
    {
        $user = auth()->user();

        $orgId = $user->org_id ?? null;
        $osaId = $user->osa_id ?? null;

        $query = Announcement::query();

        if ($orgId) {
            $query->where('org_id', $orgId);
        } elseif ($osaId) {
            $query->where('osa_id', $osaId);
        } else {
            return response()->json([]);
        }

        return response()->json(
            $query->orderBy('date_posted', 'desc')->get()
        );
    }

    /* =========================
       CREATE ANNOUNCEMENT
       (ORG OR OSA)
    ========================== */
    public function store(Request $request)
    {
        $user = auth()->user();

        $orgId = $user->org_id ?? null;
        $osaId = $user->osa_id ?? null;

        if (!$orgId && !$osaId) {
            return response()->json([
                'message' => 'Unauthorized: no org or OSA account linked'
            ], 403);
        }

        $announcement = Announcement::create([
            'title' => $request->title,
            'content' => $request->content,
            'audience' => $request->audience ?? 'all',
            'date_posted' => now()->toDateString(),
            'org_id' => $orgId,
            'osa_id' => $osaId,
        ]);

        return response()->json($announcement);
    }

    /* =========================
       UPDATE (ORG OR OSA OWNER)
    ========================== */
    public function update(Request $request, $id)
    {
        $user = auth()->user();

        $announcement = Announcement::where('announcement_id', $id)->firstOrFail();

        // ORG OWNER
        if ($user->org_id && $announcement->org_id === $user->org_id) {
            $announcement->update([
                'title' => $request->title,
                'content' => $request->content,
            ]);

            return response()->json([
                'message' => 'Updated successfully',
                'data' => $announcement
            ]);
        }

        // OSA OWNER
        if ($user->osa_id && $announcement->osa_id === $user->osa_id) {
            $announcement->update([
                'title' => $request->title,
                'content' => $request->content,
            ]);

            return response()->json([
                'message' => 'Updated successfully',
                'data' => $announcement
            ]);
        }

        return response()->json([
            'message' => 'Unauthorized'
        ], 403);
    }

    /* =========================
       DELETE (ORG OR OSA OWNER)
    ========================== */
    public function destroy($id)
    {
        $user = auth()->user();

        $announcement = Announcement::where('announcement_id', $id)->firstOrFail();

        $isOrgOwner = $user->org_id && $announcement->org_id === $user->org_id;
        $isOsaOwner = $user->osa_id && $announcement->osa_id === $user->osa_id;

        if (!$isOrgOwner && !$isOsaOwner) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }

        $announcement->delete();

        return response()->json([
            'message' => 'Deleted successfully'
        ]);
    }
}