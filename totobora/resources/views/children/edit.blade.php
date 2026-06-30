@extends('layouts.app')

@section('title', 'Edit Child')

@section('content')
<div class="max-w-3xl mx-auto bg-white p-6 rounded-lg shadow">
    <h2 class="text-xl font-semibold text-gray-800 mb-4">Edit Child</h2>

    <form action="{{ route('children.update', $child) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">First Name</label>
            <input type="text" name="first_name"
                   value="{{ old('first_name', $child->first_name) }}"
                   class="w-full mt-1 border-gray-300 rounded-md shadow-sm">
            @error('first_name')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Last Name</label>
            <input type="text" name="last_name"
                   value="{{ old('last_name', $child->last_name) }}"
                   class="w-full mt-1 border-gray-300 rounded-md shadow-sm">
            @error('last_name')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Date of Birth</label>
            <input type="date" name="date_of_birth"
                   value="{{ old('date_of_birth', $child->date_of_birth ? \Carbon\Carbon::parse($child->date_of_birth)->format('Y-m-d') : '') }}"
                   class="w-full mt-1 border-gray-300 rounded-md shadow-sm">
            @error('date_of_birth')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Gender</label>
            <select name="gender" class="w-full mt-1 border-gray-300 rounded-md shadow-sm">
                <option value="">Select Gender</option>
                <option value="Male" {{ old('gender', $child->gender) == 'Male' ? 'selected' : '' }}>Male</option>
                <option value="Female" {{ old('gender', $child->gender) == 'Female' ? 'selected' : '' }}>Female</option>
            </select>
            @error('gender')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Birth Weight</label>
            <input type="number" step="0.01" name="birth_weight"
                   value="{{ old('birth_weight', $child->birth_weight) }}"
                   class="w-full mt-1 border-gray-300 rounded-md shadow-sm">
            @error('birth_weight')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex justify-end gap-3">
            <a href="{{ route('children.show', $child) }}"
               class="px-4 py-2 bg-gray-200 text-gray-700 rounded">
                Cancel
            </a>

            <button type="submit"
                    class="px-4 py-2 bg-brand-600 text-white rounded">
                Update Child
            </button>
        </div>
    </form>
</div>
@endsection