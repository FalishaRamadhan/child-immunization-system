@extends('layouts.app')

@section('title', 'My Children')

@section('content')

<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-800">My Children</h2>
    <p class="text-sm text-gray-500">
        Welcome {{ auth()->user()->name }}
    </p>
</div>

<!-- CHILD GRID -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">

    @forelse($children as $child)

        <div class="bg-white border rounded-xl p-5 shadow-sm hover:shadow-md transition">

            <h3 class="font-semibold text-gray-800">
                {{ $child->first_name }} {{ $child->last_name }}
            </h3>

            <p class="text-sm text-gray-500 mt-1">
                Child ID: {{ $child->unique_child }}
            </p>

            <p class="text-sm text-gray-500">
                Gender: {{ $child->gender }}
            </p>

            <a href="{{ route('children.show', $child->child_id) }}"
               class="inline-block mt-3 text-sm text-blue-600 hover:underline">
                View full record →
            </a>

        </div>

    @empty

        <div class="col-span-full bg-white border rounded-xl p-8 text-center text-gray-500">
          No child is registered under your name.
        </div>

    @endforelse

</div>

@endsection