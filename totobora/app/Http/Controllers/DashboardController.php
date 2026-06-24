<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
{
    $user = auth()->user();

    // ADMIN
    if ($user->role === 'admin') {
        return view('dashboard.admin', [
            'totalChildren' => \App\Models\Child::count(),
            'vaccinesThisMonth' => \App\Models\ImmunizationRecord::whereMonth('created_at', now()->month)->count(),
            'missedAppointments' => \App\Models\Appointment::where('status', 'missed')->count(),
            'remindersSent' => \App\Models\Reminder::where('delivery_status', 'sent')->count(),
        ]);
    }

    // HEALTHCARE WORKER
    if ($user->role === 'healthcare_worker') {

        $children = \App\Models\Child::where('facility_id', $user->facility_id)->get();

        return view('dashboard.worker', [
            'children' => $children,
            'totalChildren' => $children->count(),
            'vaccinesThisMonth' => \App\Models\ImmunizationRecord::whereMonth('created_at', now()->month)
                ->whereHas('child', fn($q) => $q->where('facility_id', $user->facility_id))
                ->count(),
        ]);
    }

    // CAREGIVER
    if ($user->role === 'caregiver') {

        $children = \App\Models\Child::where('caregiver_id', $user->id)->get();

        return view('dashboard.caregiver', [
            'children' => $children
        ]);
    }

    abort(403);
}
}