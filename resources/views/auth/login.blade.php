<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>Login Staff — Skillo Academy</title>

    <!-- Google Fonts: Montserrat (Headings) & Plus Jakarta Sans (Body) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('images/skillo-logo.png') }}">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        :root {
            --skillo-primary: #ee4c37;
            --skillo-primary-hover: #d94330;
            --skillo-dark: #070B1F;
            --skillo-amber: #fcd34d;
            --skillo-bg: #FFF7E8;
        }

        body {
            background-color: var(--skillo-bg);
            font-family: 'Plus Jakarta Sans', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #0f172a;
            position: relative;
            overflow-x: hidden;
            padding: 24px 0;
        }

        /* Subtle background glow effect */
        body::before {
            content: '';
            position: absolute;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(238, 76, 55, 0.08) 0%, rgba(253, 211, 77, 0.08) 70%, transparent 100%);
            top: -100px;
            right: -100px;
            border-radius: 50%;
            z-index: 0;
            pointer-events: none;
        }

        .login-card {
            border-radius: 28px;
            overflow: hidden;
            border: 1px solid rgba(238, 76, 55, 0.12);
            background: #ffffff;
            box-shadow: 0 25px 50px -12px rgba(7, 11, 31, 0.12);
            position: relative;
            z-index: 1;
        }

        /* Sisi Kiri: Brand Skillo Academy */
        .login-brand-side {
            background: radial-gradient(circle at center, rgba(238, 76, 55, 0.12) 0%, transparent 70%), var(--skillo-dark);
            border-right: 3px solid var(--skillo-primary);
            position: relative;
            overflow: hidden;
        }

        .login-brand-side::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 260px;
            height: 260px;
            background-image: url('{{ asset("images/skillo-logo.png") }}');
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center;
            opacity: 0.05;
            pointer-events: none;
            z-index: 0;
        }

        @media (max-width: 767.98px) {
            .login-brand-side {
                border-right: none;
                border-bottom: 3px solid var(--skillo-primary);
            }
            .form-control {
                font-size: 16px !important; /* Mencegah auto-zoom pada browser iOS Safari / Android */
            }
        }

        /* Form area background watermark */
        .login-card-body {
            position: relative;
            background-color: #ffffff;
            overflow: hidden;
        }

        .login-card-body::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 310px;
            height: 310px;
            background-image: url('{{ asset("images/skillo-logo.png") }}');
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center;
            opacity: 0.08; /* Samar, elegan, dan proporsional */
            pointer-events: none;
            z-index: 0;
        }

        .login-content {
            position: relative;
            z-index: 1;
        }

        .btn-skillo-login {
            background-color: var(--skillo-primary);
            color: #ffffff;
            border-radius: 9999px;
            font-weight: 800;
            font-family: 'Montserrat', sans-serif;
            border: none;
            padding: 12px 24px;
            font-size: 0.95rem;
            transition: all 0.25s ease;
            box-shadow: 0 10px 20px -3px rgba(238, 76, 55, 0.35);
        }
        .btn-skillo-login:hover {
            background-color: var(--skillo-primary-hover);
            color: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 14px 24px -3px rgba(238, 76, 55, 0.45);
        }

        .form-control {
            border-radius: 12px;
            padding: 11px 16px;
            font-size: 0.9rem;
            border: 1.5px solid #e2e8f0;
            background-color: rgba(248, 250, 252, 0.85);
            transition: all 0.2s ease;
        }
        .form-control:focus {
            background-color: #ffffff;
            border-color: var(--skillo-primary);
            box-shadow: 0 0 0 4px rgba(238, 76, 55, 0.12);
        }

        .input-group-text {
            background-color: rgba(248, 250, 252, 0.85);
        }

        .skillo-badge-tag {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            border-radius: 9999px;
            border: 1px solid #fee2e2;
            background: #fff4f2;
            padding: 5px 14px;
            font-size: 0.72rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--skillo-primary);
        }
    </style>
</head>
<body>

<!-- Pop-up Toast Notifications (Skillo Academy - Atas Tengah Layar) -->
<div class="toast-container position-fixed top-0 start-50 translate-middle-x p-3" style="z-index: 99999; min-width: 360px; max-width: 90vw;">
    @if(session('success'))
        <div class="toast align-items-center border-0 mb-2 fade" role="alert" aria-live="assertive" aria-atomic="true"
             style="background: #070B1F; color: #ffffff; border-radius: 18px; border: 2px solid #10b981; box-shadow: 0 20px 30px -5px rgba(7, 11, 31, 0.45);">
            <div class="d-flex align-items-center justify-content-between p-3 border-bottom border-white border-opacity-10">
                <div class="d-flex align-items-center">
                    <span class="badge bg-amber-300 text-dark fw-black px-2.5 py-1.5 me-2" style="font-family: 'Montserrat', sans-serif; letter-spacing: 0.5px;">
                        <img src="{{ asset('images/skillo-logo.png') }}" alt="Logo" style="height: 16px; width: 16px; object-fit: contain; vertical-align: -2px;" class="me-1">SKILLO ACADEMY
                    </span>
                    <span class="badge bg-success text-white fw-bold">BERHASIL</span>
                </div>
                <button type="button" class="btn-close btn-close-white ms-2" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="p-3 d-flex align-items-center">
                <i class="bi bi-check-circle-fill text-success fs-3 me-3"></i>
                <div class="text-white fw-semibold" style="font-size: 0.95rem;">
                    {{ session('success') }}
                </div>
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="toast align-items-center border-0 mb-2 fade" role="alert" aria-live="assertive" aria-atomic="true"
             style="background: #070B1F; color: #ffffff; border-radius: 18px; border: 2px solid #ee4c37; box-shadow: 0 20px 30px -5px rgba(7, 11, 31, 0.45);">
            <div class="d-flex align-items-center justify-content-between p-3 border-bottom border-white border-opacity-10">
                <div class="d-flex align-items-center">
                    <span class="badge bg-amber-300 text-dark fw-black px-2.5 py-1.5 me-2" style="font-family: 'Montserrat', sans-serif; letter-spacing: 0.5px;">
                        <img src="{{ asset('images/skillo-logo.png') }}" alt="Logo" style="height: 16px; width: 16px; object-fit: contain; vertical-align: -2px;" class="me-1">SKILLO ACADEMY
                    </span>
                    <span class="badge bg-danger text-white fw-bold">PERHATIAN</span>
                </div>
                <button type="button" class="btn-close btn-close-white ms-2" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="p-3 d-flex align-items-center">
                <i class="bi bi-exclamation-circle-fill text-danger fs-3 me-3"></i>
                <div class="text-white fw-semibold" style="font-size: 0.95rem;">
                    {{ $errors->first() }}
                </div>
            </div>
        </div>
    @endif
