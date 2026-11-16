<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>@yield('title', 'Portal Staff') — Skillo Academy</title>

    <!-- Google Fonts: Montserrat (Headings) & Plus Jakarta Sans (Body) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;600;700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
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
            --skillo-dark-surface: #0f172a;
            --skillo-amber: #fcd34d;
            --skillo-amber-light: #fff9e8;
            --skillo-bg: #FFF7E8;
            --skillo-card-bg: #ffffff;
            --skillo-sky: #0f8cc9;
            --skillo-emerald: #10b981;
        }

        body {
            background-color: var(--skillo-bg);
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            color: #0f172a;
            min-height: 100vh;
            padding-bottom: env(safe-area-inset-bottom);
        }

        h1, h2, h3, h4, h5, h6, .font-monsserat {
            font-family: 'Montserrat', sans-serif;
            font-weight: 800;
            color: var(--skillo-dark);
            letter-spacing: -0.02em;
        }

        /* Skillo Button Styles */
        .btn-skillo-primary {
            background-color: var(--skillo-primary);
            color: #ffffff;
            border-radius: 9999px;
            font-weight: 800;
            border: none;
            padding: 8px 20px;
            font-size: 0.875rem;
            transition: all 0.25s ease;
            box-shadow: 0 4px 14px 0 rgba(238, 76, 55, 0.28);
        }
        .btn-skillo-primary:hover {
            background-color: var(--skillo-primary-hover);
            color: #ffffff;
            transform: translateY(-1.5px);
            box-shadow: 0 6px 18px 0 rgba(238, 76, 55, 0.4);
        }

        .btn-skillo-dark {
            background-color: var(--skillo-dark);
            color: #ffffff;
            border-radius: 9999px;
            font-weight: 700;
            border: none;
            padding: 8px 18px;
            font-size: 0.875rem;
            transition: all 0.25s ease;
            box-shadow: 0 4px 12px 0 rgba(7, 11, 31, 0.2);
        }
        .btn-skillo-dark:hover {
            background-color: var(--skillo-primary);
            color: #ffffff;
            transform: translateY(-1.5px);
        }

        .btn-skillo-amber {
            background-color: var(--skillo-amber);
            color: var(--skillo-dark);
            border-radius: 9999px;
            font-weight: 800;
            border: none;
            padding: 8px 18px;
            font-size: 0.875rem;
            transition: all 0.25s ease;
        }
        .btn-skillo-amber:hover {
            background-color: #fde68a;
            color: var(--skillo-dark);
            transform: translateY(-1.5px);
        }

        .btn-skillo-outline {
            border: 1.5px solid #e2e8f0;
            background: #ffffff;
            color: #1e293b;
            border-radius: 9999px;
            font-weight: 700;
            padding: 7px 18px;
            font-size: 0.875rem;
            transition: all 0.25s ease;
        }
        .btn-skillo-outline:hover {
            border-color: var(--skillo-primary);
            color: var(--skillo-primary);
            background: #fff4f2;
            transform: translateY(-1.5px);
        }

        /* Pill Badge Tag ala Skillo Website */
        .skillo-tag {
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

        .skillo-tag-amber {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            border-radius: 9999px;
            background: #fef3c7;
            padding: 5px 14px;
            font-size: 0.72rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #92400e;
        }

        /* Navbar & Sidebar */
        .skillo-navbar {
            background: var(--skillo-dark) !important;
            border-bottom: 2px solid rgba(238, 76, 55, 0.25);
            box-shadow: 0 4px 20px -2px rgba(7, 11, 31, 0.35);
        }

        .sidebar {
            width: 250px;
            min-height: calc(100vh - 65px);
            background: #ffffff;
            border-right: 1px solid rgba(226, 232, 240, 0.8);
            box-shadow: 4px 0 20px -5px rgba(15, 23, 42, 0.03);
            flex-shrink: 0;
        }
        .sidebar .nav-link {
            color: #475569;
            border-radius: 9999px;
            padding: 10px 18px;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.2s ease;
            margin-bottom: 4px;
        }
        .sidebar .nav-link:hover {
            background: #fff9e8;
            color: var(--skillo-dark);
            transform: translateX(3px);
        }
        .sidebar .nav-link.active {
            background: #fff4f2;
            color: var(--skillo-primary) !important;
            font-weight: 800;
            border: 1px solid #fee2e2;
        }
        .sidebar .nav-link i {
            width: 22px;
            margin-right: 8px;
        }

        .content-area {
            flex: 1;
            padding: 28px;
            overflow-x: hidden;
        }

        /* Card System */
        .card {
            border: 1px solid rgba(226, 232, 240, 0.8);
            border-radius: 20px;
            background: #ffffff;
            box-shadow: 0 4px 20px -2px rgba(15, 23, 42, 0.04);
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }
        .card-header {
            background: #ffffff;
            border-bottom: 1px solid #f1f5f9;
            border-radius: 20px 20px 0 0 !important;
            padding: 1.15rem 1.4rem;
        }

        .avatar-sm {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            object-fit: cover;
        }

        /* Status Colors Presisi */
        .status-hadir  { color: #059669; font-weight: 800; }
        .status-sakit  { color: #d97706; font-weight: 800; }
        .status-izin   { color: #0284c7; font-weight: 800; }
        .status-alfa   { color: var(--skillo-primary); font-weight: 800; }

        /* Modal Redesign */
        .modal-content {
            border-radius: 24px;
            overflow: hidden;
            border: none;
            box-shadow: 0 25px 50px -12px rgba(7, 11, 31, 0.25);
        }

        /* Smooth Touch Scrolling (iOS & Android) */
        .table-responsive {
            -webkit-overflow-scrolling: touch;
        }

        /* Tablet (iPad, Galaxy Tab, 768px - 991px) */
        @media (min-width: 768px) and (max-width: 991.98px) {
            .sidebar {
                width: 210px;
                padding: 1rem !important;
            }
            .sidebar .nav-link {
                font-size: 0.825rem;
                padding: 8px 12px;
            }
            .sidebar .nav-link i {
                width: 18px;
                margin-right: 6px;
            }
            .content-area {
                padding: 20px;
            }
        }

        /* Mobile (Android & iOS Phones < 768px) */
        @media (max-width: 767.98px) {
            .sidebar {
                display: none !important;
            }
            .content-area {
                padding: 14px;
            }
            .navbar-brand img {
                height: 32px !important;
                width: 32px !important;
            }
            .navbar-brand span.fs-4 {
                font-size: 1.15rem !important;
            }
            .badge-portal-staf {
                display: none !important;
            }
            input, select, textarea, .form-control, .form-select {
                font-size: 16px !important;
            }
        }

        /* Scrollbar Halus & Rapi untuk Modal Scrollable */
        .modal-dialog-scrollable .modal-body {
            scrollbar-width: thin;
            scrollbar-color: rgba(7, 11, 31, 0.25) transparent;
        }
        .modal-dialog-scrollable .modal-body::-webkit-scrollbar {
            width: 6px;
        }
        .modal-dialog-scrollable .modal-body::-webkit-scrollbar-track {
            background: transparent;
        }
        .modal-dialog-scrollable .modal-body::-webkit-scrollbar-thumb {
            background-color: rgba(7, 11, 31, 0.25);
            border-radius: 9999px;
        }
        .modal-dialog-scrollable .modal-body::-webkit-scrollbar-thumb:hover {
            background-color: rgba(7, 11, 31, 0.45);
        }
    </style>

    @stack('styles')
</head>
<body>

<!-- NAVBAR (Skillo Brand Dark Header) -->
<nav class="navbar navbar-expand-md navbar-dark skillo-navbar px-3 px-lg-4 sticky-top">
    <div class="container-fluid px-0">
        <a class="navbar-brand d-flex align-items-center py-2" href="{{ route('dashboard') }}">
            <img src="{{ asset('images/skillo-logo.png') }}" alt="Skillo Logo" style="height: 38px; width: 38px; object-fit: contain;" class="me-2.5">
            <span class="fs-4 fw-black text-white" style="font-family: 'Montserrat', sans-serif; letter-spacing: 0.5px;">
                SKILLO <span style="color: var(--skillo-primary);">ACADEMY</span>
            </span>
            <span class="badge rounded-pill bg-amber-300 text-light fw-bold ms-2 px-2.5 py-1 badge-portal-staf" style="font-size: 0.65rem; letter-spacing: 0.5px;">
                PORTAL STAFF
            </span>
        </a>

        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navContent">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navContent">
            <!-- Menu Navigasi Mobile -->
            <ul class="navbar-nav me-auto d-md-none mb-3 border-top border-white border-opacity-10 pt-3 mt-3">
                @if(auth()->user()->role === 'admin')
                    <li class="nav-item">
                        <a class="nav-link py-2 {{ request()->routeIs('admin.dashboard') ? 'text-warning fw-bold' : 'text-white' }}" href="{{ route('admin.dashboard') }}">
                            <i class="bi bi-grid-fill me-2"></i>Dashboard Admin
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link py-2 text-white" href="{{ route('admin.dashboard') }}#sectionRekapMingguan">
                            <i class="bi bi-calendar-week-fill me-2"></i>Rekap Mingguan
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link py-2 text-white" href="#" data-bs-toggle="modal" data-bs-target="#modalExport">
                            <i class="bi bi-file-earmark-arrow-down-fill me-2"></i>Export Excel/PDF
                        </a>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link py-2 {{ request()->routeIs('guru.dashboard') ? 'text-warning fw-bold' : 'text-white' }}" href="{{ route('guru.dashboard') }}">
                            <i class="bi bi-journal-check me-2"></i>Jurnal & Sesi Mengajar
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link py-2 text-white" href="#" data-bs-toggle="modal" data-bs-target="#modalExport">
                            <i class="bi bi-file-earmark-arrow-down-fill me-2"></i>Export Rekap
                        </a>
                    </li>
                @endif
            </ul>

            <ul class="navbar-nav ms-auto align-items-center gap-2">
                <!-- Profil dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center gap-2 text-white p-1 pe-3 rounded-pill"
                       style="background: rgba(255, 255, 255, 0.08); border: 1px solid rgba(255, 255, 255, 0.12);"
                       href="#" data-bs-toggle="dropdown">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=ee4c37&color=fff&bold=true&rounded=true"
                             class="avatar-sm border border-2 border-white" alt="Avatar">
                        <div class="text-start lh-1 d-none d-sm-block">
                            <div class="fw-bold" style="font-size:.85rem; font-family: 'Montserrat', sans-serif;">{{ auth()->user()->name }}</div>
                            <small class="text-white-50" style="font-size:.7rem">{{ ucfirst(auth()->user()->role) }}</small>
                        </div>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 mt-2 p-2" style="min-width:230px; border-radius: 16px;">
                        <li>
                            <div class="dropdown-item-text p-2">
                                <div class="fw-bold text-dark" style="font-size:.85rem; font-family: 'Montserrat', sans-serif;">{{ auth()->user()->name }}</div>
                                <small class="text-muted">{{ auth()->user()->email }}</small>
                            </div>
                        </li>
                        <li><hr class="dropdown-divider my-1"></li>
                        <li>
                            <a class="dropdown-item py-2 fw-semibold rounded-3 text-secondary" href="#" data-bs-toggle="modal" data-bs-target="#modalProfil">
                                <i class="bi bi-person-gear me-2 text-primary"></i>Edit Profil
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item py-2 fw-semibold rounded-3 text-secondary" href="#" data-bs-toggle="modal" data-bs-target="#modalPassword">
                                <i class="bi bi-shield-lock me-2 text-warning"></i>Ganti Password
                            </a>
                        </li>
                        <li><hr class="dropdown-divider my-1"></li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button class="dropdown-item py-2 text-danger fw-bold rounded-3">
                                    <i class="bi bi-box-arrow-right me-2"></i>Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- LAYOUT -->
<div class="d-flex" style="min-height: calc(100vh - 65px)">

    <!-- SIDEBAR -->
    <div class="sidebar p-3 p-lg-4">
        <p class="text-uppercase fw-extrabold mb-3 ps-2" style="font-size:.7rem; letter-spacing:1px; color: #94a3b8;">Menu Navigasi</p>
        <ul class="nav flex-column gap-1">
            @if(auth()->user()->role === 'admin')
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                        <i class="bi bi-grid-fill"></i>Dashboard Admin
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.dashboard') }}#sectionRekapMingguan">
                        <i class="bi bi-calendar2-week-fill"></i>Rekap Mingguan
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#modalExport">
                        <i class="bi bi-file-earmark-arrow-down-fill"></i>Export Excel / PDF
                    </a>
                </li>
            @else
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('guru.dashboard') ? 'active' : '' }}" href="{{ route('guru.dashboard') }}">
                        <i class="bi bi-journal-check"></i>Jurnal & Sesi
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#modalExport">
                        <i class="bi bi-file-earmark-arrow-down-fill"></i>Export Rekap
                    </a>
                </li>
            @endif
            <li class="nav-item mt-2">
                <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#modalProfil">
                    <i class="bi bi-person-circle"></i>Profil Saya
                </a>
            </li>
        </ul>

        
    </div>

    <!-- CONTENT -->
    <div class="content-area position-relative">
        <!-- Pop-up Toast Notifications -->
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

            @if(session('error'))
                <div class="toast align-items-center border-0 mb-2 fade" role="alert" aria-live="assertive" aria-atomic="true"
                     style="background: #070B1F; color: #ffffff; border-radius: 18px; border: 2px solid #ee4c37; box-shadow: 0 20px 30px -5px rgba(7, 11, 31, 0.45);">
                    <div class="d-flex align-items-center justify-content-between p-3 border-bottom border-white border-opacity-10">
                        <div class="d-flex align-items-center">
                            <span class="badge bg-amber-300 text-dark fw-black px-2.5 py-1.5 me-2" style="font-family: 'Montserrat', sans-serif; letter-spacing: 0.5px;">
                                <img src="{{ asset('images/skillo-logo.png') }}" alt="Logo" style="height: 16px; width: 16px; object-fit: contain; vertical-align: -2px;" class="me-1">SKILLO ACADEMY
                            </span>
                            <span class="badge bg-danger text-white fw-bold">GAGAL</span>
                        </div>
                        <button type="button" class="btn-close btn-close-white ms-2" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                    <div class="p-3 d-flex align-items-center">
                        <i class="bi bi-exclamation-triangle-fill text-danger fs-3 me-3"></i>
                        <div class="text-white fw-semibold" style="font-size: 0.95rem;">
                            {{ session('error') }}
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
                    <div class="p-3 d-flex align-items-start">
                        <i class="bi bi-exclamation-circle-fill text-danger fs-3 me-3 mt-1"></i>
                        <div>
                            <ul class="mb-0 ps-3 text-white small fw-medium">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        @yield('content')
    </div>
</div>

<!-- ============================================================ -->
<!-- MODAL: Edit Profil -->
<!-- ============================================================ -->
<div class="modal fade" id="modalProfil" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header text-white" style="background: var(--skillo-dark);">
                <h6 class="modal-title fw-bold font-monsserat mb-0"><i class="bi bi-person-gear me-2 text-warning"></i>Edit Profil</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('profile.update') }}" method="POST">
                @csrf @method('POST')
                <div class="modal-body p-4">
                    <div class="text-center mb-4">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=ee4c37&color=fff&bold=true&rounded=true&size=80"
                             class="rounded-circle shadow-sm border border-3 border-light" width="80" height="80" alt="Avatar">
                        <div class="fw-bold mt-2 font-monsserat" style="font-size: 1.05rem;">{{ auth()->user()->name }}</div>
                        <span class="skillo-tag mt-1">{{ auth()->user()->role }}</span>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-secondary">Username</label>
                        <input type="text" class="form-control bg-light rounded-3" value="{{ auth()->user()->username }}" readonly>
                        <small class="text-muted">Username bersifat permanen.</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-secondary">Nama Lengkap</label>
                        <input type="text" name="name" class="form-control rounded-3" value="{{ auth()->user()->name }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-secondary">Email</label>
                        <input type="email" name="email" class="form-control rounded-3" value="{{ auth()->user()->email }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-secondary">Nomor WhatsApp</label>
                        <input type="text" name="no_hp" class="form-control rounded-3" value="{{ auth()->user()->no_hp }}" placeholder="Contoh: 08123456789">
                    </div>
                </div>
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-skillo-outline" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-skillo-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ============================================================ -->
<!-- MODAL: Ganti Password -->
<!-- ============================================================ -->
<div class="modal fade" id="modalPassword" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header text-white" style="background: var(--skillo-dark);">
                <h6 class="modal-title fw-bold font-monsserat mb-0"><i class="bi bi-shield-lock me-2 text-warning"></i>Ganti Password</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('profile.password') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-secondary">Password Lama</label>
                        <input type="password" name="current_password" class="form-control rounded-3" required placeholder="Masukkan password lama">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-secondary">Password Baru</label>
                        <input type="password" name="password" class="form-control rounded-3" required placeholder="Minimal 6 karakter">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-secondary">Konfirmasi Password Baru</label>
                        <input type="password" name="password_confirmation" class="form-control rounded-3" required placeholder="Ulangi password baru">
                    </div>
                </div>
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-skillo-outline" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-skillo-dark">Perbarui Password</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ============================================================ -->
<!-- MODAL: Export Rekap (Excel / PDF) -->
<!-- ============================================================ -->
<div class="modal fade" id="modalExport" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header text-white" style="background: var(--skillo-dark);">
                <h6 class="modal-title fw-bold font-monsserat mb-0"><i class="bi bi-file-earmark-arrow-down-fill me-2" style="color: var(--skillo-primary);"></i>Export File Rekap Absensi</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('export') }}" method="GET" id="formExport">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-secondary">Periode</label>
                        <select class="form-select rounded-3" name="periode" id="selectPeriode">
                            <option value="mingguan">Per Minggu (Mingguan)</option>
                            <option value="bulanan">Per Bulan (Bulanan)</option>
                        </select>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-bold small text-secondary">Dari Tanggal</label>
                            <input type="date" name="tanggal_mulai" class="form-control rounded-3" required
                                   value="{{ now()->startOfWeek()->format('Y-m-d') }}">
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-bold small text-secondary">Sampai Tanggal</label>
                            <input type="date" name="tanggal_akhir" class="form-control rounded-3" required
                                   value="{{ now()->endOfWeek()->format('Y-m-d') }}">
                        </div>
                    </div>
                    @if(auth()->user()->role === 'admin')
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-secondary">Filter Sekolah (Opsional)</label>
                            <select class="form-select rounded-3" name="sekolah_id">
                                <option value="">— Semua Sekolah —</option>
                                @foreach(\App\Models\Sekolah::orderBy('nama_sekolah')->get() as $sek)
                                    <option value="{{ $sek->id }}">{{ $sek->nama_sekolah }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-secondary">Filter Sesi (Opsional)</label>
                            <select class="form-select rounded-3" name="sesi_id">
                                <option value="">— Semua Sesi —</option>
                                @foreach(\App\Models\Sesi::with('sekolah')->orderBy('sekolah_id')->orderBy('id')->get() as $sesiOpt)
                                    <option value="{{ $sesiOpt->id }}">{{ $sesiOpt->sekolah?->nama_sekolah }} — {{ $sesiOpt->nama_sesi }} {{ $sesiOpt->tingkatan ? '(' . $sesiOpt->tingkatan . ')' : '' }}</option>
                                @endforeach
                            </select>
                        </div>
                    @else
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-secondary">Filter Sesi yang Diampu (Opsional)</label>
                            <select class="form-select rounded-3" name="sesi_id">
                                <option value="">— Semua Sesi Saya —</option>
                                @foreach(auth()->user()->sesi()->with('sekolah')->get() as $sesiOpt)
                                    <option value="{{ $sesiOpt->id }}">{{ $sesiOpt->sekolah?->nama_sekolah }} — {{ $sesiOpt->nama_sesi }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                </div>
                <div class="modal-footer bg-light border-0 gap-2">
                    <button type="button" class="btn btn-skillo-outline" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" name="format" value="excel" class="btn btn-skillo-primary">
                        <i class="bi bi-file-earmark-excel me-1"></i>Download Excel
                    </button>
                    <button type="submit" name="format" value="pdf" class="btn btn-skillo-dark">
                        <i class="bi bi-file-earmark-pdf me-1"></i>Download PDF
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const toastElList = document.querySelectorAll('.toast');
        toastElList.forEach(function (toastEl) {
            const toast = new bootstrap.Toast(toastEl, {
                autohide: true,
                delay: 5000
            });
            toast.show();
        });
    });
</script>

@stack('scripts')
</body>
</html>
