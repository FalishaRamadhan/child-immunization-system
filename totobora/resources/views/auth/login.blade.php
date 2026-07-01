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

            <!-- Forgot Password -->
            <div class="text-right mt-1">
                <a href="{{ route('password.request') }}"
                class="text-xs text-brand-600 hover:underline">
                    Forgot password?
                </a>
            </div> 

            <!-- Submit -->
            <button type="submit"
                class="w-full bg-brand-700 hover:bg-brand-800 text-white font-semibold
                       py-2.5 rounded-lg text-sm transition mt-2">
                Log in
            </button>

            <!-- Divider -->
            <div class="flex items-center my-4">
                <div class="flex-1 border-t border-gray-200"></div>
                <span class="px-3 text-xs text-gray-400">or</span>
                <div class="flex-1 border-t border-gray-200"></div>
            </div>

            <!-- Google Login -->
            <a href="{{ route('google.login') }}"
            class="w-full flex items-center justify-center gap-3
                    border border-gray-300 rounded-lg py-2.5
                    bg-white hover:bg-gray-50
                    transition text-sm font-medium text-gray-700">

                <svg xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 48 48"
                    class="w-5 h-5">
                    <path fill="#FFC107" d="M43.6 20.5H42V20H24v8h11.3C33.7 32.7 29.3 36 24 36c-6.6 0-12-5.4-12-12S17.4 12 24 12c3 0 5.7 1.1 7.8 2.9l5.7-5.7C33.9 6.1 29.2 4 24 4 13 4 4 13 4 24s9 20 20 20 20-9 20-20c0-1.3-.1-2.4-.4-3.5z"/>
                    <path fill="#FF3D00" d="M6.3 14.7l6.6 4.8C14.7 15.4 19 12 24 12c3 0 5.7 1.1 7.8 2.9l5.7-5.7C33.9 6.1 29.2 4 24 4c-7.7 0-14.3 4.3-17.7 10.7z"/>
                    <path fill="#4CAF50" d="M24 44c5.1 0 9.7-2 13.2-5.2l-6.1-5.2c-2 1.5-4.5 2.4-7.1 2.4-5.3 0-9.7-3.3-11.3-8H6.2C9.5 39.5 15.9 44 24 44z"/>
                    <path fill="#1976D2" d="M43.6 20.5H42V20H24v8h11.3c-1.1 3.1-3.3 5.5-6.2 7.2l6.1 5.2C38.7 37.2 44 31.3 44 24c0-1.3-.1-2.4-.4-3.5z"/>
                </svg>

                <span>Continue with Google</span>
            </a>
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