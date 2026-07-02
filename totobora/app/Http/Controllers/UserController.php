<?php

namespace App\Http\Controllers;

// Admin-only user management with activate and deactivate account controls

use App\Mail\UserTemporaryPasswordMail;
use App\Models\Facility;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password as PasswordBroker;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('facility');

        /*
        |--------------------------------------------------------------------------
        | Keyword Search
        |--------------------------------------------------------------------------
        | Searches name, first name, last name, email, role, and facility details.
        */
        if ($request->filled('q')) {
            $search = trim($request->q);

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('role', 'like', "%{$search}%")
                    ->orWhereHas('facility', function ($facilityQuery) use ($search) {
                        $facilityQuery->where('name', 'like', "%{$search}%")
                            ->orWhere('location', 'like', "%{$search}%");
                    });
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Category / Role Filter
        |--------------------------------------------------------------------------
        */
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        /*
        |--------------------------------------------------------------------------
        | Facility Filter
        |--------------------------------------------------------------------------
        */
        if ($request->filled('facility_id')) {
            $query->where('facility_id', $request->facility_id);
        }

        /*
        |--------------------------------------------------------------------------
        | Status Filter
        |--------------------------------------------------------------------------
        */
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            }

            if ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Date Filters
        |--------------------------------------------------------------------------
        | Filters users by registration date.
        */
        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        /*
        |--------------------------------------------------------------------------
        | Sorting
        |--------------------------------------------------------------------------
        | Supports latest, oldest, relevance, and name ordering.
        */
        match ($request->get('sort', 'latest')) {
            'oldest' => $query->oldest(),

            'name_asc' => $query
                ->orderBy('first_name')
                ->orderBy('last_name'),

            'name_desc' => $query
                ->orderByDesc('first_name')
                ->orderByDesc('last_name'),

            'relevance' => $request->filled('q')
                ? $query->orderByRaw(
                    "CASE
                        WHEN email = ? THEN 1
                        WHEN name LIKE ? THEN 2
                        WHEN first_name LIKE ? THEN 3
                        WHEN last_name LIKE ? THEN 4
                        WHEN email LIKE ? THEN 5
                        ELSE 6
                    END",
                    [
                        $request->q,
                        "{$request->q}%",
                        "{$request->q}%",
                        "{$request->q}%",
                        "%{$request->q}%",
                    ]
                )
                : $query->latest(),

            default => $query->latest(),
        };

        $users = $query->paginate(10)->withQueryString();

        $facilities = Facility::orderBy('name')->get();

        return view('users.index', compact('users', 'facilities'));
    }

    public function create()
    {
        $facilities = Facility::orderBy('name')->get();

        return view('users.create', compact('facilities'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name'  => ['required', 'string', 'max:50'],
            'last_name'   => ['required', 'string', 'max:50'],
            'email'       => ['required', 'email', 'unique:users,email'],
            'password'    => ['required', 'confirmed', Password::min(8)],
            'facility_id' => ['required', 'exists:facilities,facility_id'],
            'role'        => ['required', 'in:admin,healthcare_worker'],
        ]);

        User::create([
            'name'        => $validated['first_name'] . ' ' . $validated['last_name'],
            'first_name'  => $validated['first_name'],
            'last_name'   => $validated['last_name'],
            'email'       => $validated['email'],
            'password'    => Hash::make($validated['password']),
            'role'        => $validated['role'],
            'facility_id' => $validated['facility_id'],
            'is_active'   => true,
        ]);

        return redirect()
            ->route('users.index')
            ->with('success', 'User created successfully.');
    }

    public function edit(User $user)
    {
        $facilities = Facility::orderBy('name')->get();

        return view('users.edit', compact('user', 'facilities'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'first_name'        => ['required', 'string', 'max:50'],
            'last_name'         => ['required', 'string', 'max:50'],
            'email'             => ['required', 'email', 'unique:users,email,' . $user->id],
            'facility_id'       => ['required', 'exists:facilities,facility_id'],
            'role'              => ['required', 'in:admin,healthcare_worker'],
            'generate_password' => ['nullable', 'boolean'],
        ]);

        $data = [
            'first_name'  => $validated['first_name'],
            'last_name'   => $validated['last_name'],
            'name'        => $validated['first_name'] . ' ' . $validated['last_name'],
            'email'       => $validated['email'],
            'facility_id' => $validated['facility_id'],
            'role'        => $validated['role'],
        ];

        $plainPassword = null;

        if ($request->boolean('generate_password')) {
            $plainPassword = $this->generateSecurePassword();

            $data['password'] = Hash::make($plainPassword);
        }

        $user->update($data);

        if ($plainPassword) {
            Mail::to($user->email)->send(
                new UserTemporaryPasswordMail($user, $plainPassword)
            );

            return redirect()
                ->route('users.index')
                ->with('success', 'User updated successfully. A new password has been sent to their email.');
        }

        return redirect()
            ->route('users.index')
            ->with('success', 'User updated successfully.');
    }

    public function sendResetLink(User $user)
    {
        $status = PasswordBroker::sendResetLink([
            'email' => $user->email,
        ]);

        if ($status === PasswordBroker::RESET_LINK_SENT) {
            return back()->with('success', 'Password reset link sent to ' . $user->email);
        }

        return back()->withErrors([
            'email' => 'Unable to send reset link. Please try again.',
        ]);
    }

    public function deactivate(User $user)
    {
        $user->update(['is_active' => false]);

        return back()->with('success', $user->first_name . ' has been deactivated.');
    }

    public function reactivate(User $user)
    {
        $user->update(['is_active' => true]);

        return back()->with('success', $user->first_name . ' has been reactivated.');
    }

    public function activate(User $user)
    {
        return $this->reactivate($user);
    }

    private function generateSecurePassword(): string
    {
        return Str::random(8) . random_int(10, 99) . '@Tb';
    }
}