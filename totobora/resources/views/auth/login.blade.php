<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TotoBora - Sign in</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(12px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .fade-in { animation: fadeIn 0.4s ease; }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center bg-gradient-to-br from-green-50 to-white">

    <div class="fade-in bg-white w-96 rounded-2xl shadow-lg px-10 py-10 text-center">

        <!-- Logo -->
        <div class="flex justify-center mb-3">
            <img src="{{ asset('images/totobora-logo.png') }}"
                 alt="TotoBora Logo"
                 class="h-20 w-20 object-contain">
        </div>

        <h1 class="text-xl font-bold text-brand-700 mb-1">TotoBora</h1>
        <p class="text-xs text-gray-400 mb-6">Child Immunization & Growth Monitoring</p>

        <!-- Error -->
        @if ($errors->any())
            <div class="mb-4 bg-red-50 border border-red-200 text-red-600
                        text-sm rounded-lg px-4 py-3 text-left">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="text-left space-y-4">
            @csrf

            <!-- Email -->
            <div>
                <label class="block text-sm text-gray-600 mb-1">Email</label>
                <input type="email" name="email"
                       value="{{ old('email') }}"
                       placeholder="Enter your email address"
                       required autofocus
                       class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm
                              outline-none focus:border-brand-600 focus:ring-2
                              focus:ring-green-100 transition">
            </div>

            <!-- Password -->
            <div>
                <label class="block text-sm text-gray-600 mb-1">Password</label>
                <div class="relative">
                    <input type="password" name="password" id="password"
                           placeholder="Enter your password"
                           required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2.5
                                  text-sm outline-none focus:border-brand-600
                                  focus:ring-2 focus:ring-green-100 transition">
                    <button type="button"
                            onclick="togglePassword()"
                            class="absolute right-3 top-2.5 text-xs text-brand-700
                                   font-medium hover:underline">
                        Show
                    </button>
                </div>
            </div>

            <!-- Submit -->
            <button type="submit"
                class="w-full bg-brand-700 hover:bg-brand-800 text-white font-semibold
                       py-2.5 rounded-lg text-sm transition mt-2">
                Log in
            </button>

        </form>

        <p class="text-xs text-gray-300 mt-6">&copy {{ date('Y') }} TotoBora</p>
    </div>

    <script>
        function togglePassword() {
            const input  = document.getElementById('password');
            const btn    = event.currentTarget;
            const isHidden = input.type === 'password';
            input.type   = isHidden ? 'text' : 'password';
            btn.textContent = isHidden ? 'Hide' : 'Show';
        }
    </script>

</body>
</html>