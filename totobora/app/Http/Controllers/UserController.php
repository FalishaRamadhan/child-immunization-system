<?php

namespace App\Http\Controllers;
// Admin-only user management with activate and deactivate account controls

use App\Models\User;
use App\Models\Facility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Password as PasswordBroker;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('facility')
            ->where('role', 'healthcare_worker')
            ->orderBy('is_active', 'desc')
            ->orderBy('first_name')
            ->get();

        return view('users.index', compact('users'));
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

        return redirect()->route('users.index')
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
            'first_name'  => ['required', 'string', 'max:50'],
            'last_name'   => ['required', 'string', 'max:50'],
            'email'       => ['required', 'email', 'unique:users,email,' . $user->id],
            'facility_id' => ['required', 'exists:facilities,facility_id'],
            'role'        => ['required', 'in:admin,healthcare_worker'],
            'password'    => ['nullable', 'confirmed', Password::min(8)],
        ]);

        $data = [
            'first_name'  => $validated['first_name'],
            'last_name'   => $validated['last_name'],
            'name'        => $validated['first_name'] . ' ' . $validated['last_name'],
            'email'       => $validated['email'],
            'facility_id' => $validated['facility_id'],
            'role'        => $validated['role'],
        ];

        if (!empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        $user->update($data);

        return redirect()->route('users.index')
            ->with('success', 'User updated successfully.');
    }

    public function sendResetLink(User $user)
    {
        $status = Password::sendResetLink([
            'email' => $user->email
        ]);

        if ($status === Password::RESET_LINK_SENT) {
            return back()->with('success', 'Password reset link sent to ' . $user->email);
        }

        return back()->withErrors([
            'email' => 'Unable to send reset link. Please try again.'
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
}