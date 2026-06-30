<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TotoBora - @yield('title', 'Dashboard')</title>
    <link rel="icon" type="image/png" href="{{ asset('images/totobora-logo.png') }}">
    <link rel="icon" href="/favicon.ico" sizes="32x32">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50 min-h-screen flex flex-col">

    <!-- TOP NAV -->
    <nav class="bg-white border-b border-gray-200 px-6 py-3 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <img src="{{ asset('images/totobora-logo.png') }}"
                 alt="TotoBora Logo"
                 class="w-9 h-9 object-contain">
            <div>
                <p class="text-brand-700 font-bold text-sm leading-tight">TotoBora</p>
                <p class="text-xs text-gray-400 leading-tight">Child Immunization System</p>
            </div>
        </div>

        <div class="flex items-center gap-4">
            <div class="text-right">
                <p class="text-sm font-medium text-gray-700">
                    {{ Auth::user()->first_name }} {{ Auth::user()->last_name }}
                </p>
                <p class="text-xs text-gray-400 capitalize">
                    {{ str_replace('_', ' ', Auth::user()->role) }}
                </p>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="text-sm text-gray-500 hover:text-red-600 transition">
                    Sign out
                </button>
            </form>
        </div>
    </nav>

    <!-- BODY -->
    <div class="flex flex-1">

        <!-- SIDEBAR -->
        <aside class="w-56 bg-white border-r border-gray-200 flex flex-col py-6 px-4 gap-1">

            <p class="text-xs text-gray-400 uppercase tracking-wide px-2 mb-2">Main</p>

            <a href="{{ route('dashboard') }}"
               class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm
                      {{ request()->routeIs('dashboard') ? 'bg-brand-50 text-brand-700 font-semibold' : 'text-gray-600 hover:bg-gray-50' }}">
                Dashboard
            </a>

            <a href="{{ route('children.index') }}"
               class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm
                      {{ request()->routeIs('children.*') ? 'bg-brand-50 text-brand-700 font-semibold' : 'text-gray-600 hover:bg-gray-50' }}">
                Children
            </a>

            <a href="{{ route('reminders.index') }}"
               class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm
                      {{ request()->routeIs('reminders.*') ? 'bg-brand-50 text-brand-700 font-semibold' : 'text-gray-600 hover:bg-gray-50' }}">
                Reminders
            </a>

            @if(Auth::user()->role === 'admin')
                <div class="mt-4 mb-1">
                    <p class="text-xs text-gray-400 uppercase tracking-wide px-2">Admin</p>
                </div>

                <a href="{{ route('users.index') }}"
                   class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm
                          {{ request()->routeIs('users.*') ? 'bg-brand-50 text-brand-700 font-semibold' : 'text-gray-600 hover:bg-gray-50' }}">
                    Users
                </a>

                <a href="{{ route('reports.index') }}"
                   class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm
                          {{ request()->routeIs('reports.*') ? 'bg-brand-50 text-brand-700 font-semibold' : 'text-gray-600 hover:bg-gray-50' }}">
                    Reports
                </a>
            @endif

            <div class="mt-auto pt-6 px-2">
                <p class="text-xs text-gray-300">© {{ date('Y') }} TotoBora v1.0</p>
            </div>

        </aside>

        <!-- PAGE CONTENT -->
        <main class="flex-1 p-8 overflow-y-auto">
            @if(session('success'))
                <div data-flash
                     class="mb-4 bg-brand-50 border border-brand-200 text-brand-700
                            text-sm rounded-lg px-4 py-3">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div data-flash
                     class="mb-4 bg-red-50 border border-red-200 text-red-700
                            text-sm rounded-lg px-4 py-3">
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </main>

    </div>

    @yield('scripts')

</body>
</html>