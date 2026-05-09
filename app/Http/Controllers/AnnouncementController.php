<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    public function index()
    {
        $announcements = Announcement::with('organization')
            ->orderBy('date_posted', 'desc')
            ->get();

        return response()->json($announcements);
    }
}
