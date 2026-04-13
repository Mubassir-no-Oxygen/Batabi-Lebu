<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Batabi Lebu - Farmer Direct Market</title>
    <!-- Modern Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <!-- Boxicons -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <style>
        :root {
            --primary: #10b981;
            --primary-dark: #059669;
            --accent: #f59e0b;
            --bg-color: #f8fafc;
            --surface: #ffffff;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --border: rgba(16, 185, 129, 0.2);
            --transition: all 0.3s ease;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: var(--bg-color);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            
            /* Animated background gradient */
            background-image: 
                radial-gradient(at 0% 0%, hsla(152,70%,85%,0.5) 0, transparent 50%), 
                radial-gradient(at 100% 100%, hsla(38,90%,85%,0.5) 0, transparent 50%);
            background-attachment: fixed;
            padding: 2rem;
            text-align: center;
        }

        .hero-title {
            font-size: 4.5rem;
            font-weight: 800;
            background: linear-gradient(135deg, var(--primary) 0%, #047857 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 0.5rem;
            letter-spacing: -2px;
        }

        .hero-subtitle {
            font-size: 1.5rem;
            color: var(--text-muted);
            margin-bottom: 3rem;
            max-width: 600px;
            font-weight: 400;
        }

        .action-container {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.5);
            padding: 3rem;
            border-radius: 24px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.05);
            max-width: 500px;
            width: 100%;
        }

        .btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            width: 100%;
            padding: 1.25rem 2rem;
            border-radius: 16px;
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
            font-size: 1.2rem;
            text-decoration: none;
            transition: var(--transition);
            margin-bottom: 1.5rem;
            color: white;
            border: none;
            cursor: pointer;
        }

        .btn-register {
            background: linear-gradient(135deg, var(--primary) 0%, #14b8a6 100%);
            box-shadow: 0 10px 20px -5px rgba(16, 185, 129, 0.4);
        }

        .btn-register:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 25px -5px rgba(16, 185, 129, 0.5);
        }

        .btn-login {
            background: transparent;
            color: var(--text-main);
            border: 2px solid var(--border);
            font-weight: 600;
        }

        .btn-login:hover {
            background: rgba(16, 185, 129, 0.1);
            border-color: var(--primary);
        }
    </style>
</head>
<body>

    <i class='bx bxl-sketch' style="font-size: 5rem; color: var(--primary); margin-bottom: -10px;"></i>
    <h1 class="hero-title">Batabi Lebu</h1>
    <h2 class="hero-subtitle">The direct connection between Bangladesh's farmers and bulk buyers. No middlemen.</h2>

    <div class="action-container">
        <h3 style="font-size: 1.5rem; margin-bottom: 2rem;">Get Started</h3>
        
        <a href="{{ route('register') }}" class="btn btn-register">
            <i class='bx bx-user-plus'></i> Create Free Account
        </a>
        
        <a href="{{ route('login') }}" class="btn btn-login">
            <i class='bx bx-log-in-circle'></i> Sign Into Portal
        </a>
    </div>

    <p style="margin-top: 3rem; color: var(--text-muted); font-size: 0.9rem;">
        Securely authenticated via standard MVC Architecture.
    </p>

</body>
</html>
