<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login — Murazon</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" crossorigin="anonymous"/>

    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            min-height: 100vh;
            display: flex;
            background: #f8fafc;
            -webkit-font-smoothing: antialiased;
        }

        /* ── Left panel — matches sidebar gradient ── */
        .left-panel {
            width: 420px;
            flex-shrink: 0;
            background: linear-gradient(135deg, #1e40af 0%, #1e3a8a 100%);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 60px 48px;
            position: relative;
            overflow: hidden;
        }

        /* decorative circles */
        .left-panel::before,
        .left-panel::after {
            content: '';
            position: absolute;
            border-radius: 50%;
            background: rgba(255,255,255,0.05);
        }
        .left-panel::before { width: 320px; height: 320px; top: -80px; right: -80px; }
        .left-panel::after  { width: 240px; height: 240px; bottom: -60px; left: -60px; }

        .brand-logo {
            width: 64px; height: 64px;
            background: rgba(255,255,255,0.15);
            border-radius: 18px;
            display: flex; align-items: center; justify-content: center;
            font-size: 28px; color: white;
            margin-bottom: 28px;
            border: 1px solid rgba(255,255,255,0.2);
            position: relative; z-index: 1;
        }

        .brand-title {
            color: white;
            font-size: 28px;
            font-weight: 700;
            letter-spacing: -0.5px;
            position: relative; z-index: 1;
        }

        .brand-sub {
            color: rgba(255,255,255,0.6);
            font-size: 13px;
            margin-top: 6px;
            position: relative; z-index: 1;
        }

        .divider-line {
            width: 48px; height: 2px;
            background: rgba(255,255,255,0.25);
            border-radius: 2px;
            margin: 32px 0;
            position: relative; z-index: 1;
        }

        .feature-item {
            display: flex; align-items: center; gap: 12px;
            color: rgba(255,255,255,0.75);
            font-size: 13px;
            margin-bottom: 14px;
            position: relative; z-index: 1;
            align-self: flex-start;
        }

        .feature-item i {
            width: 28px; height: 28px;
            background: rgba(255,255,255,0.1);
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            font-size: 12px; color: #93c5fd;
            flex-shrink: 0;
        }

        /* ── Right panel — form ── */
        .right-panel {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 48px 32px;
        }

        .form-card {
            width: 100%;
            max-width: 400px;
        }

        .form-heading {
            font-size: 22px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 6px;
        }

        .form-sub {
            font-size: 13px;
            color: #94a3b8;
            margin-bottom: 32px;
        }

        .error-box {
            background: #fef2f2;
            border: 1px solid #fecaca;
            border-radius: 10px;
            padding: 12px 16px;
            color: #dc2626;
            font-size: 13px;
            display: flex; align-items: center; gap: 10px;
            margin-bottom: 24px;
        }

        .field { margin-bottom: 20px; }

        .field label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 8px;
        }

        .input-wrap {
            position: relative;
        }

        .input-wrap i {
            position: absolute;
            left: 14px; top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 14px;
        }

        .input-wrap input {
            width: 100%;
            padding: 11px 14px 11px 40px;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            font-family: inherit;
            font-size: 14px;
            color: #1e293b;
            background: white;
            transition: border-color 0.2s, box-shadow 0.2s;
            outline: none;
        }

        .input-wrap input:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59,130,246,0.12);
        }

        .btn-login {
            width: 100%;
            padding: 13px;
            background: linear-gradient(135deg, #1e40af 0%, #1e3a8a 100%);
            color: white;
            font-family: inherit;
            font-size: 14px;
            font-weight: 600;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            transition: opacity 0.2s, transform 0.15s;
            margin-top: 8px;
            display: flex; align-items: center; justify-content: center; gap: 8px;
        }

        .btn-login:hover  { opacity: 0.92; transform: translateY(-1px); }
        .btn-login:active { transform: translateY(0); }

        .back-link {
            display: inline-flex; align-items: center; gap: 6px;
            margin-top: 24px;
            font-size: 13px;
            color: #64748b;
            text-decoration: none;
            transition: color 0.2s;
        }
        .back-link:hover { color: #1e40af; }

        @media (max-width: 640px) {
            .left-panel { display: none; }
        }
    </style>
</head>
<body>

    {{-- Left branding panel --}}
    <div class="left-panel">
        <div class="brand-logo"><i class="fas fa-shield-halved"></i></div>
        <div class="brand-title">Murazon</div>
        <div class="brand-sub">Admin Panel</div>

        <div class="divider-line"></div>

        <div class="feature-item">
            <i class="fas fa-box"></i>
            Kelola produk & stok
        </div>
        <div class="feature-item">
            <i class="fas fa-clipboard-check"></i>
            Verifikasi pesanan masuk
        </div>
        <div class="feature-item">
            <i class="fas fa-chart-line"></i>
            Pantau revenue & statistik
        </div>
    </div>

    {{-- Right form panel --}}
    <div class="right-panel">
        <div class="form-card">
            <p class="form-heading">Selamat Datang </p>
            <p class="form-sub">Masuk ke panel admin Murazon</p>

            @if($errors->any())
                <div class="error-box">
                    <i class="fas fa-exclamation-circle"></i>
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('admin.login.post') }}">
                @csrf

                <div class="field">
                    <label for="email">Email</label>
                    <div class="input-wrap">
                        <i class="fas fa-envelope"></i>
                        <input type="email" id="email" name="email"
                            value="{{ old('email') }}"
                            placeholder="admin@example.com"
                            required autofocus>
                    </div>
                </div>

                <div class="field">
                    <label for="password">Password</label>
                    <div class="input-wrap">
                        <i class="fas fa-lock"></i>
                        <input type="password" id="password" name="password"
                            placeholder="••••••••"
                            required>
                    </div>
                </div>

                <button type="submit" class="btn-login">
                    <i class="fas fa-right-to-bracket"></i>
                    Masuk ke Dashboard
                </button>
            </form>

            <a href="/" class="back-link">
                <i class="fas fa-arrow-left"></i>
                Kembali ke toko
            </a>
        </div>
    </div>

</body>
</html>