@extends('layouts.app')

@section('title', 'Add User')

@section('content')
<div class="max-w-xl">

    <div class="mb-6">
        <a href="{{ route('users.index') }}"
           class="text-sm text-gray-500 hover:text-gray-700">← Users</a>
        <h2 class="text-xl font-semibold text-gray-800 mt-1">Add healthcare worker</h2>
    </div>

    @if($errors->any())
        <div class="mb-4 bg-red-50 border border-red-200 text-red-700
                    text-sm rounded-lg px-4 py-3">
            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('users.store') }}"
          class="bg-white rounded-xl border border-gray-200 p-6 space-y-5">
        @csrf

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    First name <span class="text-red-500">*</span>
                </label>
                <input type="text" name="first_name" value="{{ old('first_name') }}"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm
                           focus:outline-none focus:ring-2 focus:ring-brand-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Last name <span class="text-red-500">*</span>
                </label>
                <input type="text" name="last_name" value="{{ old('last_name') }}"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm
                           focus:outline-none focus:ring-2 focus:ring-brand-500">
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Email <span class="text-red-500">*</span>
            </label>
            <input type="email" name="email" value="{{ old('email') }}"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm
                       focus:outline-none focus:ring-2 focus:ring-brand-500">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Role <span class="text-red-500">*</span>
            </label>
            <select name="role"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm
                       focus:outline-none focus:ring-2 focus:ring-brand-500">
                <option value="healthcare_worker"
                    {{ old('role') === 'healthcare_worker' ? 'selected' : '' }}>
                    Healthcare worker
                </option>
                <option value="admin"
                    {{ old('role') === 'admin' ? 'selected' : '' }}>
                    Administrator
                </option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Facility <span class="text-red-500">*</span>
            </label>
            <select name="facility_id"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm
                       focus:outline-none focus:ring-2 focus:ring-brand-500">
                <option value="">Select facility</option>
                @foreach($facilities as $facility)
                    <option value="{{ $facility->facility_id }}"
                        {{ old('facility_id') == $facility->facility_id ? 'selected' : '' }}>
                        {{ $facility->name }} - {{ $facility->location }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Password <span class="text-red-500">*</span>
                </label>
                <input type="password" name="password"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm
                           focus:outline-none focus:ring-2 focus:ring-brand-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Confirm password <span class="text-red-500">*</span>
                </label>
                <input type="password" name="password_confirmation"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm
                           focus:outline-none focus:ring-2 focus:ring-brand-500">
            </div>
        </div>

        <div class="flex items-center gap-3 pt-2">
            <button type="submit"
                class="bg-brand-600 hover:bg-brand-700 text-white font-medium
                       px-6 py-2 rounded-lg text-sm transition-colors">
                Create user
            </button>
            <a href="{{ route('users.index') }}"
               class="text-sm text-gray-500 hover:text-gray-700">Cancel</a>
        </div>
    </form>
</div>
@endsection