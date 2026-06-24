<?php

namespace App\Http\Controllers;

use App\Models\Reminder;
use App\Services\SmsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ReminderController extends Controller
{
  public function index()
{
    $user = auth()->user();

    $upcomingQuery = Reminder::with(['appointment.child', 'guardian'])
        ->where('send_datetime', '>=', now())
        ->latest();

    $logQuery = Reminder::with(['appointment.child', 'guardian'])
        ->where('send_datetime', '<', now())
        ->latest();

    /*
    |--------------------------------------------------------------------------
    | CAREGIVER FILTER
    |--------------------------------------------------------------------------
    | Caregiver should only see reminders linked to their own child.
    |--------------------------------------------------------------------------
    */

    if ($user->role === 'caregiver') {
        $upcomingQuery->whereHas('appointment.child', function ($query) use ($user) {
            $query->where('caregiver_id', $user->id);
        });

        $logQuery->whereHas('appointment.child', function ($query) use ($user) {
            $query->where('caregiver_id', $user->id);
        });
    }

    $upcoming = $upcomingQuery->get();
    $log = $logQuery->limit(20)->get();

    return view('reminders.index', compact('upcoming', 'log'));
}
    public function dispatch(Request $request, SmsService $sms)
{  
    $user = auth()->user();

    if ($user->role === 'caregiver') {
        abort(403, 'Caregivers are not allowed to dispatch SMS reminders.');
    }

    \Log::info('Dispatch due now button clicked', [
        'user_id' => $user->id,
        'role' => $user->role,
        'time' => now()->toDateTimeString(),
    ]);

    $dueReminders = Reminder::with(['guardian', 'appointment.child.facility'])
        ->where('send_datetime', '<=', now())
        ->where(function ($query) {
            $query->whereNull('delivery_status')
                ->orWhere('delivery_status', 'pending')
                ->orWhere('delivery_status', 'failed');
        })
        ->get();

    \Log::info('Due reminders found', [
        'count' => $dueReminders->count(),
    ]);

    $sent = 0;
    $failed = 0;

    foreach ($dueReminders as $reminder) {
        \Log::info('Trying to send reminder', [
            'reminder_id' => $reminder->reminder_id,
            'phone' => $reminder->guardian->phone_number ?? null,
        ]);

        $wasSent = $sms->send($reminder);

        if ($wasSent) {
            $sent++;
        } else {
            $failed++;
        }
    }

    return redirect()
        ->route('reminders.index')
        ->with('success', "Dispatch complete. Sent: {$sent}. Failed: {$failed}.");
}
}