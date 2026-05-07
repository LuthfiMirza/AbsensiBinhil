<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Bintaro Hill</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .login-page {
            min-height: 100vh;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f5f5f3;
            padding: 24px;
            font-family: 'Poppins', Arial, sans-serif;
            box-sizing: border-box;
        }

        .login-page *,
        .login-page *::before,
        .login-page *::after {
            box-sizing: border-box;
        }

        .login-card {
            width: 100%;
            max-width: 430px;
            background: #ffffff;
            border-radius: 28px;
            padding: 42px 36px 34px;
            box-shadow: 0 12px 35px rgba(0, 0, 0, 0.08);
        }

        .login-card .logo-wrapper {
            text-align: center;
            margin-bottom: 34px;
        }

        .login-card .logo-wrapper img {
            width: 235px;
            max-width: 80%;
            height: auto;
            display: inline-block;
        }

        .login-card .welcome {
            text-align: center;
            margin-bottom: 30px;
        }

        .login-card .welcome h1 {
            font-size: 34px;
            line-height: 1.2;
            font-weight: 700;
            color: #222222;
            margin: 0 0 10px;
        }

        .login-card .welcome p {
            font-size: 15px;
            color: #777777;
            margin: 0;
        }

        .login-card .alert-error {
            margin-bottom: 18px;
            padding: 12px 14px;
            border: 1px solid #f2b8b8;
            border-radius: 14px;
            background: #fff5f5;
            color: #b42318;
            font-size: 14px;
            line-height: 1.45;
        }

        .login-card .form-group {
            margin-bottom: 18px;
        }

        .login-card .input-wrapper {
            width: 100%;
            height: 60px;
            border: 1px solid #dddddd;
            border-radius: 16px;
            display: flex;
            align-items: center;
            padding: 0 18px;
            background: #ffffff;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .login-card .input-wrapper:focus-within {
            border-color: #5e6640;
            box-shadow: 0 0 0 3px rgba(94, 102, 64, 0.14);
        }

        .login-card .input-wrapper.has-error {
            border-color: #d92d20;
        }

        .login-card .input-wrapper svg {
            width: 20px;
            height: 20px;
            flex-shrink: 0;
            color: #888888;
        }

        .login-card .input-wrapper input {
            flex: 1;
            width: 100%;
            min-width: 0;
            height: 100%;
            border: 0;
            outline: none;
            box-shadow: none;
            background: transparent;
            padding: 0 12px;
            font-size: 16px;
            line-height: 1;
            color: #333333;
            font-family: inherit;
            appearance: none;
            -webkit-appearance: none;
        }

        .login-card .input-wrapper input:focus,
        .login-card .input-wrapper input:focus-visible,
        .login-card .input-wrapper input:active {
            border: 0;
            outline: none;
            box-shadow: none;
        }

        .login-card .input-wrapper input::placeholder {
            color: #9a9a9a;
        }

        .login-card .password-toggle {
            border: 0;
            outline: none;
            box-shadow: none;
            background: transparent;
            cursor: pointer;
            padding: 0;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #777777;
            flex-shrink: 0;
        }

        .login-card .password-toggle:focus,
        .login-card .password-toggle:focus-visible {
            outline: none;
            box-shadow: none;
        }

        .login-card .password-toggle:hover {
            color: #5e6640;
        }

        .login-card .password-toggle .is-hidden {
            display: none;
        }

        .login-card .field-error {
            margin: 8px 0 0;
            color: #d92d20;
            font-size: 13px;
            line-height: 1.4;
        }

        .login-card .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 14px;
            margin: 4px 0 26px;
            font-size: 14px;
        }

        .login-card .form-options label {
            color: #666666;
            display: flex;
            align-items: center;
            gap: 6px;
            white-space: nowrap;
        }

        .login-card .form-options input[type="checkbox"] {
            width: 15px;
            height: 15px;
            margin: 0;
            accent-color: #5e6640;
        }

        .login-card .form-options a {
            color: #5e6640;
            text-decoration: none;
            font-weight: 600;
            white-space: nowrap;
        }

        .login-card .form-options a:hover {
            color: #4c5333;
            text-decoration: underline;
        }

        .login-card .login-button {
            width: 100%;
            height: 58px;
            border: none;
            border-radius: 16px;
            background: #5e6640;
            color: #ffffff;
            font-size: 22px;
            font-weight: 700;
            cursor: pointer;
            transition: background 0.2s ease;
            font-family: inherit;
        }

        .login-card .login-button:hover {
            background: #4c5333;
        }

        .login-card .login-button:focus {
            outline: none;
            box-shadow: 0 0 0 4px rgba(94, 102, 64, 0.18);
        }

        .login-card .register-link {
            text-align: center;
            margin-top: 24px;
            color: #666666;
            font-size: 15px;
        }

        .login-card .register-link a,
        .login-card .register-link span {
            color: #222222;
            font-weight: 700;
            text-decoration: none;
        }

        .login-card .register-link a:hover {
            text-decoration: underline;
        }

        @media (max-width: 480px) {
            .login-page {
                padding: 18px;
            }

            .login-card {
                max-width: 100%;
                padding: 32px 22px 28px;
                border-radius: 24px;
            }

            .login-card .logo-wrapper {
                margin-bottom: 28px;
            }

            .login-card .logo-wrapper img {
                width: 210px;
            }

            .login-card .welcome h1 {
                font-size: 28px;
            }

            .login-card .form-options {
                align-items: flex-start;
                flex-direction: column;
                gap: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="login-page">
        <div class="login-card">
            <div class="logo-wrapper">
                <img src="{{ asset('images/logobintarohill.png') }}" alt="Bintaro Hill">
            </div>

            <div class="welcome">
                <h1>Selamat Datang</h1>
                <p>Silakan masuk untuk melanjutkan</p>
            </div>

            @if(session('error'))
                <div class="alert-error">{{ session('error') }}</div>
            @endif

            @if($errors->any())
                <div class="alert-error">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="form-group">
                    <div class="input-wrapper {{ $errors->has('email') ? 'has-error' : '' }}">
                        <svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 7.5a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.5 20.25a8.25 8.25 0 1115 0" />
                        </svg>
                        <input id="email"
                               type="text"
                               name="email"
                               value="{{ old('email') }}"
                               placeholder="Email atau Username"
                               required
                               autofocus
                               autocomplete="email">
                    </div>
                    @error('email')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <div class="input-wrapper {{ $errors->has('password') ? 'has-error' : '' }}">
                        <svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V7.875a4.5 4.5 0 00-9 0V10.5m-.75 0h10.5A1.75 1.75 0 0119 12.25v6A1.75 1.75 0 0117.25 20H6.75A1.75 1.75 0 015 18.25v-6a1.75 1.75 0 011.75-1.75z" />
                        </svg>
                        <input id="password"
                               type="password"
                               name="password"
                               placeholder="Password"
                               required
                               autocomplete="current-password">
                        <button type="button"
                                id="togglePassword"
                                class="password-toggle"
                                aria-label="Tampilkan password"
                                aria-pressed="false">
                            <svg id="eyeIcon" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12s3.75-6.75 9.75-6.75S21.75 12 21.75 12 18 18.75 12 18.75 2.25 12 2.25 12z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <svg id="eyeSlashIcon" class="is-hidden" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 3l18 18M10.58 10.58A2 2 0 0012 14a2 2 0 001.42-.58M9.88 5.52A9.16 9.16 0 0112 5.25c6 0 9.75 6.75 9.75 6.75a18.42 18.42 0 01-3.23 4.08M6.61 6.62C3.94 8.43 2.25 12 2.25 12S6 18.75 12 18.75c1.48 0 2.82-.4 4.02-1.02" />
                            </svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-options">
                    <label>
                        <input type="checkbox" name="remember">
                        <span>Ingat saya</span>
                    </label>

                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}">Lupa password?</a>
                    @endif
                </div>

                <button type="submit" class="login-button">Masuk</button>

                <div class="register-link">
                    Belum punya akun?
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}">Daftar</a>
                    @else
                        <span>Daftar</span>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const toggleButton = document.getElementById('togglePassword');
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');
            const eyeSlashIcon = document.getElementById('eyeSlashIcon');

            if (!toggleButton || !passwordInput || !eyeIcon || !eyeSlashIcon) {
                return;
            }

            toggleButton.addEventListener('click', () => {
                const isHidden = passwordInput.type === 'password';

                passwordInput.type = isHidden ? 'text' : 'password';
                toggleButton.setAttribute('aria-pressed', isHidden ? 'true' : 'false');
                toggleButton.setAttribute('aria-label', isHidden ? 'Sembunyikan password' : 'Tampilkan password');
                eyeIcon.classList.toggle('is-hidden', isHidden);
                eyeSlashIcon.classList.toggle('is-hidden', !isHidden);
            });
        });
    </script>
</body>
</html>
