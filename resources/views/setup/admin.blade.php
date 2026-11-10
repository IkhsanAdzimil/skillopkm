<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- Title sengaja dibuat generik agar tidak mencolok --}}
    <title>Skillo Academy</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('images/skillo-logo.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
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
            padding: 24px 16px;
        }

        .setup-card {
            border-radius: 24px;
            overflow: hidden;
            border: 1px solid rgba(238, 76, 55, 0.15);
            background: #ffffff;
            box-shadow: 0 25px 50px -12px rgba(7, 11, 31, 0.12);
            width: 100%;
            max-width: 480px;
        }

        .setup-header {
            background: radial-gradient(circle at center, rgba(238, 76, 55, 0.14) 0%, transparent 70%), var(--skillo-dark);
            padding: 32px 36px;
            text-align: center;
            border-bottom: 3px solid var(--skillo-primary);
        }

        .setup-header img {
            height: 48px;
            margin-bottom: 12px;
        }

        .setup-header h4 {
            font-family: 'Montserrat', sans-serif;
            font-weight: 800;
            color: #ffffff;
            margin-bottom: 4px;
            font-size: 1.25rem;
        }

        .setup-header p {
            color: rgba(255,255,255,0.5);
            font-size: 0.8rem;
            margin: 0;
        }

        .setup-body {
            padding: 32px 36px;
        }

        .form-label {
            font-weight: 600;
            font-size: 0.85rem;
            color: #374151;
        }

        .form-control, .form-select {
            border-radius: 10px;
            border: 1.5px solid #e5e7eb;
            padding: 10px 14px;
            font-size: 0.9rem;
            transition: border-color 0.2s;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--skillo-primary);
            box-shadow: 0 0 0 3px rgba(238, 76, 55, 0.1);
        }

        .form-control.is-invalid {
            border-color: #ef4444;
        }

        .btn-primary-skillo {
            background-color: var(--skillo-primary);
            border: none;
            border-radius: 10px;
            color: white;
            font-weight: 700;
            padding: 12px;
            font-size: 0.95rem;
            width: 100%;
            transition: background-color 0.2s, transform 0.1s;
        }

        .btn-primary-skillo:hover {
            background-color: var(--skillo-primary-hover);
            transform: translateY(-1px);
        }

        .btn-primary-skillo:active {
            transform: translateY(0);
        }

        .alert-success-custom {
            background: #f0fdf4;
            border: 1.5px solid #86efac;
            border-radius: 12px;
            color: #166534;
            padding: 12px 16px;
            font-size: 0.875rem;
            font-weight: 600;
        }

        .role-badge-admin {
            background: rgba(238, 76, 55, 0.1);
            color: var(--skillo-primary);
            border: 1.5px solid rgba(238, 76, 55, 0.3);
        }

        .role-badge-guru {
            background: rgba(99, 102, 241, 0.1);
            color: #4f46e5;
            border: 1.5px solid rgba(99, 102, 241, 0.3);
        }

        .invalid-feedback {
            font-size: 0.8rem;
        }

        .divider {
            border-top: 1px solid #f3f4f6;
            margin: 20px 0;
        }

        .link-login {
            color: var(--skillo-primary);
            text-decoration: none;
            font-weight: 600;
            font-size: 0.875rem;
        }

        .link-login:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="setup-card">
    {{-- Header --}}
    <div class="setup-header">
        <img src="{{ asset('images/skillo-logo.png') }}" alt="Skillo Academy">
        <h4>Setup Akun</h4>
        <p>Buat akun Admin atau Guru baru</p>
    </div>

    {{-- Body --}}
    <div class="setup-body">

        {{-- Pesan sukses --}}
        @if(session('success'))
            <div class="alert-success-custom mb-3 d-flex align-items-center gap-2">
                <i class="bi bi-check-circle-fill"></i>
                {{ session('success') }}
            </div>
        @endif

        {{-- Daftar akun yang sudah dibuat --}}
        @php
            $admins = \App\Models\User::where('role', 'admin')->orderBy('created_at', 'desc')->get();
            $gurus  = \App\Models\User::where('role', 'guru')->orderBy('created_at', 'desc')->get();
        @endphp

        @if($admins->isNotEmpty() || $gurus->isNotEmpty())
        <div class="mb-4">
            <p class="fw-bold text-secondary mb-2" style="font-size:0.8rem; text-transform:uppercase; letter-spacing:0.05em;">
                Akun yang ada di sistem
            </p>
            <div class="d-flex flex-wrap gap-2">
                @foreach($admins as $a)
                    <span class="badge role-badge-admin px-2 py-1 rounded-pill" style="font-size:0.78rem; font-weight:600;">
                        <i class="bi bi-shield-fill-check me-1"></i>{{ $a->username }}
                    </span>
                @endforeach
                @foreach($gurus as $g)
                    <span class="badge role-badge-guru px-2 py-1 rounded-pill" style="font-size:0.78rem; font-weight:600;">
                        <i class="bi bi-person-fill me-1"></i>{{ $g->username }}
                    </span>
                @endforeach
            </div>
        </div>
        <div class="divider"></div>
        @endif

        {{-- Form buat akun baru --}}
        <form action="{{ route('setup.admin.store', ['token' => $token]) }}" method="POST" novalidate>
            @csrf

            {{-- Nama Lengkap --}}
            <div class="mb-3">
                <label class="form-label">Nama Lengkap</label>
                <input type="text" name="name"
                       class="form-control @error('name') is-invalid @enderror"
                       value="{{ old('name') }}"
                       placeholder="Contoh: Administrator Utama">
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Username --}}
            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" name="username"
                       class="form-control @error('username') is-invalid @enderror"
                       value="{{ old('username') }}"
                       placeholder="Contoh: admin_skillo">
                @error('username')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Email --}}
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email"
                       class="form-control @error('email') is-invalid @enderror"
                       value="{{ old('email') }}"
                       placeholder="Contoh: admin@skillo.id">
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- No HP --}}
            <div class="mb-3">
                <label class="form-label">No HP <span class="text-secondary fw-normal">(opsional)</span></label>
                <input type="text" name="no_hp"
                       class="form-control @error('no_hp') is-invalid @enderror"
                       value="{{ old('no_hp') }}"
                       placeholder="08xxxxxxxxxx">
                @error('no_hp')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Role --}}
            <div class="mb-3">
                <label class="form-label">Role Akun</label>
                <select name="role" class="form-select @error('role') is-invalid @enderror">
                    <option value="admin" {{ old('role', 'admin') === 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="guru"  {{ old('role') === 'guru' ? 'selected' : '' }}>Guru</option>
                </select>
                @error('role')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Password --}}
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password"
                       class="form-control @error('password') is-invalid @enderror"
                       placeholder="Minimal 8 karakter">
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Konfirmasi Password --}}
            <div class="mb-4">
                <label class="form-label">Konfirmasi Password</label>
                <input type="password" name="password_confirmation"
                       class="form-control"
                       placeholder="Ulangi password">
            </div>

            <button type="submit" class="btn-primary-skillo">
                <i class="bi bi-person-plus-fill me-2"></i>Buat Akun
            </button>
        </form>

        <div class="divider"></div>

        <div class="text-center">
            <a href="{{ route('login') }}" class="link-login">
                <i class="bi bi-box-arrow-in-right me-1"></i>Pergi ke halaman Login
            </a>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
