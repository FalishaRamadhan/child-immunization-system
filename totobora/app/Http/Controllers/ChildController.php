<?php

namespace App\Http\Controllers;

use App\Models\Child;
use App\Models\Guardian;
use App\Models\Facility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ChildController extends Controller
{
   public function index(Request $request)
{
    $user = auth()->user();
    $search = trim($request->input('search'));

    $childrenQuery = Child::with(['guardians', 'immunizations', 'appointments', 'growthMeasurements'])
        ->latest();

    /*
    |--------------------------------------------------------------------------
    | Caregiver can only see their own child/children
    |--------------------------------------------------------------------------
    */

    if ($user->role === 'caregiver') {
        $childrenQuery->where('caregiver_id', $user->id);
    }

    /*
    |--------------------------------------------------------------------------
    | Search child records
    |--------------------------------------------------------------------------
    */

    if ($search) {
        $childrenQuery->where(function ($query) use ($search) {
            $query->where('first_name', 'LIKE', "%{$search}%")
                ->orWhere('last_name', 'LIKE', "%{$search}%")
                ->orWhere('unique_child', 'LIKE', "%{$search}%")
                ->orWhere('gender', 'LIKE', "%{$search}%")
                ->orWhereHas('guardians', function ($guardianQuery) use ($search) {
                    $guardianQuery->where('first_name', 'LIKE', "%{$search}%")
                        ->orWhere('last_name', 'LIKE', "%{$search}%")
                        ->orWhere('phone_number', 'LIKE', "%{$search}%")
                        ->orWhere('relationship', 'LIKE', "%{$search}%");
                });
        });
    }

    $children = $childrenQuery
        ->paginate(10)
        ->appends(['search' => $search]);

    return view('children.index', compact('children', 'search'));
}

    public function create()
    {
        $this->authorizeRole();

        $facilities = Facility::orderBy('name')->get();

        return view('children.create', compact('facilities'));
    }

    public function store(Request $request)
{
    $validated = $request->validate([
        'first_name'          => ['required', 'string', 'max:50'],
        'last_name'           => ['required', 'string', 'max:50'],
        'date_of_birth'       => ['required', 'date', 'before:today'],
        'gender'              => ['required', 'in:Male,Female'],
        'birth_weight'        => ['nullable', 'numeric', 'min:0.5', 'max:10'],

        'guardian_first_name' => ['required', 'string', 'max:50'],
        'guardian_last_name'  => ['required', 'string', 'max:50'],
        'phone_number'        => ['required', 'string', 'max:15', 'unique:guardians,phone_number'],

        // This email must belong to a caregiver user account
        'email'               => ['required', 'email', 'max:100', 'exists:users,email'],

        'relationship'        => ['required', 'in:Mother,Father,Grandparent,Aunt/Uncle,Sibling,Other'],
        'facility_id'         => ['required', 'exists:facilities,facility_id'],
    ]);

    $caregiver = \App\Models\User::where('email', $validated['email'])
        ->where('role', 'caregiver')
        ->first();

    if (!$caregiver) {
        return back()
            ->withErrors(['email' => 'The email must belong to a registered caregiver account.'])
            ->withInput();
    }

    DB::transaction(function () use ($validated, $caregiver) {

        $uniqueKey = strtolower($validated['first_name'])
            . strtolower($validated['last_name'])
            . $validated['date_of_birth']
            . $validated['facility_id'];

        $child = Child::create([
            'first_name'    => $validated['first_name'],
            'last_name'     => $validated['last_name'],
            'date_of_birth' => $validated['date_of_birth'],
            'gender'        => $validated['gender'],
            'birth_weight'  => $validated['birth_weight'] ?? null,
            'facility_id'   => $validated['facility_id'],

            // Correct: assign child to caregiver user
            'caregiver_id'  => $caregiver->id,

            'unique_child'  => md5($uniqueKey),
        ]);

        $child->update([
            'unique_child' => 'CH-' . str_pad($child->child_id, 5, '0', STR_PAD_LEFT),
        ]);

        Guardian::create([
            'child_id'     => $child->child_id,
            'first_name'   => $validated['guardian_first_name'],
            'last_name'    => $validated['guardian_last_name'],
            'phone_number' => $validated['phone_number'],
            'email'        => $validated['email'],
            'relationship' => $validated['relationship'],
        ]);
    });

    return redirect()->route('children.index')
        ->with('success', 'Child registered successfully.');
}
    public function show(Child $child)
    {
        $this->authorizeAccess($child);

        $child->load([
            'guardians',
            'immunizations',
            'appointments',
            'growthMeasurements'
        ]);

        return view('children.show', compact('child'));
    }

    public function edit(Child $child)
    {
        $this->authorizeAccess($child);

        return view('children.edit', compact('child'));
    }

    public function update(Request $request, Child $child)
    {
        $this->authorizeAccess($child);

        $validated = $request->validate([
            'first_name'    => ['required', 'string', 'max:50'],
            'last_name'     => ['required', 'string', 'max:50'],
            'date_of_birth' => ['required', 'date', 'before:today'],
            'gender'        => ['required', 'in:Male,Female'],
            'birth_weight'  => ['nullable', 'numeric', 'min:0.5', 'max:10'],
        ]);

        $child->update($validated);

        return redirect()->route('children.show', $child)
            ->with('success', 'Child updated successfully.');
    }

    private function authorizeAccess(Child $child): void
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            return;
        }

        if ($user->role === 'healthcare_worker') {
            if ($child->facility_id !== $user->facility_id) {
                abort(403);
            }
            return;
        }

        if ($user->role === 'caregiver') {
            if ($child->caregiver_id !== $user->id) {
                abort(403);
            }
            return;
        }

        abort(403);
    }

    private function authorizeRole(): void
    {
        $user = Auth::user();

        if (!in_array($user->role, ['admin', 'healthcare_worker'])) {
            abort(403);
        }
    }
}