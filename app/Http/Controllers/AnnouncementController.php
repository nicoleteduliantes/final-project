<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    // STUDENT FEED (all announcements)
    public function index()
    {
        return Announcement::with(['organization', 'osa'])
            ->orderBy('date_posted', 'desc')
            ->get();
    }

    // ORG OWN ANNOUNCEMENTS
    public function myAnnouncements()
{
    $user = auth()->user();

    $orgId = $user->org_id;

    if (!$orgId) {
        return response()->json([]);
    }

    return response()->json(
        Announcement::where('org_id', $orgId)
            ->orderBy('date_posted', 'desc')
            ->get()
    );
}

    // CREATE ANNOUNCEMENT
public function store(Request $request)
{
    $user = auth()->user();

    $orgId = $user->org_id;

    if (!$orgId) {
        return response()->json([
            'message' => 'User is not linked to an organization'
        ], 403);
    }

    $announcement = Announcement::create([
        'title' => $request->title,
        'content' => $request->content,
        'date_posted' => now()->toDateString(),
        'org_id' => $orgId,
        'osa_id' => null,
    ]);

    return response()->json($announcement);
}

    // UPDATE ANNOUNCEMENT (ORG ONLY)
    public function update(Request $request, $id)
    {
        $orgId = auth()->user()->organization->org_id;

        $announcement = Announcement::where('announcement_id', $id)
            ->where('org_id', $orgId)
            ->firstOrFail();

        $announcement->update([
            'title' => $request->title,
            'content' => $request->content,
        ]);

        return response()->json([
            'message' => 'Updated successfully',
            'data' => $announcement
        ]);
    }

    public function destroy($id)
{
    $orgId = auth()->user()->org_id;

    $announcement = Announcement::where('announcement_id', $id)
        ->where('org_id', $orgId)
        ->firstOrFail();

    $announcement->delete();

    return response()->json([
        'message' => 'Deleted successfully'
    ]);
}
}