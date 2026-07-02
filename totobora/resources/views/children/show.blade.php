@extends('layouts.app')

@section('title', $child->first_name . ' ' . $child->last_name)

@section('content')

@php
    $canManageChild = in_array(auth()->user()->role, ['admin', 'healthcare_worker'], true);
@endphp

{{-- Profile header --}}
<div class="flex items-center gap-4 mb-8">
    <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center
                text-green-700 font-semibold text-lg">
        {{ strtoupper(substr($child->first_name, 0, 1)) }}{{ strtoupper(substr($child->last_name, 0, 1)) }}
    </div>

    <div>
        <h2 class="text-xl font-semibold text-gray-800">
            {{ $child->first_name }} {{ $child->last_name }}
        </h2>

        <p class="text-sm text-gray-500">
            {{ $child->unique_child }} &middot;
            Age: {{ $child->getAgeLabel() }} &middot;
            {{ $child->gender }}
        </p>
    </div>

    @if($canManageChild)
        <a href="{{ route('children.edit', $child) }}"
           class="ml-auto text-sm border border-gray-300 px-4 py-2 rounded-lg
                  hover:bg-gray-50 text-gray-600 transition-colors">
            Edit
        </a>
    @endif
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">

    {{-- Guardian --}}
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-3">
            Guardian
        </h3>

        @forelse ($child->guardians as $guardian)
            <p class="font-medium text-gray-800 text-sm">
                {{ $guardian->first_name }} {{ $guardian->last_name }}
            </p>

            <p class="text-sm text-gray-500 mt-1">
                {{ $guardian->phone_number }}
            </p>

            <p class="text-sm text-gray-400">
                {{ $guardian->relationship }}
            </p>
        @empty
            <p class="text-sm text-gray-400">
                No guardian on record.
            </p>
        @endforelse
    </div>

    {{-- Immunization summary --}}
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-3">
            Immunizations
        </h3>

        <p class="text-3xl font-semibold text-gray-800">
            {{ $child->immunizations->count() }}
        </p>

        <p class="text-sm text-gray-500 mt-1">
            vaccines recorded
        </p>
    </div>

    {{-- Growth summary --}}
    <a href="{{ route('growth.chart', $child) }}"
       class="bg-white rounded-xl border border-gray-200 p-5 block hover:border-green-300 transition-colors">
        <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-3">
            Growth
        </h3>

        @if ($child->growthMeasurements->count())
            @php
                $latest = $child->growthMeasurements->last();
            @endphp

            <p class="text-sm text-gray-800">
                {{ $latest->weight_kg }} kg · {{ $latest->height_cm }} cm
            </p>

            <p class="text-xs text-gray-400 mt-1">
                {{ \Carbon\Carbon::parse($latest->date_measured)->format('d M Y') }}
            </p>

            @if($latest->isFlagged())
                <p class="text-xs text-red-600 mt-2 font-medium">
                    ⚠ Flagged
                </p>
            @endif
        @else
            <p class="text-sm text-gray-400">
                No measurements yet.
            </p>

            @if($canManageChild)
                <p class="text-xs text-green-600 mt-2">
                    + Record first →
                </p>
            @endif
        @endif
    </a>

</div>

{{-- Immunization history table --}}
<div class="mt-6 bg-white rounded-xl border border-gray-200 overflow-hidden">
    <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
        <h3 class="font-medium text-gray-800">
            Immunization history
        </h3>

        @if($canManageChild)
            <a href="{{ route('immunizations.create', $child) }}"
               class="text-sm text-green-600 hover:underline">
                + Record vaccine
            </a>
        @endif
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100 text-left">
                    <th class="px-5 py-3 font-medium text-gray-600">Vaccine</th>
                    <th class="px-5 py-3 font-medium text-gray-600">Dose</th>
                    <th class="px-5 py-3 font-medium text-gray-600">Date given</th>
                    <th class="px-5 py-3 font-medium text-gray-600">Next due</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-100">
                @forelse ($child->immunizations->sortByDesc('date_administered') as $record)
                    <tr>
                        <td class="px-5 py-3 text-gray-800">
                            {{ $record->vaccine_name }}
                        </td>

                        <td class="px-5 py-3 text-gray-600">
                            {{ $record->dose_number ?? '-' }}
                        </td>

                        <td class="px-5 py-3 text-gray-600">
                            @if($record->date_administered)
                                {{ \Carbon\Carbon::parse($record->date_administered)->format('d M Y') }}
                            @else
                                -
                            @endif
                        </td>

                        <td class="px-5 py-3 text-gray-600">
                            @if($record->next_due_date)
                                {{ \Carbon\Carbon::parse($record->next_due_date)->format('d M Y') }}
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-5 py-8 text-center text-gray-400">
                            No immunizations recorded yet.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Appointments --}}
<div class="mt-6 bg-white rounded-xl border border-gray-200 overflow-hidden">
    <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
        <h3 class="font-medium text-gray-800">
            Appointments
        </h3>

        @if($canManageChild)
            <a href="{{ route('appointments.create', $child) }}"
               class="text-sm text-green-600 hover:underline">
                + Schedule
            </a>
        @endif
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100 text-left">
                    <th class="px-5 py-3 font-medium text-gray-600">Date</th>
                    <th class="px-5 py-3 font-medium text-gray-600">Vaccine due</th>
                    <th class="px-5 py-3 font-medium text-gray-600">Status</th>

                    @if($canManageChild)
                        <th class="px-5 py-3 font-medium text-gray-600">Actions</th>
                    @endif
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-100">
                @forelse($child->appointments->sortByDesc('scheduled_date') as $appt)
                    @php
                        $badge = $appt->getStatusBadge();
                        $status = strtolower($appt->status);
                    @endphp

                    <tr>
                        <td class="px-5 py-3 text-gray-800">
                            {{ $appt->scheduled_date->format('d M Y') }}

                            @if($appt->isOverdue() && $status === 'scheduled')
                                <span class="text-xs text-red-500 ml-1">
                                    Overdue
                                </span>
                            @endif
                        </td>

                        <td class="px-5 py-3 text-gray-600">
                            {{ $appt->vaccine_due }}
                        </td>

                        <td class="px-5 py-3">
                            <span class="px-2 py-1 rounded-full text-xs font-medium {{ $badge['class'] }}">
                                {{ $badge['label'] }}
                            </span>
                        </td>

                        @if($canManageChild)
                            <td class="px-5 py-3">
                                @if($status === 'attended')
                                    <span class="text-xs font-semibold text-green-600">
                                        Vaccine recorded
                                    </span>
                                @else
                                    <div class="flex items-center gap-3">
                                        <form method="POST"
                                              action="{{ route('appointments.attend', $appt) }}"
                                              onsubmit="return confirm('Mark this appointment as attended and record the vaccine in immunization history?');">
                                            @csrf
                                            @method('PATCH')

                                            <button type="submit"
                                                    class="bg-green-600 hover:bg-green-700 text-white text-xs font-semibold
                                                           px-3 py-2 rounded-lg transition-colors">
                                                Mark attended
                                            </button>
                                        </form>

                                        @if($status === 'scheduled')
                                            <form method="POST"
                                                  action="{{ route('appointments.miss', $appt) }}"
                                                  onsubmit="return confirm('Mark this appointment as missed?');">
                                                @csrf
                                                @method('PATCH')

                                                <button type="submit"
                                                        class="text-xs text-red-500 hover:underline font-medium">
                                                    Missed
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                @endif
                            </td>
                        @endif
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ $canManageChild ? 4 : 3 }}"
                            class="px-5 py-8 text-center text-gray-400">
                            No appointments scheduled.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection