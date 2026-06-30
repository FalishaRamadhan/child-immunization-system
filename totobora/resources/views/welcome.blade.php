<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>TotoBora - Child Immunization System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="/favicon.ico" sizes="32x32">

    <style>
        :root {
            --green: #1f7a5a;
            --dark-green: #155c43;
            --light: #f4f7f6;
            --text: #2d3436;
        }

        body {
            margin: 0;
            font-family: 'Segoe UI', Arial, sans-serif;
            background: white;
            color: var(--text);
            scroll-behavior: smooth;
        }

        /* NAV */
        .nav {
            display: flex;
            justify-content: space-between;
            padding: 18px 40px;
            align-items: center;
            background: white;
            border-bottom: 1px solid #eee;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .logo img {
            height: 42px;
        }

        .nav a {
            margin-left: 18px;
            text-decoration: none;
            color: var(--text);
            font-weight: 500;
        }

        .btn {
            background: var(--green);
            color: white;
            padding: 10px 16px;
            border-radius: 8px;
            text-decoration: none;
            transition: 0.3s;
        }

        .btn:hover {
            background: var(--dark-green);
            transform: scale(1.05);
        }

        /* HERO */
        .hero {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 80px 40px;
            background: linear-gradient(135deg, #e8f5ef, #ffffff);
        }

        .hero-text {
            max-width: 520px;
        }

        .hero-text h1 {
            font-size: 42px;
            color: var(--dark-green);
        }

        .hero-text p {
            color: #555;
            line-height: 1.6;
        }

        .hero-buttons {
            margin-top: 25px;
            display: flex;
            gap: 12px;
        }

        .btn-outline {
            border: 2px solid var(--green);
            padding: 10px 16px;
            border-radius: 8px;
            color: var(--green);
            text-decoration: none;
            cursor: pointer;
            transition: 0.3s;
        }

        .btn-outline:hover {
            background: var(--green);
            color: white;
        }

        /* SECTIONS */
        .section {
            padding: 60px 40px;
        }

        .title {
            text-align: center;
            color: var(--dark-green);
            margin-bottom: 30px;
        }

        /* FEATURES */
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px;
        }

        .card {
            padding: 22px;
            border-radius: 12px;
            background: white;
            box-shadow: 0 8px 20px rgba(0,0,0,0.05);
            cursor: pointer;
            transition: 0.3s;
        }

        .card:hover {
            transform: translateY(-8px) scale(1.03);
            background: var(--green);
            color: white;
        }

        .card h3 {
            margin-bottom: 10px;
        }

        .card:hover h3,
        .card:hover p {
            color: white;
        }

        /* INTERACTIVE PANEL */
        .panel {
            display: none;
            margin-top: 30px;
            padding: 20px;
            border-radius: 12px;
            background: #e8f5ef;
            text-align: center;
            animation: fade 0.4s ease;
        }

        .panel.active {
            display: block;
        }

        @keyframes fade {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* FOOTER */
        .footer {
            text-align: center;
            padding: 25px;
            border-top: 1px solid #eee;
            color: #777;
        }

    </style>
</head>

<body>

<!-- NAV -->
<div class="nav">
    <div class="logo">
        <img src="{{ asset('images/totobora-logo.png') }}" alt="TotoBora Logo">
    </div>
</div>

<!-- HERO -->
<div class="hero">
    <div class="hero-text">
        <h1>Child Immunization<br>Made Simple</h1>
        <p>
            TotoBora helps caregivers and health facilities track vaccinations,
            manage appointments, and receive smart SMS reminders.
        </p>

        <div class="hero-buttons">
            <a href="{{ route('login') }}" class="btn" onclick="showPanel('start')">
                Login
            </a>
        </div>
    </div>
</div>

<!-- FEATURES -->
<div class="section" id="featureSection">
    <h2 class="title">Why TotoBora?</h2>

    <div class="grid">
        <div class="card">
            <h3>Smart Reminders</h3>
            <p>Automated SMS alerts for vaccines & appointments.</p>
        </div>

        <div class="card">
            <h3>Child Tracking</h3>
            <p>Full immunization history per child.</p>
        </div>

        <div class="card">
            <h3>Facility Management</h3>
            <p>Organized healthcare workflows per facility.</p>
        </div>

        <div class="card">
            <h3>Secure System</h3>
            <p>Role-based access for caregivers and staff.</p>
        </div>
    </div>
</div>

<!-- FOOTER -->
<div class="footer">
    &copy 2026 TotoBora
</div>

<!-- JS INTERACTION -->
<script>
    function showPanel(id) {
        document.querySelectorAll('.panel').forEach(p => p.classList.remove('active'));
        document.getElementById(id).classList.add('active');
    }
</script>

</body>
</html>