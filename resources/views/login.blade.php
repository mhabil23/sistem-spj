<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login | Sistem Informasi Penyelesaian Tagihan</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite([
        'resources/css/login.css',
        'resources/js/app.js'
    ])
</head>

<body class="etoile-login-body">

    <!-- Abstract Floating Transparent BPS Logos Background (22 scattered nodes) -->
    <div class="bg-bps-decor-container" aria-hidden="true">
        @for ($i = 1; $i <= 22; $i++)
            <img src="{{ asset('images/logo-bps-app.png') }}" alt="" class="bg-bps-logo bps-node-{{ $i }}">
        @endfor
    </div>

    <main class="water-login-page">

        <!-- Outer Wrapper -->
        <div class="water-scene-wrapper">

            <!-- Glassmorphic Neumorphic Card -->
            <div class="water-glass-card">

                <!-- 3D Water Orb with BPS Logo at Top Center -->
                <div class="avatar-droplet-wrapper">
                    <div class="avatar-glass-dome">
                        <div class="avatar-liquid-sphere">
                            <div class="avatar-specular-arc"></div>
                            <img src="{{ asset('images/logo-bps-app.png') }}" alt="Logo BPS" class="avatar-bps-logo">
                        </div>
                    </div>
                </div>

                <!-- Header -->
                <div class="login-heading-area">
                    <h1 class="login-main-title">Login</h1>
                    <p class="login-main-subtitle">Welcome Back! Please login to continue.</p>
                </div>

                <!-- Error Messages Box -->
                @if (isset($errors) && $errors->any())
                    <div class="water-alert-error">
                        <svg class="alert-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="12" y1="8" x2="12" y2="12"></line>
                            <line x1="12" y1="16" x2="12.01" y2="16"></line>
                        </svg>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Authentication Form (Preserving all routes, CSRF, input names) -->
                <form action="{{ route('login') }}" method="POST" id="mainLoginForm">
                    @csrf

                    <!-- USER ID / EMAIL -->
                    <div class="water-field-container">
                        <div class="water-field-box">
                            <span class="field-icon-box">
                                <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="12" cy="7" r="4"></circle>
                                </svg>
                            </span>
                            <input
                                type="email"
                                id="email"
                                name="email"
                                value="{{ old('email') }}"
                                placeholder="User ID"
                                autocomplete="email"
                                required>
                        </div>
                    </div>

                    <!-- PASSWORD -->
                    <div class="water-field-container">
                        <div class="water-field-box">
                            <span class="field-icon-box">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                    <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                                </svg>
                            </span>
                            <input
                                type="password"
                                id="password"
                                name="password"
                                placeholder="Password"
                                autocomplete="current-password"
                                required>
                            <button
                                type="button"
                                class="password-eye-btn"
                                onclick="togglePassword()"
                                title="Lihat password"
                                aria-label="Toggle password visibility">
                                <svg id="eyeSvg" width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- SUBMIT BUTTON (Glossy 3D Orange Liquid Capsule) -->
                    <button type="submit" class="water-login-button" id="submitBtn">
                        <div class="btn-highlight-streak"></div>
                        <span class="btn-icon-symbol">
                            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4M10 17l5-5-5-5M15 12H3"/>
                            </svg>
                        </span>
                        <span class="btn-caption">Login</span>
                    </button>

                </form>

            </div>
        </div>

    </main>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeSvg = document.getElementById('eyeSvg');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeSvg.innerHTML = '<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line>';
            } else {
                passwordInput.type = 'password';
                eyeSvg.innerHTML = '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle>';
            }
        }
    </script>

</body>

</html>