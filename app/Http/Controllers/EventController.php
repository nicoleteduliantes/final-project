<?php
namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    //CREATE EVENT
    public function createEvent(Request $request)
    {
        $request->validate([
            'event_name' => 'required|string|max:255',
            'date'       => 'required|date',
            'location'   => 'required|string',
            'description' => 'nullable|string',
            'event_logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', 
        ]);

       
        $user = $request->user();
        $org = Organization::find($user->org_id);

        $org_name = $org ? $org->org_name : 'Official Host'; 

        $event = Event::create([
            'org_id'      => $user->org_id,
            'event_name'  => $request->event_name,
            'event_host'  => $org_name, 
            'date'        => $request->date,
            'location'    => $request->location,
            'description' => $request->description,
            'event_logo'  => null,
        ]);

        return response()->json([
            'message' => 'Event created successfully!',
            'event'   => $event
        ], 201);
    }

    //GET EVENT
    public function index(Request $request)
{
    // 1. Double check the user exists
    $user = $request->user();
    
    if (!$user) {
        return response()->json(['error' => 'User not authenticated'], 401);
    }

    // 2. Fetch the events
    try {
        $events = Event::where('org_id', $user->org_id)
                       ->orderBy('date', 'asc')
                       ->get();

        return response()->json($events);
    } catch (\Exception $e) {
        // This will show the real error in your logs
        return response()->json(['error' => $e->getMessage()], 500);
    }
}

// Get a single event for the editor
public function show($id)
{
    return Event::findOrFail($id);
}

// Save the changes
public function update(Request $request, $id)
{
    $event = Event::findOrFail($id);
    
    // Ensure the user owns this event before updating!
    if ($event->org_id !== $request->user()->org_id) {
        return response()->json(['error' => 'Unauthorized'], 403);
    }

    $event->update([
        'event_name'  => $request->event_name,
        'date'        => $request->date,
        'location'    => $request->location,
        'description' => $request->description,
    ]);

    return response()->json(['message' => 'Updated!']);
}
}