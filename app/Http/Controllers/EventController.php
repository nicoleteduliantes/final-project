<?php
namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    //CREATE EVENT
    public function store(Request $request)
    {
        $request->validate([
            'event_name' => 'required|string|max:255',
            'date'       => 'required|date|after_or_equal:today',
            'location'   => 'required|string',
            'description' => 'nullable|string',
        ], 
            ['event_date.after_or_equal' => 'You cannot schedule an event for a past date.']
        );

       
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
        ]);

        return response()->json([
            'message' => 'Event created successfully!',
            'event'   => $event
        ], 201);
    }

    //GET ALL EVENTS
    public function index(Request $request)
    {
    
    $events = Event::where('org_id', $request->user()->org_id)
                       ->orderBy('date', 'asc')
                       ->get();

        return response()->json($events);
}

    // GET A SINGLE EVENT (for the editor)
    public function show(Request $request, $id)
    {
        //Ensures organizations only sees their own event
        return Event::where ('event_id', $id)
                    ->where('org_id', $request->user()->org_id)
                    ->findOrFail($id);
    }

    // UPDATE EVENT
    public function update(Request $request, $id)
    {
        //Ensures organizations  can edit only their own event
        $event = Event::where ('event_id', $id)
                    ->where('org_id', $request->user()->org_id)
                    ->findOrFail($id);

        $event->update([
            'event_name'  => $request->event_name,
            'date'        => $request->date,
            'location'    => $request->location,
            'description' => $request->description,
        ]);

        return response()->json(['message' => 'Updated!']);
    }

    // DELETE EVENT
    public function destroy(Request $request, $id)
    {
        $event = Event::where('event_id', $id)
                    ->where('org_id', $request->user()->org_id)
                    ->firstOrFail();

        $event->delete();

        return response()->json(['message' => 'Event deleted successfully']);
    }
}
