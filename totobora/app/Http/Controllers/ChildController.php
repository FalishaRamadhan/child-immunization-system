<?php

namespace App\Http\Controllers;
// Manages child registration, guardian management, CH-XXXXX ID generation and immunization dashboard

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
     Search child records
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
        $facilities = \App\Models\Facility::orderBy('name')->get();
        return view('children.create', compact('facilities'));
    }

    public function store(Request $request)
{
    $validated = $request->validate([
        'first_name'          => ['required', 'string', 'max:15'],
        'last_name'           => ['required', 'string', 'max:15'],
        'date_of_birth'       => ['required', 'date', 'before:today'],
        'gender'              => ['required', 'in:Male,Female'],
        'birth_weight'        => ['nullable', 'numeric', 'min:0.5', 'max:10'],
        'guardian_first_name' => ['required', 'string', 'max:15'],
        'guardian_last_name'  => ['required', 'string', 'max:15'],
        'phone_number'        => ['required', 'string', 'max:15'],
        'email'               => ['nullable', 'email', 'max:100'],
        'relationship'        => ['required', 'in:Mother,Father,Grandparent,Aunt/Uncle,Sibling,Other'],
        'facility_id'         => ['required', 'exists:facilities,facility_id'],
    ]);

    DB::transaction(function () use ($validated) {

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
            'unique_child'  => md5($uniqueKey),
        ]);

        $child->update([
            'unique_child' => 'CH-' . str_pad($child->child_id, 5, '0', STR_PAD_LEFT),
        ]);

        // Check if guardian with this phone already exists
        $existingGuardian = Guardian::where('phone_number', $validated['phone_number'])->first();

        Guardian::create([
            'child_id'     => $child->child_id,
            'first_name'   => $existingGuardian->first_name ?? $validated['guardian_first_name'],
            'last_name'    => $existingGuardian->last_name  ?? $validated['guardian_last_name'],
            'phone_number' => $validated['phone_number'],
            'email'        => $existingGuardian->email       ?? $validated['email'] ?? null,
            'relationship' => $validated['relationship'],
        ]);
    });

    return redirect()->route('children.index')
        ->with('success', 'Child registered successfully.');

    AuditService::log('register_child', "Registered child {$validated['first_name']} {$validated['last_name']}", 'Child', $child->child_id ?? null);
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
            'first_name'    => ['required', 'string', 'max:15'],
            'last_name'     => ['required', 'string', 'max:15'],
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

    }

    private function authorizeRole(): void
    {
        $user = Auth::user();

        if (!in_array($user->role, ['admin', 'healthcare_worker'])) {
            abort(403);
        }
    }
}
