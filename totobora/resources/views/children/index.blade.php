@extends('layouts.app')

@section('title', 'Children Records')

@section('content')

@php
    $user = auth()->user();
    $canManageChildren = in_array($user->role, ['admin', 'healthcare_worker'], true);
    $isCaregiver = $user->role === 'caregiver';
    $searchValue = $search ?? request('search');
@endphp

<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-xl font-semibold text-gray-800">
            {{ $isCaregiver ? 'My Child Record' : 'Children Records' }}
        </h2>
        <p class="text-sm text-gray-500 mt-1">
            {{ $isCaregiver ? 'View your child immunization record.' : 'Search, view, and manage registered children.' }}
        </p>
    </div>

    @if($canManageChildren)
        <a href="{{ route('children.create') }}"
           class="bg-green-600 hover:bg-green-700 text-white text-sm font-medium
                  px-4 py-2 rounded-lg transition-colors">
            + Register Child
        </a>
    @endif
</div>

{{-- Search --}}
<div class="bg-white rounded-xl border border-gray-200 p-5 mb-6">
    <form method="GET" action="{{ route('children.index') }}">
        <div class="flex flex-col md:flex-row gap-3">
            <input type="text"
                   name="search"
                   value="{{ $searchValue }}"
                   placeholder="Search by child name, child ID, guardian name, or phone number"
                   class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm
                          focus:ring-green-500 focus:border-green-500">

            <div class="flex gap-2">
                <button type="submit"
                        class="bg-green-600 hover:bg-green-700 text-white text-sm font-medium
                               px-5 py-2 rounded-lg transition-colors">
                    Search
                </button>

                @if($searchValue)
                    <a href="{{ route('children.index') }}"
                       class="border border-gray-300 text-gray-600 text-sm font-medium
                              px-5 py-2 rounded-lg hover:bg-gray-50 transition-colors">
                        Clear
                    </a>
                @endif
            </div>
        </div>
    </form>
</div>

{{-- Children Table --}}
<div class="bg-white rounded-xl border border-gray-200 overflow-hidden">

    <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
        <h3 class="font-medium text-gray-800">
            {{ $isCaregiver ? 'Child Details' : 'Registered Children' }}
        </h3>

        <p class="text-sm text-gray-400">
            {{ $children->count() }} record{{ $children->count() === 1 ? '' : 's' }}
        </p>
    </div>

    <table class="w-full text-sm">
        <thead>
            <tr class="bg-gray-50 border-b border-gray-100 text-left">
                <th class="px-5 py-3 font-medium text-gray-600">Child</th>
                <th class="px-5 py-3 font-medium text-gray-600">Child ID</th>
                <th class="px-5 py-3 font-medium text-gray-600">Age</th>
                <th class="px-5 py-3 font-medium text-gray-600">Gender</th>
                <th class="px-5 py-3 font-medium text-gray-600">Guardian</th>
                <th class="px-5 py-3 font-medium text-gray-600">Phone</th>
                <th class="px-5 py-3 font-medium text-gray-600 text-right">Action</th>
            </tr>
        </thead>

        <tbody class="divide-y divide-gray-100">
            @forelse($children as $child)
                @php
                    $guardian = $child->guardians->first();
                @endphp

                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-5 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center
                                        text-green-700 font-semibold text-sm">
                                {{ strtoupper(substr($child->first_name, 0, 1)) }}{{ strtoupper(substr($child->last_name, 0, 1)) }}
                            </div>

                            <div>
                                <p class="font-medium text-gray-800">
                                    {{ $child->first_name }} {{ $child->last_name }}
                                </p>
                                <p class="text-xs text-gray-400">
                                    Registered child
                                </p>
                            </div>
                        </div>
                    </td>

                    <td class="px-5 py-4 text-gray-600">
                        {{ $child->unique_child }}
                    </td>

                    <td class="px-5 py-4 text-gray-600">
                        {{ $child->getAgeLabel() }}
                    </td>

                    <td class="px-5 py-4 text-gray-600">
                        {{ $child->gender }}
                    </td>

                    <td class="px-5 py-4 text-gray-600">
                        @if($guardian)
                            {{ $guardian->first_name }} {{ $guardian->last_name }}
                        @else
                            —
                        @endif
                    </td>

                    <td class="px-5 py-4 text-gray-600">
                        {{ $guardian->phone_number ?? '—' }}
                    </td>

                    <td class="px-5 py-4 text-right">
                        <a href="{{ route('children.show', $child->child_id) }}"
                           class="text-green-600 hover:underline text-sm font-medium">
                            View
                        </a>

                        @if($canManageChildren)
                            <a href="{{ route('children.edit', $child->child_id) }}"
                               class="ml-4 text-gray-500 hover:text-gray-700 hover:underline text-sm">
                                Edit
                            </a>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="px-5 py-10 text-center text-gray-400">
                        @if($searchValue)
                            No child records found for "{{ $searchValue }}".
                        @else
                            No child records found.
                        @endif
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Pagination --}}
@if(method_exists($children, 'links'))
    <div class="mt-6">
        {{ $children->links() }}
    </div>
@endif

@endsection