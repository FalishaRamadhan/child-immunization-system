@extends('layouts.app')

@section('title', 'Reports & Analytics')

@section('content')

<!-- HEADER -->
<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Reports & Analytics</h2>
    <p class="text-sm text-gray-500">
        System-wide immunization coverage and performance overview
    </p>
</div>

<!-- KPI STRIP -->
<div class="flex flex-wrap gap-4 mb-8">

    <!-- CHILDREN -->
    <div class="flex-1 min-w-[180px] bg-gradient-to-br from-green-50 to-white border border-green-100 rounded-xl p-4 shadow-sm hover:shadow-md transition transform hover:-translate-y-1">
        <p class="text-xs text-gray-500 uppercase">Children Registered</p>
        <p class="text-2xl font-bold text-brand-700 mt-1">
            {{ $totalChildren }}
        </p>
    </div>

    <!-- VACCINES -->
    <div class="flex-1 min-w-[180px] bg-gradient-to-br from-blue-50 to-white border border-blue-100 rounded-xl p-4 shadow-sm hover:shadow-md transition transform hover:-translate-y-1">
        <p class="text-xs text-gray-500 uppercase">Vaccines This Month</p>
        <p class="text-2xl font-bold text-blue-600 mt-1">
            {{ $vaccinesThisMonth }}
        </p>
    </div>

    <!-- MISSED -->
    <div class="flex-1 min-w-[180px] bg-gradient-to-br from-red-50 to-white border border-red-100 rounded-xl p-4 shadow-sm hover:shadow-md transition transform hover:-translate-y-1">
        <p class="text-xs text-gray-500 uppercase">Missed Appointments</p>
        <p class="text-2xl font-bold text-red-600 mt-1">
            {{ $missedAppointments }}
        </p>
    </div>

    <!-- REMINDERS -->
    <div class="flex-1 min-w-[180px] bg-gradient-to-br from-purple-50 to-white border border-purple-100 rounded-xl p-4 shadow-sm hover:shadow-md transition transform hover:-translate-y-1">
        <p class="text-xs text-gray-500 uppercase">Reminders Sent</p>
        <p class="text-2xl font-bold text-purple-600 mt-1">
            {{ $remindersSent }}
        </p>
    </div>

</div>

<!-- CHART SECTION -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">

    <!-- COVERAGE -->
    <div class="bg-white rounded-xl border shadow-sm p-6 hover:shadow-md transition">
        <div class="flex justify-between items-center mb-4">
            <h3 class="font-semibold text-gray-700">
                Immunization Coverage by Vaccine
            </h3>
            <span class="text-xs bg-brand-50 text-brand-600 px-2 py-1 rounded-full">
                Coverage
            </span>
        </div>

        <canvas id="coverageChart" height="200"></canvas>
    </div>

    <!-- TREND -->
    <div class="bg-white rounded-xl border shadow-sm p-6 hover:shadow-md transition">
        <div class="flex justify-between items-center mb-4">
            <h3 class="font-semibold text-gray-700">
                Vaccines Administered (6 Months)
            </h3>
            <span class="text-xs bg-blue-50 text-blue-600 px-2 py-1 rounded-full">
                Trend
            </span>
        </div>

        <canvas id="trendChart" height="200"></canvas>
    </div>

</div>

<!-- DEFAULTER TABLE -->
<div class="bg-white rounded-xl border shadow-sm overflow-hidden">

    <div class="p-5 border-b">
        <h3 class="font-semibold text-gray-800">Defaulter List</h3>
        <p class="text-xs text-gray-500">
            Children with missed appointments and overdue vaccinations
        </p>
    </div>

    <div class="overflow-x-auto">

        <table class="w-full text-sm">

            <thead class="bg-gray-50 text-left">
                <tr>
                    <th class="px-5 py-3">No.</th>
                    <th class="px-5 py-3">Child</th>
                    <th class="px-5 py-3">Vaccine</th>
                    <th class="px-5 py-3">Due Date</th>
                    <th class="px-5 py-3">Guardian Phone</th>
                    <th class="px-5 py-3">Days Overdue</th>
                </tr>
            </thead>

            <tbody class="divide-y">

                @forelse($defaulters as $d)
                    <tr class="hover:bg-gray-50 transition cursor-pointer"
                        onclick="window.location='{{ route('children.show', $d['child']) }}'">

                        <td class="px-5 py-3 text-gray-600">
                            {{ $loop->iteration }}
                        </td>

                        <td class="px-5 py-3 font-medium text-gray-800">
                            {{ $d['child']->first_name }} {{ $d['child']->last_name }}
                        </td>

                        <td class="px-5 py-3 text-gray-600">
                            {{ $d['vaccine'] }}
                        </td>

                        <td class="px-5 py-3 text-gray-600">
                            {{ \Carbon\Carbon::parse($d['due_date'])->format('d M Y') }}
                        </td>

                        <td class="px-5 py-3 text-gray-600">
                            {{ $d['guardian']?->phone_number ?? '—' }}
                        </td>

                        <td class="px-5 py-3">
                            <span class="font-semibold text-red-600">
                                {{ $d['days_overdue'] }} days
                            </span>
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-10 text-gray-400">
                            No defaulters - all children are up to date
                        </td>
                    </tr>
                @endforelse

            </tbody>

        </table>

    </div>
</div>

@endsection

@section('scripts')

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>

<script>
/* COVERAGE CHART */
new Chart(document.getElementById('coverageChart'), {
    type: 'bar',
    data: {
        labels: @json($vaccines),
        datasets: [{
            data: @json($coverage),
            backgroundColor: '#1f7a5a',
            borderRadius: 6
        }]
    },
    options: {
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true } }
    }
});

/* TREND CHART */
const trend = @json($trend);

new Chart(document.getElementById('trendChart'), {
    type: 'line',
    data: {
        labels: trend.map(t => t.label),
        datasets: [{
            data: trend.map(t => t.total),
            borderColor: '#2563eb',
            fill: true,
            tension: 0.4
        }]
    },
    options: {
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true } }
    }
});
</script>

@endsection