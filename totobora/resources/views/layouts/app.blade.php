<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TotoBora - @yield('title', 'Dashboard')</title>

    <!-- Favicon / Browser Tab Logo -->
    <link rel="icon" type="image/png" href="{{ asset('images/totobora-logo.png') }}?v=3">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/totobora-logo.png') }}?v=3">
    <link rel="apple-touch-icon" href="{{ asset('images/totobora-logo.png') }}?v=3">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
</head>

<body class="bg-gray-50 min-h-screen flex flex-col">

<!-- TOP NAV -->
<nav class="bg-white border-b border-gray-200 px-6 py-3 flex items-center justify-between">

    <!-- Brand -->
    <div class="flex items-center gap-3">
        <img src="{{ asset('images/totobora-logo.png') }}"
             alt="TotoBora Logo"
             class="w-10 h-10 object-contain">

        <div>
            <h1 class="text-green-700 font-bold">TotoBora</h1>
            <p class="text-xs text-gray-400">Child Immunization System</p>
        </div>
    </div>

    <!-- User Info -->
    <div class="flex items-center gap-4">

        <div class="text-right">
            <p class="text-sm font-medium text-gray-700">
                {{ Auth::user()->name ?? 'User' }}
            </p>
            <p class="text-xs text-gray-400">
                {{ Auth::user()->role ?? 'staff' }}
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

<!-- MAIN LAYOUT -->
<div class="flex flex-1 min-h-[calc(100vh-64px)]">

    <!-- SIDEBAR -->
    <aside class="w-64 bg-white border-r border-gray-200 flex flex-col">

        <!-- Brand Block -->
        <div class="px-6 py-5 border-b">
            <h2 class="text-lg font-bold text-green-700">TotoBora</h2>
        </div>

        <!-- NAV LINKS -->
        <div class="flex-1 px-4 py-4 space-y-1">

            <p class="text-xs text-gray-400 uppercase px-2 mb-2">Main</p>

            <a href="{{ route('dashboard') }}"
               class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm
               {{ request()->routeIs('dashboard') ? 'bg-green-50 text-green-700 font-semibold border-l-4 border-green-600' : 'text-gray-600 hover:bg-gray-50' }}">
                📊 Dashboard
            </a>

            <a href="{{ route('children.index') }}"
               class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm
               {{ request()->routeIs('children.*') ? 'bg-green-50 text-green-700 font-semibold border-l-4 border-green-600' : 'text-gray-600 hover:bg-gray-50' }}">
                🧒 Children
            </a>

            <a href="{{ route('reminders.index') }}"
               class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm
               {{ request()->routeIs('reminders.*') ? 'bg-green-50 text-green-700 font-semibold border-l-4 border-green-600' : 'text-gray-600 hover:bg-gray-50' }}">
                🔔 Reminders
            </a>

            @if(Auth::user()->role === 'admin')

                <p class="text-xs text-gray-400 uppercase px-2 mt-4 mb-2">Admin</p>

                <a href="{{ route('users.index') }}"
                   class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm
                   {{ request()->routeIs('users.*') ? 'bg-green-50 text-green-700 font-semibold border-l-4 border-green-600' : 'text-gray-600 hover:bg-gray-50' }}">
                    👥 Users
                </a>

                <a href="{{ route('reports.index') }}"
                   class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm
                   {{ request()->routeIs('reports.*') ? 'bg-green-50 text-green-700 font-semibold border-l-4 border-green-600' : 'text-gray-600 hover:bg-gray-50' }}">
                    📋 Reports
                </a>

            @endif

        </div>

        <!-- FOOTER -->
        <div class="px-6 py-4 border-t text-xs text-gray-400">
            © {{ date('Y') }} TotoBora • v1.0
        </div>

    </aside>

    <!-- MAIN CONTENT -->
    <main class="flex-1 p-8">

        @yield('content')

    </main>

</div>

<!-- ICON INIT -->
<script>
    lucide.createIcons();
</script>

@yield('scripts')

</body>
</html>