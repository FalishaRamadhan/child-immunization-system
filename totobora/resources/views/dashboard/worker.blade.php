@extends('layouts.app')

@section('title', 'Healthcare Dashboard')

@section('content')

<div class="mb-6 flex items-center justify-between">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Facility Dashboard</h2>
        <p class="text-sm text-gray-500">
            Welcome {{ auth()->user()->name }} 
        </p>
    </div>

    
</div>

<!-- KPI ROW -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">

    <div class="bg-white p-5 rounded-xl border shadow-sm hover:shadow-md transition">
        <p class="text-xs text-gray-500 uppercase">Total Children</p>
        <p class="text-3xl font-bold text-green-700 mt-2">
            {{ $totalChildren }}
        </p>
    </div>

    <div class="bg-white p-5 rounded-xl border shadow-sm hover:shadow-md transition">
        <p class="text-xs text-gray-500 uppercase">Vaccines This Month</p>
        <p class="text-3xl font-bold text-blue-600 mt-2">
            {{ $vaccinesThisMonth }}
        </p>
    </div>

</div>

<!-- CHILD LIST -->
<div class="bg-white rounded-xl border shadow-sm p-5">

    <div class="flex items-center justify-between mb-4">
        <h3 class="font-semibold text-gray-700">Recent Children</h3>

    </div>

    <div class="space-y-3">

        @forelse($children as $child)
            <div class="flex justify-between items-center border-b pb-2">
                <div>
                    <p class="font-medium text-gray-800">
                        {{ $child->first_name }} {{ $child->last_name }}
                    </p>
                    <p class="text-xs text-gray-500">
                        ID: {{ $child->unique_child }}
                    </p>
                </div>

                <a href="{{ route('children.show', $child->child_id) }}"
                   class="text-sm text-green-600 hover:underline">
                    View
                </a>
            </div>
        @empty
            <p class="text-gray-400 text-center py-6">
                No children in this facility yet
            </p>
        @endforelse

    </div>

</div>

@endsection