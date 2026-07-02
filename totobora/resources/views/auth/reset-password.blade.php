<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TotoBora — Set New Password</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex items-center justify-center bg-gradient-to-br from-green-50 to-white">
    <div class="bg-white w-96 rounded-2xl shadow-lg px-10 py-10">

        <div class="flex justify-center mb-4">
            <img src="{{ asset('images/totobora-logo.png') }}"
                 alt="TotoBora" class="h-16 w-16 object-contain">
        </div>

        <h1 class="text-lg font-bold text-brand-700 text-center mb-1">Set New Password</h1>
        <p class="text-xs text-gray-400 text-center mb-6">Enter your new password below.</p>

        @if($errors->any())
            <div class="mb-4 bg-red-50 border border-red-200 text-red-600
                        text-sm rounded-lg px-4 py-3">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <input type="hidden" name="email" value="{{ $email }}">

            <div>
                <label class="block text-sm text-gray-600 mb-1">New password</label>
                <input type="password" name="password" required
                       class="w-full border border-gray-300 rounded-lg px-3 py-2.5
                              text-sm focus:outline-none focus:ring-2 focus:ring-brand-500">
            </div>
            <div>
                <label class="block text-sm text-gray-600 mb-1">Confirm password</label>
                <input type="password" name="password_confirmation" required
                       class="w-full border border-gray-300 rounded-lg px-3 py-2.5
                              text-sm focus:outline-none focus:ring-2 focus:ring-brand-500">
            </div>

            <button type="submit"
                class="w-full bg-brand-700 hover:bg-brand-800 text-white font-semibold
                       py-2.5 rounded-lg text-sm transition">
                Reset Password
            </button>
        </form>
    </div>
</body>
</html>