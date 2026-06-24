<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>TotoBora - Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        :root {
            --green: #1f7a5a;
            --dark-green: #155c43;
            --bg: #f4f7f6;
            --text: #2d3436;
        }

        body {
            margin: 0;
            font-family: 'Segoe UI', Arial, sans-serif;
            background: linear-gradient(135deg, #e8f5ef, #ffffff);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* LOGIN CARD */
        .login-card {
            background: white;
            width: 380px;
            padding: 35px;
            border-radius: 14px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.08);
            text-align: center;
            animation: fadeIn 0.5s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* LOGO */
        .logo img {
            height: 90px;
            margin-bottom: 10px;
        }

        .tagline {
            font-size: 13px;
            color: #666;
            margin-bottom: 25px;
        }

        /* INPUTS */
        .input-group {
            text-align: left;
            margin-bottom: 15px;
            position: relative;
        }

        label {
            font-size: 13px;
            color: #444;
            display: block;
            margin-bottom: 5px;
        }

        input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            outline: none;
            transition: 0.3s;
        }

        input:focus {
            border-color: var(--green);
            box-shadow: 0 0 0 3px rgba(31,122,90,0.1);
        }

        /* SHOW/HIDE BUTTON */
        .toggle-password {
            position: absolute;
            right: 12px;
            top: 38px;
            cursor: pointer;
            font-size: 13px;
            color: var(--green);
            user-select: none;
        }

        /* BUTTON */
        .btn {
            width: 100%;
            padding: 12px;
            background: var(--green);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 15px;
            cursor: pointer;
            transition: 0.3s;
            margin-top: 10px;
        }

        .btn:hover {
            background: var(--dark-green);
            transform: scale(1.02);
        }

        /* FOOTER */
        .footer {
            font-size: 12px;
            color: #888;
            margin-top: 20px;
        }

    </style>
</head>

<body>

<div class="login-card">

    <!-- LOGO -->
    <div class="logo">
        <img src="{{ asset('images/totobora-logo.png') }}" alt="TotoBora Logo">
    </div>

    <div class="tagline">
        Healthy Children • Stronger Tomorrow
    </div>

    <!-- FORM -->
    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- EMAIL -->
        <div class="input-group">
            <label>Email</label>
            <input 
                type="email" 
                name="email" 
                value="{{ old('email') }}" 
                placeholder="Enter your email address"
                required
            >
        </div>

        <!-- PASSWORD -->
        <div class="input-group">
            <label>Password</label>
            <input 
                type="password" 
                id="password"
                name="password" 
                placeholder="Enter your password"
                required
            >

            <span class="toggle-password" onclick="togglePassword()">
                Show
            </span>
        </div>

        <button type="submit" class="btn">
            Log in
        </button>
    </form>

    <div class="footer">
        © 2026 TotoBora • All Rights Reserved
    </div>

</div>

<!-- SCRIPT -->
<script>
    function togglePassword() {
        const password = document.getElementById("password");
        const toggle = document.querySelector(".toggle-password");

        if (password.type === "password") {
            password.type = "text";
            toggle.textContent = "Hide";
        } else {
            password.type = "password";
            toggle.textContent = "Show";
        }
    }
</script>

</body>
</html>