</div>

<div class="container py-4 py-md-5">
    <div class="row justify-content-center">
        <div class="col-12 col-md-11 col-lg-10 col-xl-9" style="max-width: 940px;">
            <div class="card login-card">
                <div class="row g-0 align-items-stretch">
                    <!-- Sisi Kiri: Brand Skillo Academy -->
                    <div class="col-12 col-md-5 login-brand-side p-4 p-md-5 text-center text-white d-flex flex-column justify-content-center align-items-center">
                        <div class="login-brand-content position-relative" style="z-index: 1;">
                            <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3 shadow-sm"
                                 style="width: 76px; height: 76px; background: rgba(255, 255, 255, 0.05); border: 1.5px solid rgba(238, 76, 55, 0.4); padding: 8px;">
                                <img src="{{ asset('images/skillo-logo.png') }}" alt="Skillo Logo" style="width: 100%; height: 100%; object-fit: contain;">
                            </div>
                            <h3 class="fw-black mb-1" style="font-family: 'Montserrat', sans-serif; letter-spacing: 0.5px;">
                                SKILLO <span style="color: var(--skillo-primary);">ACADEMY</span>
                            </h3>
                            <p class="text-white-50 small mb-0 fw-medium">Portal Staff — Sistem Informasi & Absensi</p>
                        </div>
                    </div>

                    <!-- Sisi Kanan: Form Input Login -->
                    <div class="col-12 col-md-7 login-card-body p-4 p-md-5 d-flex flex-column justify-content-center">
                        <div class="login-content">
                            <div class="mb-4 text-center">
                                <span class="skillo-badge-tag">
                                    <i class="bi bi-lock-fill"></i> Autentikasi Staf
                                </span>
                            </div>

                            <form action="{{ route('login.post') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label fw-bold small text-secondary">Username Terdaftar</label>
                                    <div class="input-group">
                                        <span class="input-group-text border-end-0 text-muted" style="border-radius: 12px 0 0 12px;">
                                            <i class="bi bi-person-fill"></i>
                                        </span>
                                        <input type="text" name="username" class="form-control border-start-0"
                                               value="{{ old('username') }}" required autofocus placeholder="admin_skillo / budi_guru"
                                               style="border-radius: 0 12px 12px 0;" autocomplete="username">
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label fw-bold small text-secondary">Password Akun</label>
                                    <div class="input-group">
                                        <span class="input-group-text border-end-0 text-muted" style="border-radius: 12px 0 0 12px;">
                                            <i class="bi bi-shield-lock"></i>
                                        </span>
                                        <input type="password" name="password" id="passwordInput"
                                               class="form-control border-start-0 border-end-0"
                                               required placeholder="••••••••">
                                        <button class="input-group-text border-start-0 text-secondary" type="button"
                                                onclick="togglePassword()" style="border-radius: 0 12px 12px 0;">
                                            <i class="bi bi-eye" id="eyeIcon"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="remember" id="remember" style="cursor: pointer;">
                                        <label class="form-check-label small text-secondary fw-medium" for="remember" style="cursor: pointer;">
                                            Ingat saya di perangkat ini
                                        </label>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-skillo-login w-100 py-3">
                                    <i class="bi bi-box-arrow-in-right me-2"></i>Masuk ke Portal
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-center mt-4">
                <p class="text-secondary small fw-semibold mb-0" style="font-size: 0.8rem;">
                    &copy; {{ date('Y') }} PT Skillo Logic Indonesia. All rights reserved.
                </p>
                <small class="text-muted" style="font-size: 0.75rem;">Tempat Anak Belajar Coding, Desain & Teknologi</small>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function togglePassword() {
        const input = document.getElementById('passwordInput');
        const icon  = document.getElementById('eyeIcon');
        if (input.type === 'password') {
            input.type = 'text';
            icon.className = 'bi bi-eye-slash';
        } else {
            input.type = 'password';
            icon.className = 'bi bi-eye';
        }
    }

    // Inisialisasi dan tampilkan Pop-up Notifikasi Skillo Academy (otomatis hilang setelah 5 detik)
    document.addEventListener('DOMContentLoaded', function () {
        const toastElList = document.querySelectorAll('.toast');
        toastElList.forEach(function (toastEl) {
            const toast = new bootstrap.Toast(toastEl, {
                autohide: true,
                delay: 5000 // 5 detik
            });
            toast.show();
        });
    });
</script>
</body>
</html>
