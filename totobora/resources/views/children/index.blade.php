@extends('layouts.app')

@section('title', 'Children Records')

@section('content')

@php
    $user = auth()->user();
    $canManageChildren = in_array($user->role, ['admin', 'healthcare_worker'], true);

    $searchValue = $search ?? request('search');
    $genderValue = $gender ?? request('gender');
    $dateFilterValue = $dateFilter ?? request('date_filter');
    $sortValue = $sort ?? request('sort', 'latest');

    $hasFilters = $searchValue || $genderValue || $dateFilterValue || $sortValue !== 'latest';
@endphp

<div class="flex items-center justify-between mb-8">
    <div>
        <h2 class="text-2xl font-semibold text-gray-800">
            Children Records
        </h2>
        <p class="text-sm text-gray-500 mt-2">
            Manage registered children.
        </p>
    </div>

    @if($canManageChildren)
        <a href="{{ route('children.create') }}"
           class="bg-green-600 hover:bg-green-700 text-white text-sm font-semibold
                  px-5 py-3 rounded-lg transition-colors shadow-sm">
            + Register Child
        </a>
    @endif
</div>

{{-- Search and Filters --}}
<div class="bg-white rounded-xl border border-gray-200 p-6 md:p-7 mb-8 shadow-sm">
    <form method="GET" action="{{ route('children.index') }}">

        <div class="flex items-center justify-between mb-6">
            <h3 class="text-base font-semibold text-gray-800">
                Search Records
            </h3>

            @if($hasFilters)
                <a href="{{ route('children.index') }}"
                   class="text-sm text-green-700 hover:text-green-800 hover:underline font-medium">
                    Clear all filters
                </a>
            @endif
        </div>

        {{-- Filter Row --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 items-end">

            {{-- Keyword Search --}}
            <div class="lg:col-span-2">
                <label for="search" class="block text-sm font-medium text-gray-700 mb-2">
                    Keyword Search
                </label>

                <input type="text"
                       id="search"
                       name="search"
                       value="{{ $searchValue }}"
                       placeholder="Search by child name, child ID, guardian, or phone"
                       class="w-full h-12 border border-gray-300 rounded-lg px-4 text-sm
                              focus:ring-2 focus:ring-green-500 focus:border-green-500">
            </div>

            {{-- Sort Records --}}
            <div>
                <label for="sort" class="block text-sm font-medium text-gray-700 mb-2">
                    Sort Records
                </label>

                <select id="sort"
                        name="sort"
                        class="w-full h-12 border border-gray-300 rounded-lg px-4 text-sm
                               focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    <option value="latest" {{ $sortValue === 'latest' ? 'selected' : '' }}>
                        Latest First
                    </option>
                    <option value="oldest" {{ $sortValue === 'oldest' ? 'selected' : '' }}>
                        Oldest First
                    </option>
                    <option value="name_asc" {{ $sortValue === 'name_asc' ? 'selected' : '' }}>
                        Name A - Z
                    </option>
                    <option value="name_desc" {{ $sortValue === 'name_desc' ? 'selected' : '' }}>
                        Name Z - A
                    </option>
                </select>
            </div>

            {{-- Gender Filter --}}
            <div>
                <label for="gender" class="block text-sm font-medium text-gray-700 mb-2">
                    Gender
                </label>

                <select id="gender"
                        name="gender"
                        class="w-full h-12 border border-gray-300 rounded-lg px-4 text-sm
                               focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    <option value="">All Genders</option>
                    <option value="Male" {{ $genderValue === 'Male' ? 'selected' : '' }}>
                        Male
                    </option>
                    <option value="Female" {{ $genderValue === 'Female' ? 'selected' : '' }}>
                        Female
                    </option>
                </select>
            </div>

            {{-- Registration Date Filter --}}
            <div>
                <label for="date_filter" class="block text-sm font-medium text-gray-700 mb-2">
                    Registration Date
                </label>

                <select id="date_filter"
                        name="date_filter"
                        class="w-full h-12 border border-gray-300 rounded-lg px-4 text-sm
                               focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    <option value="">All Dates</option>
                    <option value="today" {{ $dateFilterValue === 'today' ? 'selected' : '' }}>
                        Registered Today
                    </option>
                    <option value="7days" {{ $dateFilterValue === '7days' ? 'selected' : '' }}>
                        Last 7 Days
                    </option>
                    <option value="30days" {{ $dateFilterValue === '30days' ? 'selected' : '' }}>
                        Last 30 Days
                    </option>
                    <option value="year" {{ $dateFilterValue === 'year' ? 'selected' : '' }}>
                        This Year
                    </option>
                </select>
            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="flex flex-wrap gap-3 mt-6 pt-5 border-t border-gray-100">
            <button type="submit"
                    class="bg-green-600 hover:bg-green-700 text-white text-sm font-semibold
                           px-6 py-3 rounded-lg transition-colors shadow-sm">
                Search
            </button>

            @if($hasFilters)
                <a href="{{ route('children.index') }}"
                   class="border border-green-600 text-green-700 text-sm font-semibold
                          px-6 py-3 rounded-lg hover:bg-green-50 transition-colors text-center">
                    Reset Filters
                </a>
            @endif
        </div>

    </form>
</div>

{{-- Active Filter Summary --}}
@if($hasFilters)
    <div class="bg-green-50 border border-green-100 text-green-700 rounded-lg px-5 py-4 mb-8 text-sm">
        <span class="font-semibold">
            Showing filtered results
        </span>

        @if($searchValue)
            <span class="ml-1">
                for <strong>"{{ $searchValue }}"</strong>
            </span>
        @endif

        @if($genderValue)
            <span class="ml-2">
                Gender: <strong>{{ $genderValue }}</strong>
            </span>
        @endif

        @if($dateFilterValue)
            <span class="ml-2">
                Date:
                <strong>
                    @switch($dateFilterValue)
                        @case('today')
                            Registered Today
                            @break

                        @case('7days')
                            Last 7 Days
                            @break

                        @case('30days')
                            Last 30 Days
                            @break

                        @case('year')
                            This Year
                            @break

                        @default
                            {{ $dateFilterValue }}
                    @endswitch
                </strong>
            </span>
        @endif

        @if($sortValue)
            <span class="ml-2">
                Sort:
                <strong>
                    @switch($sortValue)
                        @case('oldest')
                            Oldest First
                            @break

                        @case('name_asc')
                            Name A - Z
                            @break

                        @case('name_desc')
                            Name Z - A
                            @break

                        @default
                            Latest First
                    @endswitch
                </strong>
            </span>
        @endif
    </div>
@endif

{{-- Children Table --}}
<div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm">

    <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
        <h3 class="text-base font-semibold text-gray-800">
            Registered Children
        </h3>

        <p class="text-sm text-gray-400">
            {{ $children->total() }} record{{ $children->total() === 1 ? '' : 's' }}
        </p>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100 text-left">
                    <th class="px-6 py-4 font-medium text-gray-600">No.</th>
                    <th class="px-6 py-4 font-medium text-gray-600">Child</th>
                    <th class="px-6 py-4 font-medium text-gray-600">Child ID</th>
                    <th class="px-6 py-4 font-medium text-gray-600">Age</th>
                    <th class="px-6 py-4 font-medium text-gray-600">Gender</th>
                    <th class="px-6 py-4 font-medium text-gray-600">Guardian</th>
                    <th class="px-6 py-4 font-medium text-gray-600">Phone</th>
                    <th class="px-6 py-4 font-medium text-gray-600">Registered</th>
                    <th class="px-6 py-4 font-medium text-gray-600 text-right">Action</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-100">
                @forelse($children as $child)
                    @php
                        $guardian = $child->guardians->first();
                    @endphp

                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-5 text-gray-700 font-semibold">
                            {{ $children->firstItem() + $loop->index }}
                        </td>

                        <td class="px-6 py-5">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center
                                            text-green-700 font-semibold text-sm">
                                    {{ strtoupper(substr($child->first_name, 0, 1)) }}{{ strtoupper(substr($child->last_name, 0, 1)) }}
                                </div>

                                <div>
                                    <p class="font-medium text-gray-800">
                                        {{ $child->first_name }} {{ $child->last_name }}
                                    </p>
                                    <p class="text-xs text-gray-400 mt-1">
                                        Registered child
                                    </p>
                                </div>
                            </div>
                        </td>

                        <td class="px-6 py-5 text-gray-600">
                            {{ $child->unique_child }}
                        </td>

                        <td class="px-6 py-5 text-gray-600">
                            {{ $child->getAgeLabel() }}
                        </td>

                        <td class="px-6 py-5 text-gray-600">
                            {{ $child->gender }}
                        </td>

                        <td class="px-6 py-5 text-gray-600">
                            @if($guardian)
                                {{ $guardian->first_name }} {{ $guardian->last_name }}
                            @else
                                -
                            @endif
                        </td>

                        <td class="px-6 py-5 text-gray-600">
                            {{ $guardian->phone_number ?? '—' }}
                        </td>

                        <td class="px-6 py-5 text-gray-600">
                            @if($child->created_at)
                                {{ $child->created_at->format('d M Y') }}
                            @else
                                -
                            @endif
                        </td>

                        <td class="px-6 py-5 text-right whitespace-nowrap">
                            <a href="{{ route('children.show', $child) }}"
                               class="text-green-600 hover:text-green-700 hover:underline text-sm font-semibold">
                                View
                            </a>

                            @if($canManageChildren)
                                <a href="{{ route('children.edit', $child) }}"
                                   class="ml-4 text-gray-500 hover:text-gray-700 hover:underline text-sm font-medium">
                                    Edit
                                </a>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="px-6 py-12 text-center text-gray-400">
                            @if($hasFilters)
                                No child records match your search.
                            @else
                                No child records found.
                            @endif
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Pagination --}}
@if(method_exists($children, 'links'))
    <div class="mt-8">
        {{ $children->links() }}
    </div>
@endif

@endsection