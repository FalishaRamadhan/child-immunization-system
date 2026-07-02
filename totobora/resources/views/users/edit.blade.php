@extends('layouts.app')

@section('title', 'Edit User')

@section('content')
<div class="max-w-xl">

    <div class="mb-6">
        <a href="{{ route('users.index') }}"
           class="text-sm text-gray-500 hover:text-gray-700">
            ← Users
        </a>

        <h2 class="text-xl font-semibold text-gray-800 mt-1">
            Edit - {{ $user->first_name }} {{ $user->last_name }}
        </h2>
    </div>

    {{-- Alerts --}}
    @if(session('success'))
        <div class="mb-4 bg-green-50 border border-green-200 text-green-700
                    text-sm rounded-lg px-4 py-3">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-4 bg-red-50 border border-red-200 text-red-700
                    text-sm rounded-lg px-4 py-3">
            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    {{-- Update User Form --}}
    <form method="POST" action="{{ route('users.update', $user) }}"
          class="bg-white rounded-xl border border-gray-200 p-6 space-y-5">
        @csrf
        @method('PUT')

        {{-- Name --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    First name
                </label>

                <input type="text"
                       name="first_name"
                       value="{{ old('first_name', $user->first_name) }}"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm
                              focus:outline-none focus:ring-2 focus:ring-green-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Last name
                </label>

                <input type="text"
                       name="last_name"
                       value="{{ old('last_name', $user->last_name) }}"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm
                              focus:outline-none focus:ring-2 focus:ring-green-500">
            </div>
        </div>

        {{-- Email --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Email
            </label>

            <input type="email"
                   name="email"
                   value="{{ old('email', $user->email) }}"
                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm
                          focus:outline-none focus:ring-2 focus:ring-green-500">
        </div>

        {{-- Role --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Role
            </label>

            <select name="role"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm
                           focus:outline-none focus:ring-2 focus:ring-green-500">
                <option value="healthcare_worker"
                    {{ old('role', $user->role) === 'healthcare_worker' ? 'selected' : '' }}>
                    Healthcare worker
                </option>

                <option value="admin"
                    {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>
                    Administrator
                </option>
            </select>
        </div>

        {{-- Facility --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Facility
            </label>

            <select name="facility_id"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm
                           focus:outline-none focus:ring-2 focus:ring-green-500">
                <option value="">Select facility</option>

                @foreach($facilities as $facility)
                    <option value="{{ $facility->facility_id }}"
                        {{ old('facility_id', $user->facility_id) == $facility->facility_id ? 'selected' : '' }}>
                        {{ $facility->name }} - {{ $facility->location }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Random Password Generator --}}
        <div class="rounded-xl border border-green-200 bg-green-50 p-5">
            <div class="flex items-start gap-3">
                <input type="checkbox"
                       id="generate_password"
                       name="generate_password"
                       value="1"
                       {{ old('generate_password') ? 'checked' : '' }}
                       class="mt-1 rounded border-gray-300 text-green-600 focus:ring-green-500">

                <div>
                    <label for="generate_password"
                           class="block text-sm font-semibold text-gray-800">
                        Generate random password and send to user email
                    </label>

                    
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex items-center gap-3 pt-2">
            <button type="submit"
                    class="bg-green-600 hover:bg-green-700 text-white font-medium
                           px-6 py-2 rounded-lg text-sm transition-colors">
                Save changes
            </button>

            <a href="{{ route('users.index') }}"
               class="text-sm text-gray-500 hover:text-gray-700">
                Cancel
            </a>
        </div>
    </form>

    {{-- Password Reset Link Form --}}
    

</div>
@endsection