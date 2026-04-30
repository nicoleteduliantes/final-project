use App\Models\Membership;
use App\Models\ApplicationDetail;
use Illuminate\Support\Facades\DB;

public function store(Request $request)
{
    // Start a transaction so if one fails, both fail (no partial data)
    return DB::transaction(function () use ($request) {
        
        // 1. Create the Membership entry first
        $membership = Membership::create([
            'org_id'     => $request->org_id,
            'student_id' => auth()->user()->student_id,
            'position'   => 'Applicant', // Default status
            'status'     => 'Pending',   // Or whatever your default is
        ]);

        // 2. Use the newly created $membership->membership_id for the details
        $application = ApplicationDetail::create([
            'membership_id'       => $membership->membership_id,
            'cover_letter'        => $request->cover_letter,
            'skills'              => $request->skills,
            'previous_experience' => $request->previous_experience,
            'applied_committee'   => $request->applied_committee,
            'date_applied'        => $request->date_applied,
        ]);

        return response()->json([
            'message' => 'Application and Membership created!',
            'data'    => $application
        ], 201);
    });
}