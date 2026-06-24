@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

<!-- HEADER (LOGO FIXED HERE) -->
<div class="mb-6 flex items-center justify-between">

    <div class="flex items-center gap-4">

        <!-- REAL LOGO (NO EMOJI) -->
        <img src="{{ asset('images/totobora-logo.png') }}"
             class="w-12 h-12 object-contain"
             alt="TotoBora Logo">

        <div>
            <h2 class="text-2xl font-bold text-gray-800">Dashboard</h2>
            <p class="text-sm text-gray-500">
                Welcome back, {{ auth()->user()->name }}
            </p>
        </div>

    </div>

    <div class="text-right">
        <p class="text-sm font-semibold text-gray-700">
            {{ ucfirst(auth()->user()->role) }}
        </p>
        <p class="text-xs text-gray-400">Active Session</p>
    </div>

</div>

<!-- KPI ROW (FORCED SIDE BY SIDE) -->
<div class="grid grid-cols-4 gap-4 mb-8">

    <!-- CHILDREN -->
    <div class="bg-white border rounded-xl p-5 shadow-sm hover:shadow-lg transition hover:-translate-y-1 border-green-200">
        <p class="text-xs text-gray-500 uppercase">Children</p>
        <p class="text-3xl font-bold text-green-600 mt-2">
            {{ $totalChildren }}
        </p>
    </div>

    <!-- VACCINES -->
    <div class="bg-white border rounded-xl p-5 shadow-sm hover:shadow-lg transition hover:-translate-y-1 border-blue-200">
        <p class="text-xs text-gray-500 uppercase">Vaccines</p>
        <p class="text-3xl font-bold text-blue-600 mt-2">
            {{ $vaccinesThisMonth }}
        </p>
    </div>

    <!-- APPOINTMENTS -->
    <div class="bg-white border rounded-xl p-5 shadow-sm hover:shadow-lg transition hover:-translate-y-1 border-yellow-200">
        <p class="text-xs text-gray-500 uppercase">Appointments</p>
        <p class="text-3xl font-bold text-yellow-600 mt-2">
            {{ $missedAppointments }}
        </p>
    </div>

    <!-- REMINDERS -->
    <div class="bg-white border rounded-xl p-5 shadow-sm hover:shadow-lg transition hover:-translate-y-1 border-purple-200">
        <p class="text-xs text-gray-500 uppercase">Reminders</p>
        <p class="text-3xl font-bold text-purple-600 mt-2">
            {{ $remindersSent }}
        </p>
    </div>

</div>

<!-- QUICK ACTIONS (COLORED LIKE REPORTS STYLE) -->
<div class="bg-white border rounded-xl p-6 shadow-sm">

    <h3 class="font-semibold text-gray-700 mb-4">Quick Actions</h3>

    <div class="flex gap-3 flex-wrap">

        <a href="{{ route('children.index') }}"
           class="px-4 py-2 rounded-lg bg-green-600 text-white hover:bg-green-700 transition">
            View Children
        </a>

        <a href="{{ route('reminders.index') }}"
           class="px-4 py-2 rounded-lg bg-green-600 text-white hover:bg-blue-700 transition">
            View Reminders
        </a>

        @if(auth()->user()->role === 'admin')
        <a href="{{ route('reports.index') }}"
           class="px-4 py-2 rounded-lg bg-green-600 text-white hover:bg-purple-700 transition">
            View Reports
        </a>
        @endif

    </div>

</div>

@endsection