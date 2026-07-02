@extends('layouts.app')

@section('title', 'Users')

@section('content')
<div class="space-y-6">

    {{-- Page Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-semibold text-gray-800">
                Users
            </h2>
            <p class="text-sm text-gray-500 mt-1">
                Manage system users, roles, facilities, and account status.
            </p>
        </div>

        @if(Route::has('users.create'))
            <a href="{{ route('users.create') }}"
               class="bg-green-600 hover:bg-green-700 text-white font-medium
                      px-5 py-2 rounded-lg text-sm transition-colors">
                Add user
            </a>
        @endif
    </div>

    {{-- Alerts --}}
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700
                    text-sm rounded-lg px-4 py-3">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700
                    text-sm rounded-lg px-4 py-3">
            {{ session('error') }}
        </div>
    @endif

    {{-- Search and Filters --}}
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <form method="GET" action="{{ route('users.index') }}" class="space-y-4">

            {{-- Keyword Search --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Keyword search
                </label>

                <input type="text"
                       name="q"
                       value="{{ request('q') }}"
                       placeholder="Search by name, email, role, or facility..."
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm
                              focus:outline-none focus:ring-2 focus:ring-green-500">
            </div>

            {{-- Advanced Filters --}}
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4">

                {{-- Role / Category Filter --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Role
                    </label>

                    <select name="role"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm
                                   focus:outline-none focus:ring-2 focus:ring-green-500">
                        <option value="">All roles</option>

                        <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>
                            Administrator
                        </option>

                        <option value="healthcare_worker" {{ request('role') === 'healthcare_worker' ? 'selected' : '' }}>
                            Healthcare worker
                        </option>
                    </select>
                </div>

                {{-- Facility Filter --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Facility
                    </label>

                    <select name="facility_id"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm
                                   focus:outline-none focus:ring-2 focus:ring-green-500">
                        <option value="">All facilities</option>

                        @foreach($facilities as $facility)
                            <option value="{{ $facility->facility_id }}"
                                {{ request('facility_id') == $facility->facility_id ? 'selected' : '' }}>
                                {{ $facility->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Status Filter --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Status
                    </label>

                    <select name="status"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm
                                   focus:outline-none focus:ring-2 focus:ring-green-500">
                        <option value="">All statuses</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>
                            Active
                        </option>
                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>
                            Inactive
                        </option>
                    </select>
                </div>

                {{-- Date From --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        From date
                    </label>

                    <input type="date"
                           name="from_date"
                           value="{{ request('from_date') }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm
                                  focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>

                {{-- Date To --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        To date
                    </label>

                    <input type="date"
                           name="to_date"
                           value="{{ request('to_date') }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm
                                  focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>
            </div>

            {{-- Sort and Actions --}}
            <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4">

                <div class="w-full md:w-64">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Sort by
                    </label>

                    <select name="sort"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm
                                   focus:outline-none focus:ring-2 focus:ring-green-500">
                        <option value="latest" {{ request('sort', 'latest') === 'latest' ? 'selected' : '' }}>
                            Latest
                        </option>

                        <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>
                            Oldest
                        </option>

                        <option value="relevance" {{ request('sort') === 'relevance' ? 'selected' : '' }}>
                            Relevance
                        </option>

                        <option value="name_asc" {{ request('sort') === 'name_asc' ? 'selected' : '' }}>
                            Name A-Z
                        </option>

                        <option value="name_desc" {{ request('sort') === 'name_desc' ? 'selected' : '' }}>
                            Name Z-A
                        </option>
                    </select>
                </div>

                <div class="flex items-center gap-3">
                    <button type="submit"
                            class="bg-green-600 hover:bg-green-700 text-white font-medium
                                   px-5 py-2 rounded-lg text-sm transition-colors">
                        Search
                    </button>

                    <a href="{{ route('users.index') }}"
                       class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium
                              px-5 py-2 rounded-lg text-sm transition-colors">
                        Clear
                    </a>
                </div>
            </div>
        </form>
    </div>

    {{-- Search Summary --}}
    @if(request()->hasAny(['q', 'role', 'facility_id', 'status', 'from_date', 'to_date', 'sort']))
        <div class="text-sm text-gray-500">
            Showing filtered results
            @if(request('q'))
                for <span class="font-semibold text-gray-700">"{{ request('q') }}"</span>
            @endif
        </div>
    @endif

    {{-- Users Table --}}
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-sm font-semibold text-gray-700 whitespace-nowrap">
                            No.
                        </th>
                        <th class="px-6 py-4 text-sm font-semibold text-gray-700">
                            Name
                        </th>
                        <th class="px-6 py-4 text-sm font-semibold text-gray-700">
                            Email
                        </th>
                        <th class="px-6 py-4 text-sm font-semibold text-gray-700">
                            Role
                        </th>
                        <th class="px-6 py-4 text-sm font-semibold text-gray-700">
                            Facility
                        </th>
                        <th class="px-6 py-4 text-sm font-semibold text-gray-700">
                            Status
                        </th>
                        <th class="px-6 py-4 text-sm font-semibold text-gray-700">
                            Registered
                        </th>
                        <th class="px-6 py-4 text-sm font-semibold text-gray-700">
                            Actions
                        </th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($users as $user)
                        <tr class="border-b border-gray-100 hover:bg-gray-50">

                            {{-- Number Column --}}
                            <td class="px-6 py-4 text-sm text-gray-600 whitespace-nowrap">
                                {{ method_exists($users, 'firstItem') ? $users->firstItem() + $loop->index : $loop->iteration }}
                            </td>

                            {{-- Name --}}
                            <td class="px-6 py-4 text-sm font-semibold text-gray-900">
                                {{ $user->first_name }} {{ $user->last_name }}
                            </td>

                            {{-- Email --}}
                            <td class="px-6 py-4 text-sm text-gray-700">
                                {{ $user->email }}
                            </td>

                            {{-- Role --}}
                            <td class="px-6 py-4 text-sm">
                                <span class="inline-flex px-3 py-1 rounded-full bg-blue-100 text-blue-700 font-medium">
                                    {{ ucwords(str_replace('_', ' ', $user->role)) }}
                                </span>
                            </td>

                            {{-- Facility --}}
                            <td class="px-6 py-4 text-sm text-gray-700">
                                {{ $user->facility->name ?? 'Not assigned' }}
                            </td>

                            {{-- Status --}}
                            <td class="px-6 py-4 text-sm">
                                @if($user->is_active ?? true)
                                    <span class="inline-flex px-3 py-1 rounded-full bg-green-100 text-green-700 font-medium">
                                        Active
                                    </span>
                                @else
                                    <span class="inline-flex px-3 py-1 rounded-full bg-red-100 text-red-700 font-medium">
                                        Inactive
                                    </span>
                                @endif
                            </td>

                            {{-- Registered Date --}}
                            <td class="px-6 py-4 text-sm text-gray-700 whitespace-nowrap">
                                {{ $user->created_at ? $user->created_at->format('d M Y') : '—' }}
                            </td>

                            {{-- Actions --}}
                            <td class="px-6 py-4 text-sm whitespace-nowrap">
                                <div class="flex items-center gap-4">

                                    <a href="{{ route('users.edit', $user) }}"
                                       class="text-blue-600 hover:text-blue-800 font-medium">
                                        Edit
                                    </a>

                                    @if(Route::has('users.deactivate') && ($user->is_active ?? true))
                                        <form method="POST"
                                              action="{{ route('users.deactivate', $user) }}"
                                              onsubmit="return confirm('Deactivate this user?')">
                                            @csrf
                                            @method('PATCH')

                                            <button type="submit"
                                                    class="text-red-600 hover:text-red-800 font-medium">
                                                Deactivate
                                            </button>
                                        </form>
                                    @elseif(Route::has('users.activate') && !($user->is_active ?? true))
                                        <form method="POST"
                                              action="{{ route('users.activate', $user) }}"
                                              onsubmit="return confirm('Activate this user?')">
                                            @csrf
                                            @method('PATCH')

                                            <button type="submit"
                                                    class="text-green-600 hover:text-green-800 font-medium">
                                                Activate
                                            </button>
                                        </form>
                                    @endif

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8"
                                class="px-6 py-8 text-center text-sm text-gray-500">
                                No users found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pagination --}}
    @if(method_exists($users, 'links'))
        <div>
            {{ $users->withQueryString()->links() }}
        </div>
    @endif

</div>
@endsection