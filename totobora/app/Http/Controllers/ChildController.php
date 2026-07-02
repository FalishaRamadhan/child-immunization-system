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
        $gender = $request->input('gender');
        $dateFilter = $request->input('date_filter');
        $sort = $request->input('sort', 'latest');

        $childrenQuery = Child::with([
            'guardians',
            'immunizations',
            'appointments',
            'growthMeasurements'
        ]);

        if (!empty($search)) {
            $childrenQuery->where(function ($query) use ($search) {
                $query->where('first_name', 'LIKE', "%{$search}%")
                    ->orWhere('last_name', 'LIKE', "%{$search}%")
                    ->orWhere('unique_child', 'LIKE', "%{$search}%")
                    ->orWhere('gender', 'LIKE', "%{$search}%")
                    ->orWhereHas('guardians', function ($g) use ($search) {
                        $g->where('first_name', 'LIKE', "%{$search}%")
                          ->orWhere('last_name', 'LIKE', "%{$search}%")
                          ->orWhere('phone_number', 'LIKE', "%{$search}%")
                          ->orWhere('relationship', 'LIKE', "%{$search}%");
                    });
            });
        }

        if (!empty($gender)) {
            $childrenQuery->where('gender', $gender);
        }

        switch ($dateFilter) {
            case 'today':
                $childrenQuery->whereDate('created_at', today());
                break;
            case '7days':
                $childrenQuery->where('created_at', '>=', now()->subDays(7));
                break;
            case '30days':
                $childrenQuery->where('created_at', '>=', now()->subDays(30));
                break;
            case 'year':
                $childrenQuery->whereYear('created_at', now()->year);
                break;
        }

        switch ($sort) {
            case 'oldest':
                $childrenQuery->oldest();
                break;
            case 'name_asc':
                $childrenQuery->orderBy('first_name')->orderBy('last_name');
                break;
            case 'name_desc':
                $childrenQuery->orderByDesc('first_name')->orderByDesc('last_name');
                break;
            default:
                $childrenQuery->latest();
                break;
        }

        if ($user->role === 'healthcare_worker') {
            $childrenQuery->where('facility_id', $user->facility_id);
        }

        $children = $childrenQuery->paginate(10)->appends($request->query());

        return view('children.index', compact(
            'children',
            'search',
            'gender',
            'dateFilter',
            'sort'
        ));
    }

    public function create()
    {
        $facilities = Facility::orderBy('name')->get();
        return view('children.create', compact('facilities'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:15'],
            'last_name' => ['required', 'string', 'max:15'],
            'date_of_birth' => ['required', 'date', 'before:today'],
            'gender' => ['required', 'in:Male,Female'],
            'birth_weight' => ['nullable', 'numeric', 'min:0.5', 'max:10'],
            'guardian_first_name' => ['required', 'string', 'max:15'],
            'guardian_last_name' => ['required', 'string', 'max:15'],
            'phone_number' => ['required', 'string', 'max:15'],
            'email' => ['nullable', 'email', 'max:100'],
            'relationship' => ['required'],
            'facility_id' => ['required', 'exists:facilities,facility_id'],
        ]);

        DB::transaction(function () use ($validated) {

            $child = Child::create([
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'date_of_birth' => $validated['date_of_birth'],
                'gender' => $validated['gender'],
                'birth_weight' => $validated['birth_weight'] ?? null,
                'facility_id' => $validated['facility_id'],
                'unique_child' => md5($validated['first_name'].$validated['last_name']),
            ]);

            $child->update([
                'unique_child' => 'CH-' . str_pad($child->child_id, 5, '0', STR_PAD_LEFT),
            ]);

            Guardian::create([
                'child_id' => $child->child_id,
                'first_name' => $validated['guardian_first_name'],
                'last_name' => $validated['guardian_last_name'],
                'phone_number' => $validated['phone_number'],
                'email' => $validated['email'] ?? null,
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
            'growthMeasurements',
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
            'first_name' => ['required'],
            'last_name' => ['required'],
            'date_of_birth' => ['required', 'date'],
            'gender' => ['required'],
            'birth_weight' => ['nullable'],
        ]);

        $child->update($validated);

        return redirect()->route('children.show', $child)
            ->with('success', 'Child updated successfully.');
    }

    private function authorizeAccess(Child $child): void
    {
        $user = Auth::user();

        if ($user->role === 'admin') return;

        if ($user->role === 'healthcare_worker') {
            if ($child->facility_id !== $user->facility_id) {
                abort(403);
            }
            return;
        }

        abort(403);
    }
}