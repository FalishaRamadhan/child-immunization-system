@extends('layouts.app')

@section('title', 'Manage Users')

@section('content')

    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl font-semibold text-gray-800">Healthcare workers</h2>
            <p class="text-sm text-gray-500 mt-1">Manage system users and facility assignments.</p>
        </div>
        <a href="{{ route('users.create') }}"
           class="bg-green-600 hover:bg-green-700 text-white text-sm font-medium
                  px-4 py-2 rounded-lg transition-colors">
            + Add user
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 bg-green-50 border border-green-200 text-green-700
                    text-sm rounded-lg px-4 py-3">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100 text-left">
                    <th class="px-5 py-3 font-medium text-gray-600">Name</th>
                    <th class="px-5 py-3 font-medium text-gray-600">Email</th>
                    <th class="px-5 py-3 font-medium text-gray-600">Role</th>
                    <th class="px-5 py-3 font-medium text-gray-600">Facility</th>
                    <th class="px-5 py-3 font-medium text-gray-600">Status</th>
                    <th class="px-5 py-3 font-medium text-gray-600">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($users as $user)
                    <tr class="{{ !$user->is_active ? 'opacity-50' : '' }}">
                        <td class="px-5 py-3 font-medium text-gray-800">
                            {{ $user->first_name }} {{ $user->last_name }}
                        </td>
                        <td class="px-5 py-3 text-gray-600">{{ $user->email }}</td>
                        <td class="px-5 py-3">
                            <span class="px-2 py-1 rounded-full text-xs font-medium
                                {{ $user->role === 'admin'
                                    ? 'bg-purple-100 text-purple-700'
                                    : 'bg-blue-100 text-blue-700' }}">
                                {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                            </span>
                        </td>
                        <td class="px-5 py-3 text-gray-600">
                            {{ $user->facility?->name ?? '—' }}
                        </td>
                        <td class="px-5 py-3">
                            <span class="px-2 py-1 rounded-full text-xs font-medium
                                {{ $user->is_active
                                    ? 'bg-green-100 text-green-700'
                                    : 'bg-gray-100 text-gray-500' }}">
                                {{ $user->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="px-5 py-3 flex items-center gap-3">
                            <a href="{{ route('users.edit', $user) }}"
                               class="text-sm text-blue-600 hover:underline">
                                Edit
                            </a>
                            @if($user->is_active)
                                <form method="POST"
                                      action="{{ route('users.deactivate', $user) }}">
                                    @csrf @method('PATCH')
                                    <button class="text-sm text-red-500 hover:underline">
                                        Deactivate
                                    </button>
                                </form>
                            @else
                                <form method="POST"
                                      action="{{ route('users.reactivate', $user) }}">
                                    @csrf @method('PATCH')
                                    <button class="text-sm text-green-600 hover:underline">
                                        Reactivate
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-5 py-10 text-center text-gray-400">
                            No healthcare workers yet.
                            <a href="{{ route('users.create') }}"
                               class="text-green-600 hover:underline ml-1">
                                Add the first one →
                            </a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

@endsection