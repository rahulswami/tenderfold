<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CMS Admin Login | MLG Finedge</title>
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <style>
        body {
            background: linear-gradient(135deg, #071526 0%, #0b1e36 50%, #0072bc 100%);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            font-family: 'Outfit', sans-serif;
            color: #ffffff;
        }
        .login-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: var(--radius-lg);
            padding: 3rem;
            width: 100%;
            max-width: 450px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.3);
            text-align: center;
        }
        .login-logo {
            margin-bottom: 2rem;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }
        .login-logo svg {
            width: 48px;
            height: 48px;
        }
        .login-logo span {
            font-size: 1.5rem;
            font-weight: 700;
            letter-spacing: 1px;
            color: #ffffff;
        }
        .login-card h2 {
            font-size: 1.75rem;
            margin-bottom: 0.5rem;
            font-weight: 600;
        }
        .login-card p {
            color: rgba(255,255,255,0.6);
            margin-bottom: 2rem;
            font-size: 0.9rem;
        }
        .form-group {
            margin-bottom: 1.5rem;
            text-align: left;
        }
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-size: 0.85rem;
            color: rgba(255,255,255,0.8);
            font-weight: 500;
        }
        .form-control {
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.15);
            color: #ffffff;
            width: 100%;
            padding: 0.75rem 1rem;
            border-radius: var(--radius-md);
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }
        .form-control:focus {
            background: rgba(255, 255, 255, 0.12);
            border-color: var(--mint-green);
            outline: none;
            box-shadow: 0 0 10px rgba(92, 203, 179, 0.2);
        }
        .btn-login {
            background-color: var(--mint-green);
            color: var(--primary-teal-dark);
            font-weight: 600;
            padding: 0.85rem;
            width: 100%;
            border: none;
            border-radius: var(--radius-md);
            cursor: pointer;
            font-size: 1rem;
            margin-top: 1rem;
            transition: all 0.3s ease;
        }
        .btn-login:hover {
            background-color: #4ebfa7;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(92, 203, 179, 0.4);
        }
        .error-message {
            color: #ff6b6b;
            font-size: 0.85rem;
            margin-top: 0.5rem;
            text-align: left;
        }
    </style>
</head>
<body>

    <div class="login-card">
        <div class="login-logo">
            <svg viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect x="25" y="45" width="12" height="30" rx="3" fill="#ffffff" />
                <rect x="44" y="30" width="12" height="45" rx="3" fill="#ffffff" />
                <rect x="63" y="15" width="12" height="60" rx="3" fill="#5ccbb3" />
                <path d="M15 80 L35 60 L50 70 L85 35" stroke="#e85c24" stroke-width="6" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M72 35 H85 V48" stroke="#e85c24" stroke-width="6" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
            <span>MLG FINEDGE</span>
        </div>
        
        <h2>Welcome Back</h2>
        <p>Log in to access the Content Management System.</p>

        <form action="{{ route('login') }}" method="POST">
            @csrf
            
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" class="form-control" placeholder="admin@mlgfinedge.com" value="{{ old('email') }}" required autofocus>
                @error('email')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" class="form-control" placeholder="••••••••" required>
                @error('password')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn-login">Log In to Dashboard</button>
        </form>
    </div>

</body>
</html>
