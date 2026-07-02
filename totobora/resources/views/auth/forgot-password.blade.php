<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TotoBora - Forgot Password</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex items-center justify-center bg-gradient-to-br from-green-50 to-white">
    <div class="bg-white w-96 rounded-2xl shadow-lg px-10 py-10">

        <div class="flex justify-center mb-4">
            <img src="{{ asset('images/totobora-logo.png') }}"
                 alt="TotoBora" class="h-16 w-16 object-contain">
        </div>

        <h1 class="text-lg font-bold text-brand-700 text-center mb-1">Reset Password</h1>
        <p class="text-xs text-gray-400 text-center mb-6">
            Enter your email and we'll generate a reset token.
        </p>

        @if(session('status'))
            <div class="mb-4 bg-green-50 border border-green-200 text-green-700
                        text-sm rounded-lg px-4 py-3">
                {{ session('status') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-4 bg-red-50 border border-red-200 text-red-600
                        text-sm rounded-lg px-4 py-3">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm text-gray-600 mb-1">Email address</label>
                <input type="email" name="email" value="{{ old('email') }}"
                       required autofocus
                       class="w-full border border-gray-300 rounded-lg px-3 py-2.5
                              text-sm focus:outline-none focus:ring-2 focus:ring-brand-500">
            </div>
            <button type="submit"
                class="w-full bg-brand-700 hover:bg-brand-800 text-white font-semibold
                       py-2.5 rounded-lg text-sm transition">
                Send Reset Link
            </button>
        </form>

        <p class="text-center text-sm text-gray-400 mt-4">
            <a href="{{ route('login') }}" class="text-brand-600 hover:underline">
                Back to login
            </a>
        </p>
    </div>
</body>
</html>