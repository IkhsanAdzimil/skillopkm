@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')

{{-- Sapaan & Tanggal --}}
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
    <div>
        <div class="d-flex align-items-center gap-2 mb-2">
            <span class="skillo-tag">
                <i class="bi bi-mortarboard-fill"></i> Admin Console
            </span>
            <span class="text-secondary small fw-semibold" id="currentDate"></span>
        </div>
        <h2 class="fw-black mb-0 font-monsserat" style="font-size: 1.85rem; letter-spacing: -0.03em;">
            Selamat Datang, {{ auth()->user()->name }}! 👋
        </h2>
        <p class="text-secondary small mb-0 mt-1">Kelola sekolah, pembagian sesi belajar, multi-guru pengampu, impor murid Excel, dan rekapan absensi mingguan.</p>
    </div>
</div>

{{-- Statistik Card ala Skillo Company Web --}}
<div class="row g-3 mb-4">
    <div class="col-12 col-sm-6 col-md-4">
        <div class="card h-100 p-2">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-2xl d-flex align-items-center justify-content-center shrink-0"
                     style="width:56px; height:56px; background: #fff4f2; border: 1.5px solid #fee2e2; border-radius: 16px;">
                    <i class="bi bi-building-fill fs-3" style="color: var(--skillo-primary);"></i>
                </div>
                <div>
                    <span class="text-uppercase fw-bold text-secondary font-monsserat" style="font-size: 0.72rem; letter-spacing: 0.5px;">Sekolah & Sesi Aktif</span>
                    <div class="d-flex align-items-baseline gap-2">
                        <h3 class="fw-black mb-0 font-monsserat" style="color: var(--skillo-primary); font-size: 1.85rem;">{{ $totalSekolah }}</h3>
                        <span class="badge rounded-pill bg-light text-dark border fw-bold" style="font-size: 0.75rem;">{{ $totalSesi }} Sesi</span>
                    </div>
                    <small class="text-muted" style="font-size: 0.75rem;">Sekolah Mitra & Kelompok Sesi</small>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-md-4">
        <div class="card h-100 p-2">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-2xl d-flex align-items-center justify-content-center shrink-0"
                     style="width:56px; height:56px; background: #f0fdf4; border: 1.5px solid #bbf7d0; border-radius: 16px;">
                    <i class="bi bi-person-video3 fs-3" style="color: #059669;"></i>
                </div>
                <div class="flex-grow-1">
                    <span class="text-uppercase fw-extrabold text-secondary font-monsserat d-block" style="font-size: 0.75rem; letter-spacing: 0.75px;">TOTAL GURU</span>
                    <h3 class="fw-black mb-0 font-monsserat text-success" style="font-size: 1.85rem; line-height: 1.15;">{{ $totalGuru }}</h3>
                    <a href="javascript:void(0)" class="text-decoration-none fw-bold small text-primary d-inline-flex align-items-center gap-1 mt-1"
                       data-bs-toggle="modal" data-bs-target="#modalSemuaGuru" style="font-size: 0.8rem;">
                        Lihat Semua <i class="bi bi-arrow-right-short fs-6"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-12 col-md-4">
        <div class="card h-100 p-2">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-2xl d-flex align-items-center justify-content-center shrink-0"
                     style="width:56px; height:56px; background: #fff9e8; border: 1.5px solid #fef3c7; border-radius: 16px;">
                    <i class="bi bi-mortarboard-fill fs-3" style="color: #d97706;"></i>
                </div>
                <div class="flex-grow-1">
                    <span class="text-uppercase fw-extrabold text-secondary font-monsserat d-block" style="font-size: 0.75rem; letter-spacing: 0.75px;">TOTAL MURID</span>
                    <h3 class="fw-black mb-0 font-monsserat" style="color: #d97706; font-size: 1.85rem; line-height: 1.15;">{{ $totalSiswa }}</h3>
                    <a href="javascript:void(0)" class="text-decoration-none fw-bold small text-primary d-inline-flex align-items-center gap-1 mt-1"
                       data-bs-toggle="modal" data-bs-target="#modalSemuaMurid" style="font-size: 0.8rem;">
                        Lihat Semua <i class="bi bi-arrow-right-short fs-6"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ============================================================ --}}
{{-- DAFTAR SEKOLAH & PEMBAGIAN SESI (Sesi 1, Sesi 2, dst.) --}}
{{-- ============================================================ --}}
<div class="card mb-4">
    <div class="card-header d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
        <div>
            <h5 class="fw-black mb-0 font-monsserat" style="font-size: 1.15rem;">
                <i class="bi bi-building-fill me-2" style="color: var(--skillo-primary);"></i>Daftar Sekolah & Pembagian Sesi
            </h5>
            <small class="text-muted">Setiap sekolah dibagi menjadi beberapa sesi (Sesi 1, Sesi 2, dst.) dengan tingkatan, murid, dan multi-guru pengampu masing-masing</small>
        </div>
        <div class="d-flex align-items-center gap-2 flex-wrap">
            <button class="btn btn-skillo-dark" data-bs-toggle="modal" data-bs-target="#modalInputSiswa" data-default-metode="import">
                <i class="bi bi-file-earmark-excel-fill me-1 text-success"></i>Import Excel Murid
            </button>
            <button class="btn btn-skillo-amber" data-bs-toggle="modal" data-bs-target="#modalInputSiswa" data-default-metode="baru">
                <i class="bi bi-person-plus-fill me-1"></i>+ Tambah Murid
            </button>
            <button class="btn btn-skillo-primary" data-bs-toggle="modal" data-bs-target="#modalTambahSekolah">
                <i class="bi bi-plus-circle me-1"></i>+ Tambah Sekolah
            </button>
        </div>
    </div>

    <div class="card-body p-3 p-md-4">
        @if($sekolahList->isEmpty())
            <div class="text-center py-5 text-muted">
                <i class="bi bi-building fs-1 text-secondary opacity-50"></i>
                <p class="mt-2 fw-semibold">Belum ada data sekolah aktif saat ini.</p>
                <button class="btn btn-skillo-primary btn-sm mt-1" data-bs-toggle="modal" data-bs-target="#modalTambahSekolah">
                    + Buat Sekolah & Sesi Pertama
                </button>
            </div>
        @else
            <div class="d-flex flex-column gap-4">
                @foreach($sekolahList as $sekolah)
                @php
                    $totalMuridSekolah = $sekolah->sesi->sum('siswa_count');
                @endphp
                <div class="border rounded-4 overflow-hidden shadow-xs" style="border-color: #e2e8f0 !important;">
                    {{-- Header Sekolah --}}
                    <div class="p-3 px-4 d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3"
                         style="background: #f8fafc; border-bottom: 1.5px solid #e2e8f0;">
                        <div>
                            <div class="d-flex flex-wrap align-items-center gap-2">
                                <span class="badge rounded-pill text-white fw-bold px-3 py-1" style="background: var(--skillo-dark); font-size: 0.72rem;">
                                    <i class="bi bi-building me-1 text-warning"></i>SEKOLAH
                                </span>
                                <h5 class="fw-black font-monsserat mb-0 text-dark" style="font-size: 1.15rem;">
                                    {{ $sekolah->nama_sekolah }}
                                </h5>
                                <span class="badge rounded-pill bg-white text-secondary border fw-bold" style="font-size: 0.72rem;">
                                    {{ $sekolah->sesi->count() }} Sesi
                                </span>
                                <span class="badge rounded-pill bg-amber-300 text-dark fw-bold" style="background: #fef3c7; color: #92400e; font-size: 0.72rem;">
                                    <i class="bi bi-people-fill me-1"></i>{{ $totalMuridSekolah }} Murid
                                </span>
                            </div>
                            @if(!empty($sekolah->deskripsi))
                                <div class="text-secondary small mt-1">{{ $sekolah->deskripsi }}</div>
                            @endif
                        </div>

                        <div class="d-flex flex-wrap align-items-center gap-2">
                            {{-- Dropdown Filter Sesi di dalam Sekolah --}}
                            @if($sekolah->sesi->isNotEmpty())
                            <div class="d-flex align-items-center gap-1 me-1">
                                <select class="form-select form-select-sm rounded-pill px-3 fw-bold border-secondary-subtle"
                                        style="min-width: 175px; font-size: 0.78rem;"
                                        onchange="filterSesiSekolah({{ $sekolah->id }}, this.value)">
                                    <option value="all">— Tampilkan Semua Sesi ({{ $sekolah->sesi->count() }}) —</option>
                                    @foreach($sekolah->sesi as $sOpt)
                                        <option value="{{ $sOpt->id }}">{{ $sOpt->nama_sesi }} {{ $sOpt->tingkatan ? '(' . $sOpt->tingkatan . ')' : '' }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @endif

                            <button type="button" class="btn btn-sm btn-skillo-amber py-1 px-3"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalTambahSesi"
                                    data-sekolah-id="{{ $sekolah->id }}"
                                    data-sekolah-nama="{{ $sekolah->nama_sekolah }}">
                                <i class="bi bi-layers-half me-1"></i>+ Tambah Sesi
                            </button>

                            <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill py-1 px-2.5"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalEditSekolah"
                                    data-sekolah-id="{{ $sekolah->id }}"
                                    data-sekolah-nama="{{ $sekolah->nama_sekolah }}"
                                    data-deskripsi="{{ $sekolah->deskripsi }}"
                                    title="Edit Nama/Deskripsi Sekolah">
                                <i class="bi bi-pencil"></i>
                            </button>

                            <button type="button" class="btn btn-sm btn-outline-danger rounded-pill py-1 px-2.5"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalKonfirmasiHapusSekolah"
                                    data-action="{{ route('admin.sekolah.destroy', $sekolah) }}"
                                    data-sekolah-nama="{{ $sekolah->nama_sekolah }}"
                                    data-sesi-count="{{ $sekolah->sesi->count() }}"
                                    data-murid-count="{{ $totalMuridSekolah }}"
                                    title="Hapus Sekolah Beserta Seluruh Sesinya">
                                <i class="bi bi-trash-fill"></i>
                            </button>
                        </div>
                    </div>

                    {{-- Daftar Sesi di Bawah Sekolah (Sesi 1, Sesi 2, dst.) --}}
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" id="table-sesi-sekolah-{{ $sekolah->id }}">
                            <thead class="bg-light" style="border-bottom: 1.5px solid #f1f5f9;">
                                <tr class="text-secondary small fw-bold text-uppercase" style="letter-spacing: 0.5px; font-size: 0.72rem;">
                                    <th class="px-4 py-2.5" style="width: 18%;">Bagian Sesi</th>
                                    <th class="py-2.5" style="width: 20%;">Tingkatan / Course Sesi</th>
                                    <th class="py-2.5" style="width: 32%;">Guru Pengampu (Bisa > 1 Guru)</th>
                                    <th class="py-2.5 text-center" style="width: 12%;">Jumlah Murid</th>
                                    <th class="px-4 py-2.5 text-end text-nowrap" style="width: 18%;">Aksi Sesi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($sekolah->sesi as $sesi)
                                <tr class="sesi-row-sekolah-{{ $sekolah->id }}" data-sesi-id="{{ $sesi->id }}">
                                    <td class="px-4">
                                        <span class="badge rounded-pill px-3 py-1.5 fw-black font-monsserat"
                                              style="background: #fff4f2; color: var(--skillo-primary); border: 1px solid #fee2e2; font-size: 0.82rem;">
                                            <i class="bi bi-layers-fill me-1"></i>{{ $sesi->nama_sesi }}
                                        </span>
                                    </td>
                                    <td>
                                        @if(!empty($sesi->tingkatan))
                                            <span class="badge rounded-pill bg-warning-subtle text-dark border border-warning-subtle px-2.5 py-1 fw-bold" style="font-size: 0.78rem;">
                                                <i class="bi bi-award-fill me-1 text-warning"></i>{{ $sesi->tingkatan }}
                                            </span>
                                        @else
                                            <span class="text-muted small fst-italic">Belum diatur</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($sesi->guru->isNotEmpty())
                                            <div class="d-flex flex-wrap gap-1.5">
                                                @foreach($sesi->guru as $g)
                                                    <div class="d-inline-flex align-items-center gap-1.5 px-2.5 py-1 rounded-pill border bg-light">
                                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($g->name) }}&background=fee2e2&color=ee4c37&bold=true&rounded=true"
                                                             style="width: 20px; height: 20px; border-radius: 50%;" alt="">
                                                        <span class="fw-bold text-dark" style="font-size: 0.78rem;">{{ $g->name }}</span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <span class="badge rounded-pill bg-light text-muted border fw-semibold">Belum Ditugaskan</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <span class="fw-black text-dark font-monsserat" style="font-size: 0.95rem;">{{ $sesi->siswa_count }}</span>
                                        <small class="text-muted d-block" style="font-size: 0.7rem;">Murid</small>
                                    </td>
                                    <td class="px-4 text-end text-nowrap">
                                        <button type="button" class="btn btn-sm btn-skillo-dark me-1 py-1 px-2.5"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modalDaftarMuridSesi-{{ $sesi->id }}"
                                                title="Lihat Daftar Murid di Sesi Ini">
                                            <i class="bi bi-eye-fill me-1"></i>Murid ({{ $sesi->siswa_count }})
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-success rounded-pill me-1 py-1 px-2.5 fw-bold"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modalInputSiswa"
                                                data-sekolah-id="{{ $sekolah->id }}"
                                                data-sesi-id="{{ $sesi->id }}"
                                                data-default-course="{{ $sesi->tingkatan }}"
                                                data-default-metode="import"
                                                title="Import / Tambah Murid ke Sesi Ini">
                                            <i class="bi bi-person-plus-fill"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill me-1 py-1 px-2.5"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modalEditSesi"
                                                data-sesi-id="{{ $sesi->id }}"
                                                data-sekolah-nama="{{ $sekolah->nama_sekolah }}"
                                                data-nama-sesi="{{ $sesi->nama_sesi }}"
                                                data-tingkatan="{{ $sesi->tingkatan }}"
                                                data-guru-ids="{{ json_encode($sesi->guru->pluck('id')->values()) }}"
                                                title="Edit Sesi, Tingkatan & Guru Pengampu">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-danger rounded-pill py-1 px-2.5"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modalKonfirmasiHapusSesi"
                                                data-action="{{ route('admin.sesi.destroy', $sesi) }}"
                                                data-sekolah-nama="{{ $sekolah->nama_sekolah }}"
                                                data-nama-sesi="{{ $sesi->nama_sesi }}"
                                                data-murid-count="{{ $sesi->siswa_count }}"
                                                title="Hapus Sesi Ini">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted small">
                                        Belum ada sesi pada sekolah ini. Klik tombol <strong>+ Tambah Sesi</strong> di kanan atas.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

{{-- ============================================================ --}}
{{-- REKAPAN ABSENSI MINGGUAN LANGSUNG DI DASHBOARD ADMIN --}}
{{-- (Dapat dilihat langsung tanpa harus download / export CSV) --}}
{{-- ============================================================ --}}
<div class="card mb-4" id="sectionRekapMingguan">
    <div class="card-header d-flex flex-column flex-xl-row justify-content-between align-items-xl-center gap-3"
         style="background: linear-gradient(135deg, #070B1F 0%, #1e293b 100%); color: #ffffff;">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <span class="badge rounded-pill bg-amber-300 text-dark fw-black px-2.5 py-1" style="background: var(--skillo-amber); font-size: 0.7rem;">
                    <i class="bi bi-lightning-charge-fill me-1"></i>LIVE REKAP DASHBOARD
                </span>
                <span class="text-white-50 small">Tanpa perlu download CSV / Excel</span>
            </div>
            <h5 class="fw-black mb-0 font-monsserat text-white" style="font-size: 1.2rem;">
                <i class="bi bi-calendar2-week-fill me-2 text-warning"></i>Rekapan Absensi & Catatan Mingguan (Guru & Murid)
            </h5>
            <small class="text-white-50">
                Memudahkan Admin memantau kehadiran guru pengampu, status siswa yang izin/sakit, serta membaca 2 catatan untuk keperluan <strong>sesi pengganti</strong>.
            </small>
        </div>

        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('admin.dashboard', ['rekap_mulai' => now()->startOfWeek()->format('Y-m-d'), 'rekap_akhir' => now()->endOfWeek()->format('Y-m-d')]) }}#sectionRekapMingguan"
               class="btn btn-sm btn-skillo-amber py-1.5 px-3">
                <i class="bi bi-calendar-check me-1"></i>Minggu Ini
            </a>
            <a href="{{ route('admin.dashboard', ['rekap_mulai' => now()->subWeek()->startOfWeek()->format('Y-m-d'), 'rekap_akhir' => now()->subWeek()->endOfWeek()->format('Y-m-d')]) }}#sectionRekapMingguan"
               class="btn btn-sm btn-outline-light rounded-pill py-1.5 px-3 fw-bold">
                <i class="bi bi-clock-history me-1"></i>Minggu Lalu
            </a>
            <a href="{{ route('admin.dashboard', ['rekap_mulai' => now()->startOfMonth()->format('Y-m-d'), 'rekap_akhir' => now()->endOfMonth()->format('Y-m-d')]) }}#sectionRekapMingguan"
               class="btn btn-sm btn-outline-light rounded-pill py-1.5 px-3 fw-bold">
                <i class="bi bi-calendar-month me-1"></i>Bulan Ini
            </a>
        </div>
    </div>

    {{-- Filter Bar Rekapan --}}
    <div class="p-3 px-4 border-bottom" style="background: #fff9e8;">
        <form action="{{ route('admin.dashboard') }}#sectionRekapMingguan" method="GET" class="row g-2 align-items-end">
            <div class="col-6 col-md-2">
                <label class="form-label fw-bold small text-secondary mb-1">Dari Tanggal</label>
                <input type="date" name="rekap_mulai" value="{{ $rekapMulai }}" class="form-control form-control-sm rounded-3 bg-white fw-semibold">
            </div>
            <div class="col-6 col-md-2">
                <label class="form-label fw-bold small text-secondary mb-1">Sampai Tanggal</label>
                <input type="date" name="rekap_akhir" value="{{ $rekapAkhir }}" class="form-control form-control-sm rounded-3 bg-white fw-semibold">
            </div>
            <div class="col-12 col-md-3">
                <label class="form-label fw-bold small text-secondary mb-1">Filter Sekolah</label>
                <select name="rekap_sekolah_id" id="filterRekapSekolah" class="form-select form-select-sm rounded-3 bg-white fw-semibold" onchange="sinkronDropdownSesiRekap()">
                    <option value="">— Semua Sekolah —</option>
                    @foreach($sekolahList as $sek)
                        <option value="{{ $sek->id }}" {{ (string)$rekapSekolahId === (string)$sek->id ? 'selected' : '' }}>
                            {{ $sek->nama_sekolah }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 col-md-3">
                <label class="form-label fw-bold small text-secondary mb-1">Filter Sesi</label>
                <select name="rekap_sesi_id" id="filterRekapSesi" class="form-select form-select-sm rounded-3 bg-white fw-semibold">
                    <option value="">— Semua Sesi —</option>
                    @foreach($sesiList as $sOpt)
                        <option value="{{ $sOpt->id }}" data-sekolah-id="{{ $sOpt->sekolah_id }}" {{ (string)$rekapSesiId === (string)$sOpt->id ? 'selected' : '' }}>
                            {{ $sOpt->sekolah?->nama_sekolah }} — {{ $sOpt->nama_sesi }} {{ $sOpt->tingkatan ? '(' . $sOpt->tingkatan . ')' : '' }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 col-md-2 d-flex gap-1">
                <button type="submit" class="btn btn-sm btn-skillo-primary w-100 py-1.5">
                    <i class="bi bi-search me-1"></i>Tampilkan
                </button>
                <a href="{{ route('admin.dashboard') }}#sectionRekapMingguan" class="btn btn-sm btn-skillo-outline py-1.5 px-2.5" title="Reset Filter">
                    <i class="bi bi-arrow-counterclockwise"></i>
                </a>
            </div>
        </form>
    </div>

    {{-- Ringkasan Angka & Pencarian Cepat --}}
    <div class="p-3 px-4 border-bottom bg-light d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
        <div class="d-flex flex-wrap align-items-center gap-2">
            <span class="badge rounded-pill bg-dark text-white px-3 py-1.5 fw-bold">
                <i class="bi bi-journal-check me-1 text-warning"></i>{{ $rekapStats['total_pertemuan'] }} Pertemuan Sesi
            </span>
            <span class="badge rounded-pill bg-success-subtle text-success border border-success-subtle px-3 py-1.5 fw-bold">
                Hadir: {{ $rekapStats['hadir'] }}
            </span>
            <span class="badge rounded-pill bg-warning-subtle text-warning-emphasis border border-warning-subtle px-3 py-1.5 fw-bold">
                Sakit: {{ $rekapStats['sakit'] }}
            </span>
            <span class="badge rounded-pill bg-info-subtle text-info-emphasis border border-info-subtle px-3 py-1.5 fw-bold">
                Izin: {{ $rekapStats['izin'] }}
            </span>
            <span class="badge rounded-pill bg-danger-subtle text-danger border border-danger-subtle px-3 py-1.5 fw-bold">
                Alpa: {{ $rekapStats['alfa'] }}
            </span>
            <span class="badge rounded-pill bg-white text-dark border px-3 py-1.5 fw-bold">
                <i class="bi bi-chat-left-text-fill me-1 text-primary"></i>{{ $rekapStats['bercatatan'] }} Catatan Progres / Sesi Pengganti
            </span>
        </div>

        <div class="d-flex align-items-center gap-2">
            <div class="input-group input-group-sm" style="min-width: 260px;">
                <span class="input-group-text bg-white border-end-0 rounded-start-pill ps-3"><i class="bi bi-search text-muted"></i></span>
                <input type="text" id="searchRekapInput" class="form-control border-start-0 rounded-end-pill"
                       placeholder="Cari murid, panggilan, sesi, catatan..." oninput="filterBarisRekapLive(this.value)">
            </div>
        </div>
    </div>

    {{-- Tab Pilihan Tampilan Rekap: Detail Murid & 2 Notes vs Ringkasan Pertemuan & Guru --}}
    <div class="px-4 pt-3 border-bottom bg-white">
        <ul class="nav nav-pills gap-2 pb-3" id="rekapTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="btn btn-sm btn-skillo-primary active" id="tab-detail-murid-btn" data-bs-toggle="pill" data-bs-target="#pane-detail-murid" type="button" role="tab">
                    <i class="bi bi-person-lines-fill me-1"></i>Detail Absensi & 2 Catatan Murid ({{ $rekapAbsensiRows->count() }})
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="btn btn-sm btn-skillo-outline" id="tab-ringkasan-sesi-btn" data-bs-toggle="pill" data-bs-target="#pane-ringkasan-sesi" type="button" role="tab">
                    <i class="bi bi-person-workspace me-1"></i>Kehadiran Guru Pengampu & Jurnal Sesi ({{ $rekapPertemuanList->count() }})
                </button>
            </li>
        </ul>
    </div>

    <div class="card-body p-0 tab-content">
        {{-- TAB 1: Detail Absensi Murid beserta Catatan 1 & Catatan 2 --}}
        <div class="tab-pane fade show active" id="pane-detail-murid" role="tabpanel">
            @if($rekapAbsensiRows->isEmpty())
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-calendar-x fs-1 text-secondary opacity-50"></i>
                    <p class="mt-2 fw-semibold mb-1">Belum ada data absensi pada rentang tanggal {{ \Carbon\Carbon::parse($rekapMulai)->format('d/m/Y') }} s/d {{ \Carbon\Carbon::parse($rekapAkhir)->format('d/m/Y') }}.</p>
                    <small class="text-muted">Ubah rentang tanggal di atas atau pilih tombol Minggu Lalu / Bulan Ini untuk melihat riwayat lainnya.</small>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="tabelRekapDetailMurid">
                        <thead class="bg-light" style="border-bottom: 2px solid #f1f5f9;">
                            <tr class="text-secondary small fw-bold text-uppercase" style="letter-spacing: 0.5px; font-size: 0.72rem;">
                                <th class="px-3 py-3" style="width: 4%;">No</th>
                                <th class="py-3" style="width: 10%;">Tanggal</th>
                                <th class="py-3" style="width: 16%;">Sekolah & Sesi</th>
                                <th class="py-3" style="width: 18%;">Guru Pengampu (Kehadiran)</th>
                                <th class="py-3" style="width: 16%;">Murid (Panggilan)</th>
                                <th class="py-3 text-center" style="width: 8%;">Status</th>
                                <th class="py-3" style="width: 14%;">Catatan 1 (Sesi / Tingkatan)</th>
                                <th class="py-3 pe-3" style="width: 14%;">Catatan 2 (Progres / Pengganti)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($rekapAbsensiRows as $idx => $r)
                            <tr class="baris-rekap-item">
                                <td class="px-3 fw-bold text-muted small">{{ $idx + 1 }}</td>
                                <td>
                                    <span class="fw-bold text-dark small">{{ \Carbon\Carbon::parse($r->tanggal)->format('d/m/Y') }}</span>
                                    <small class="text-muted d-block" style="font-size: 0.7rem;">{{ \Carbon\Carbon::parse($r->tanggal)->translatedFormat('l') }}</small>
                                </td>
                                <td>
                                    <div class="fw-bold text-dark small">{{ $r->sekolah_nama }}</div>
                                    <div class="d-flex flex-wrap gap-1 mt-0.5">
                                        <span class="badge rounded-pill bg-danger-subtle text-danger border border-danger-subtle" style="font-size: 0.68rem;">
                                            {{ $r->sesi_nama }}
                                        </span>
                                        @if(!empty($r->course) && $r->course !== '-')
                                            <span class="badge rounded-pill bg-warning-subtle text-dark border border-warning-subtle" style="font-size: 0.68rem;">
                                                {{ $r->course }}
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column gap-1">
                                        @forelse($r->guru_sesi as $gSesi)
                                            @php
                                                $stGuru = $r->guru_absensi[$gSesi->id]->status ?? 'hadir';
                                            @endphp
                                            <div class="d-flex align-items-center gap-1.5">
                                                <span class="badge rounded-pill {{ $stGuru === 'hadir' ? 'bg-success-subtle text-success border border-success-subtle' : 'bg-danger-subtle text-danger border border-danger-subtle' }}"
                                                      style="font-size: 0.65rem;">
                                                    {{ $stGuru === 'hadir' ? 'Hadir' : 'Tidak Hadir' }}
                                                </span>
                                                <span class="small fw-semibold text-dark" style="font-size: 0.78rem;">{{ $gSesi->name }}</span>
                                            </div>
                                        @empty
                                            <span class="text-muted small fst-italic">-</span>
                                        @endforelse
                                    </div>
                                </td>
                                <td>
                                    <div class="fw-bold font-monsserat text-dark" style="font-size: 0.88rem;">
                                        {{ $r->siswa?->nama ?? '-' }}
                                        @if(!empty($r->siswa?->nama_panggilan))
                                            <span class="badge rounded-pill bg-light text-dark border ms-1" style="font-size: 0.68rem;">
                                                {{ $r->siswa->nama_panggilan }}
                                            </span>
                                        @endif
                                    </div>
                                    <small class="text-muted font-monospace" style="font-size: 0.7rem;">{{ $r->siswa?->nim }}</small>
                                </td>
                                <td class="text-center">
                                    <span class="status-{{ $r->status }} text-uppercase small">{{ $r->status }}</span>
                                </td>
                                <td>
                                    @if(!empty($r->notes_1))
                                        <div class="p-2 rounded-3 bg-light border small text-dark" style="font-size: 0.8rem;">
                                            {{ $r->notes_1 }}
                                        </div>
                                    @else
                                        <span class="text-muted small">—</span>
                                    @endif
                                </td>
                                <td class="pe-3">
                                    @if(!empty($r->notes_2))
                                        <div class="p-2 rounded-3 small text-dark" style="background: #fff9e8; border: 1px solid #fef3c7; font-size: 0.8rem;">
                                            {{ $r->notes_2 }}
                                        </div>
                                    @else
                                        <span class="text-muted small">—</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        {{-- TAB 2: Ringkasan per Pertemuan Sesi & Kehadiran Guru Pengampu --}}
        <div class="tab-pane fade" id="pane-ringkasan-sesi" role="tabpanel">
            @if($rekapPertemuanList->isEmpty())
                <div class="text-center py-5 text-muted">
                    <p class="mb-0 fw-semibold">Belum ada pertemuan sesi pada periode ini.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light" style="border-bottom: 2px solid #f1f5f9;">
                            <tr class="text-secondary small fw-bold text-uppercase" style="font-size: 0.72rem;">
                                <th class="px-4 py-3">Tanggal</th>
                                <th class="py-3">Sekolah & Sesi</th>
                                <th class="py-3">Materi Pembelajaran</th>
                                <th class="py-3">Kehadiran Guru Pengampu</th>
                                <th class="px-4 py-3 text-center">Ringkasan Kehadiran Murid</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($rekapPertemuanList as $pt)
                            @php
                                $guruAbsMap = $pt->absensiGuru->keyBy('guru_id');
                                $cHadir = $pt->absensiSiswa->where('status', 'hadir')->count();
                                $cSakit = $pt->absensiSiswa->where('status', 'sakit')->count();
                                $cIzin  = $pt->absensiSiswa->where('status', 'izin')->count();
                                $cAlfa  = $pt->absensiSiswa->where('status', 'alfa')->count();
                            @endphp
                            <tr>
                                <td class="px-4">
                                    <div class="fw-bold text-dark small">{{ \Carbon\Carbon::parse($pt->tanggal)->format('d/m/Y') }}</div>
                                    <small class="text-muted">{{ \Carbon\Carbon::parse($pt->tanggal)->translatedFormat('l') }}</small>
                                </td>
                                <td>
                                    <div class="fw-bold text-dark">{{ $pt->sesi?->sekolah?->nama_sekolah ?? '-' }}</div>
                                    <span class="badge rounded-pill bg-danger-subtle text-danger border border-danger-subtle">
                                        {{ $pt->sesi?->nama_sesi }}
                                    </span>
                                    @if(!empty($pt->sesi?->tingkatan))
                                        <span class="badge rounded-pill bg-warning-subtle text-dark border border-warning-subtle">
                                            {{ $pt->sesi->tingkatan }}
                                        </span>
                                    @endif
                                </td>
                                <td class="small text-secondary" style="max-width: 260px;">
                                    {{ $pt->materi ?: '—' }}
                                </td>
                                <td>
                                    <div class="d-flex flex-wrap gap-2">
                                        @foreach($pt->sesi?->guru ?? [] as $g)
                                            @php
                                                $stG = $guruAbsMap[$g->id]->status ?? 'hadir';
                                            @endphp
                                            <span class="badge rounded-pill px-2.5 py-1 {{ $stG === 'hadir' ? 'bg-success-subtle text-success border border-success-subtle' : 'bg-danger-subtle text-danger border border-danger-subtle' }}">
                                                <i class="bi {{ $stG === 'hadir' ? 'bi-check-circle-fill' : 'bi-x-circle-fill' }} me-1"></i>
                                                {{ $g->name }} ({{ $stG === 'hadir' ? 'Hadir' : 'Tidak Hadir' }})
                                            </span>
                                        @endforeach
                                    </div>
                                </td>
                                <td class="px-4 text-center">
                                    <div class="d-inline-flex flex-wrap gap-1 justify-content-center">
                                        <span class="badge bg-success-subtle text-success border">H: {{ $cHadir }}</span>
                                        <span class="badge bg-warning-subtle text-warning-emphasis border">S: {{ $cSakit }}</span>
                                        <span class="badge bg-info-subtle text-info-emphasis border">I: {{ $cIzin }}</span>
                                        <span class="badge bg-danger-subtle text-danger border">A: {{ $cAlfa }}</span>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>

{{-- ============================================================ --}}
{{-- DAFTAR AKUN GURU & MENTOR --}}
{{-- ============================================================ --}}
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div>
            <h5 class="fw-black mb-0 font-monsserat" style="font-size: 1.15rem;">
                <i class="bi bi-people-fill me-2" style="color: #059669;"></i>Daftar Akun Guru & Mentor
            </h5>
            <small class="text-muted">Kredensial dan daftar sekolah/sesi yang diampu oleh staf pengajar</small>
        </div>
        <div class="d-flex align-items-center gap-2">
            @if(isset($arsipGuruList) && $arsipGuruList->isNotEmpty())
                <button class="btn btn-outline-secondary btn-sm rounded-pill" data-bs-toggle="modal" data-bs-target="#modalArsipGuru">
                    <i class="bi bi-archive me-1"></i>Arsip Guru ({{ $arsipGuruList->count() }})
                </button>
            @endif
            <button class="btn btn-skillo-amber btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambahGuru">
                <i class="bi bi-person-plus me-1"></i>+ Guru Baru
            </button>
        </div>
    </div>
    <div class="card-body p-0">
        @if($guruList->isEmpty())
            <div class="text-center py-4 text-muted small fw-semibold">Belum ada akun guru yang terdaftar.</div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light" style="border-bottom: 2px solid #f1f5f9;">
                        <tr class="text-secondary small fw-bold text-uppercase" style="letter-spacing: 0.5px; font-size: 0.75rem;">
                            <th class="px-4 py-3">Nama Mentor</th>
                            <th class="py-3">Username</th>
                            <th class="py-3">Email</th>
                            <th class="py-3">WhatsApp</th>
                            <th class="py-3">Sekolah & Sesi Yang Diampu</th>
                            <th class="px-4 py-3 text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($guruList as $guru)
                        <tr>
                            <td class="px-4">
                                <div class="d-flex align-items-center gap-2">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($guru->name) }}&background=f0fdf4&color=059669&bold=true&rounded=true"
                                         class="avatar-sm border" alt="">
                                    <span class="fw-bold font-monsserat text-dark small">{{ $guru->name }}</span>
                                </div>
                            </td>
                            <td class="small text-muted font-monospace">{{ $guru->username }}</td>
                            <td class="small fw-medium text-dark">{{ $guru->email }}</td>
                            <td class="small text-muted">{{ $guru->no_hp ?? '-' }}</td>
                            <td>
                                @foreach($guru->sesi as $s)
                                    <span class="badge rounded-pill bg-light text-dark border me-1 mb-1 px-2.5 py-1 fw-semibold">
                                        {{ $s->sekolah?->nama_sekolah }} — <strong>{{ $s->nama_sesi }}</strong>
                                    </span>
                                @endforeach
                                @if($guru->sesi->isEmpty())
                                    <span class="text-muted small fst-italic">Belum mengampu sesi</span>
                                @endif
                            </td>
                            <td class="px-4 text-end text-nowrap">
                                <div class="dropdown d-inline-block">
                                    <button class="btn btn-sm btn-outline-danger rounded-pill dropdown-toggle" type="button"
                                            data-bs-toggle="dropdown" data-bs-popper-config='{"strategy":"fixed"}' aria-expanded="false">
                                        <i class="bi bi-trash me-1"></i>Hapus
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 p-2" style="border-radius: 14px; min-width: 240px; z-index: 1050;">
                                        <li>
                                            <button type="button"
                                                    class="dropdown-item p-2.5 rounded-3 d-flex align-items-center gap-2.5 text-start"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#modalKonfirmasiHapusGuruDaftar"
                                                    data-action="{{ route('admin.guru.destroy', $guru->id) }}"
                                                    data-guru-nama="{{ $guru->name }}"
                                                    data-guru-email="{{ $guru->email }}">
                                                <div class="rounded-circle d-flex align-items-center justify-content-center shrink-0"
                                                     style="width: 32px; height: 32px; background: #fff9e8; border: 1px solid #fef3c7;">
                                                    <i class="bi bi-person-dash-fill text-warning"></i>
                                                </div>
                                                <div>
                                                    <span class="fw-bold text-dark d-block" style="font-size: 0.85rem;">Hapus dari Daftar</span>
                                                    <small class="text-muted d-block" style="font-size: 0.72rem; line-height: 1.2;">Lepas dari pengajar aktif</small>
                                                </div>
                                            </button>
                                        </li>
                                        <li><hr class="dropdown-divider my-1"></li>
                                        <li>
                                            <button type="button"
                                                    class="dropdown-item p-2.5 rounded-3 d-flex align-items-center gap-2.5 text-start"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#modalKonfirmasiHapusGuruPermanen"
                                                    data-action="{{ route('admin.guru.destroy', $guru->id) }}"
                                                    data-guru-nama="{{ $guru->name }}"
                                                    data-guru-email="{{ $guru->email }}">
                                                <div class="rounded-circle d-flex align-items-center justify-content-center shrink-0"
                                                     style="width: 32px; height: 32px; background: #fff5f5; border: 1px solid #fee2e2;">
                                                    <i class="bi bi-trash-fill text-danger"></i>
                                                </div>
                                                <div>
                                                    <span class="fw-bold text-danger d-block" style="font-size: 0.85rem;">Hapus Permanen</span>
                                                    <small class="text-muted d-block" style="font-size: 0.72rem; line-height: 1.2;">Hapus total dari database</small>
                                                </div>
                                            </button>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

{{-- Datalist Saran Nama Sesi (Tetap bebas diketik manual oleh Admin) --}}
<datalist id="saranNamaSesiList">
    <option value="Sesi 1">
    <option value="Sesi 2">
    <option value="Sesi 3">
    <option value="Sesi Pagi">
    <option value="Sesi Siang">
</datalist>

{{-- ============================================================ --}}
{{-- MODAL: Tambah Sekolah Baru (Beserta Pembagian Sesi 1 & Sesi 2) --}}
{{-- ============================================================ --}}
<div class="modal fade" id="modalTambahSekolah" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <form action="{{ route('admin.sekolah.store') }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header text-white" style="background: var(--skillo-dark);">
                <h6 class="modal-title fw-bold font-monsserat mb-0">
                    <i class="bi bi-building-add me-2" style="color: var(--skillo-primary);"></i>Tambah Sekolah & Pembagian Sesi
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-secondary">Nama Sekolah <span class="text-danger">*</span></label>
                        <input type="text" name="nama_sekolah" class="form-control rounded-3" required placeholder="Contoh: SD Islam Al-Azhar BSD">
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-bold small text-secondary">Deskripsi Program Sekolah</label>
                        <textarea name="deskripsi" class="form-control rounded-3" rows="2" placeholder="Jelaskan program ekstrakurikuler atau fokus materi di sekolah ini..."></textarea>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div>
                            <label class="form-label fw-black small text-dark font-monsserat mb-0">
                                <i class="bi bi-layers-fill me-1 text-danger"></i>Pembagian Sesi di Bawah Sekolah Ini
                            </label>
                            <small class="text-muted d-block">Nama sesi dapat diketik bebas (default Sesi 1 & Sesi 2), memiliki tingkatan berbeda, dan bisa pilih > 1 guru pengampu.</small>
                        </div>
                        <button type="button" class="btn btn-sm btn-skillo-amber" onclick="tambahBarisSesiSekolahBaru()">
                            <i class="bi bi-plus-lg me-1"></i>+ Tambah Sesi Lain
                        </button>
                    </div>

                    <div id="containerSesiSekolahBaru" class="d-flex flex-column gap-3 mt-2">
                        {{-- Baris Default 0: Sesi 1 --}}
                        <div class="p-3 rounded-4 border bg-light item-sesi-baru">
                            <div class="row g-2 mb-2">
                                <div class="col-12 col-sm-6">
                                    <label class="form-label fw-bold small text-secondary mb-1">Nama Sesi (Ketik Manual) <span class="text-danger">*</span></label>
                                    <input type="text" name="sesi[0][nama_sesi]" list="saranNamaSesiList" class="form-control form-control-sm rounded-3 fw-bold" value="Sesi 1" required placeholder="Ketik misal: Sesi 1">
                                </div>
                                <div class="col-12 col-sm-6">
                                    <label class="form-label fw-bold small text-secondary mb-1">Tingkatan / Course Sesi</label>
                                    <input type="text" name="sesi[0][tingkatan]" class="form-control form-control-sm rounded-3" placeholder="Contoh: Course E / Junior">
                                </div>
                            </div>
                            <div>
                                <label class="form-label fw-bold small text-secondary mb-1">Pilih Guru Pengampu (Bisa Lebih dari 1 Guru):</label>
                                <div class="d-flex flex-wrap gap-2 p-2 bg-white rounded-3 border">
                                    @forelse($guruList as $g)
                                        <div class="form-check me-2">
                                            <input class="form-check-input" type="checkbox" name="sesi[0][guru_ids][]" value="{{ $g->id }}" id="new_s0_g_{{ $g->id }}">
                                            <label class="form-check-label small fw-semibold text-dark" for="new_s0_g_{{ $g->id }}" style="cursor: pointer;">
                                                {{ $g->name }}
                                            </label>
                                        </div>
                                    @empty
                                        <span class="text-muted small">Belum ada akun guru.</span>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        {{-- Baris Default 1: Sesi 2 --}}
                        <div class="p-3 rounded-4 border bg-light item-sesi-baru">
                            <div class="row g-2 mb-2">
                                <div class="col-12 col-sm-6">
                                    <label class="form-label fw-bold small text-secondary mb-1">Nama Sesi (Ketik Manual) <span class="text-danger">*</span></label>
                                    <input type="text" name="sesi[1][nama_sesi]" list="saranNamaSesiList" class="form-control form-control-sm rounded-3 fw-bold" value="Sesi 2" required placeholder="Ketik misal: Sesi 2">
                                </div>
                                <div class="col-12 col-sm-6">
                                    <label class="form-label fw-bold small text-secondary mb-1">Tingkatan / Course Sesi</label>
                                    <input type="text" name="sesi[1][tingkatan]" class="form-control form-control-sm rounded-3" placeholder="Contoh: Course F / Intermediate">
                                </div>
                            </div>
                            <div>
                                <label class="form-label fw-bold small text-secondary mb-1">Pilih Guru Pengampu (Bisa Lebih dari 1 Guru):</label>
                                <div class="d-flex flex-wrap gap-2 p-2 bg-white rounded-3 border">
                                    @forelse($guruList as $g)
                                        <div class="form-check me-2">
                                            <input class="form-check-input" type="checkbox" name="sesi[1][guru_ids][]" value="{{ $g->id }}" id="new_s1_g_{{ $g->id }}">
                                            <label class="form-check-label small fw-semibold text-dark" for="new_s1_g_{{ $g->id }}" style="cursor: pointer;">
                                                {{ $g->name }}
                                            </label>
                                        </div>
                                    @empty
                                        <span class="text-muted small">Belum ada akun guru.</span>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-skillo-outline" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-skillo-primary">Simpan Sekolah & Sesi</button>
                </div>
        </form>
    </div>
</div>

{{-- ============================================================ --}}
{{-- MODAL: Edit Sekolah --}}
{{-- ============================================================ --}}
<div class="modal fade" id="modalEditSekolah" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header text-white" style="background: var(--skillo-dark);">
                <h6 class="modal-title fw-bold font-monsserat mb-0"><i class="bi bi-pencil-square me-2 text-warning"></i>Edit Data Sekolah</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="formEditSekolah" action="" method="POST">
                @csrf @method('PUT')
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-secondary">Nama Sekolah <span class="text-danger">*</span></label>
                        <input type="text" name="nama_sekolah" id="editNamaSekolah" class="form-control rounded-3" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-secondary">Deskripsi Sekolah</label>
                        <textarea name="deskripsi" id="editDeskripsiSekolah" class="form-control rounded-3" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-skillo-outline" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-skillo-dark">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ============================================================ --}}
{{-- MODAL: Tambah Sesi Baru ke Sekolah --}}
{{-- ============================================================ --}}
<div class="modal fade" id="modalTambahSesi" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header text-white" style="background: var(--skillo-dark);">
                <div>
                    <h6 class="modal-title fw-bold font-monsserat mb-0">
                        <i class="bi bi-layers-half me-2 text-warning"></i>Tambah Sesi Baru
                    </h6>
                    <small class="text-white-50" id="tambahSesiNamaSekolahLabel">Sekolah</small>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.sesi.store') }}" method="POST">
                @csrf
                <input type="hidden" name="sekolah_id" id="tambahSesiSekolahId">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-secondary">Nama Sesi (Ketik Manual) <span class="text-danger">*</span></label>
                        <input type="text" name="nama_sesi" list="saranNamaSesiList" class="form-control rounded-3 fw-bold" required placeholder="Ketik misal: Sesi 1, Sesi 2, Sesi 3...">
                        <small class="text-muted">Anda dapat mengetik bebas nama sesi atau memilih dari saran yang muncul.</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-secondary">Tingkatan / Course Sesi</label>
                        <input type="text" name="tingkatan" class="form-control rounded-3" placeholder="Contoh: Course E, Level 2, dsb.">
                    </div>
                    <div class="mb-2">
                        <label class="form-label fw-bold small text-secondary">Guru Pengampu Sesi Ini (Bisa Pilih Lebih dari 1 Guru)</label>
                        <div class="p-3 rounded-3 border bg-light d-flex flex-column gap-2" style="max-height: 220px; overflow-y: auto;">
                            @forelse($guruList as $g)
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="guru_ids[]" value="{{ $g->id }}" id="add_sesi_guru_{{ $g->id }}">
                                    <label class="form-check-label fw-semibold text-dark small" for="add_sesi_guru_{{ $g->id }}" style="cursor: pointer;">
                                        {{ $g->name }} <span class="text-muted">({{ $g->email }})</span>
                                    </label>
                                </div>
                            @empty
                                <span class="text-muted small">Belum ada akun guru terdaftar.</span>
                            @endforelse
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-skillo-outline" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-skillo-primary">Simpan Sesi</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ============================================================ --}}
{{-- MODAL: Edit Sesi, Tingkatan & Multi-Guru Pengampu --}}
{{-- ============================================================ --}}
<div class="modal fade" id="modalEditSesi" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header text-white" style="background: var(--skillo-dark);">
                <div>
                    <h6 class="modal-title fw-bold font-monsserat mb-0">
                        <i class="bi bi-pencil-square me-2 text-warning"></i>Edit Sesi & Guru Pengampu
                    </h6>
                    <small class="text-white-50" id="editSesiSekolahLabel">Sekolah</small>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="formEditSesi" action="" method="POST">
                @csrf @method('PUT')
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-secondary">Nama Sesi (Ketik Manual) <span class="text-danger">*</span></label>
                        <input type="text" name="nama_sesi" id="editSesiNama" list="saranNamaSesiList" class="form-control rounded-3 fw-bold" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-secondary">Tingkatan / Course Sesi</label>
                        <input type="text" name="tingkatan" id="editSesiTingkatan" class="form-control rounded-3" placeholder="Contoh: Course E">
                    </div>
                    <div class="mb-2">
                        <label class="form-label fw-bold small text-secondary">Guru Pengampu Sesi Ini (Bisa Pilih Lebih dari 1 Guru)</label>
                        <div class="p-3 rounded-3 border bg-light d-flex flex-column gap-2" style="max-height: 220px; overflow-y: auto;">
                            @foreach($guruList as $g)
                                <div class="form-check">
                                    <input class="form-check-input edit-sesi-guru-checkbox" type="checkbox" name="guru_ids[]" value="{{ $g->id }}" id="edit_sesi_guru_{{ $g->id }}">
                                    <label class="form-check-label fw-semibold text-dark small" for="edit_sesi_guru_{{ $g->id }}" style="cursor: pointer;">
                                        {{ $g->name }} <span class="text-muted">({{ $g->email }})</span>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-skillo-outline" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-skillo-dark">Simpan Perubahan Sesi</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ============================================================ --}}
{{-- MODAL: Tambah / Import Murid ke Sesi (Pilih Sesi Dulu Baru Input/Import) --}}
{{-- ============================================================ --}}
<div class="modal fade" id="modalInputSiswa" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header text-white" style="background: var(--skillo-dark);">
                <h6 class="modal-title fw-bold font-monsserat mb-0">
                    <i class="bi bi-person-plus-fill me-2" style="color: var(--skillo-primary);"></i>Tambah / Import Excel Murid ke Sesi Sekolah
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body p-4">
                {{-- LANGKAH 1: PILIH SEKOLAH & SESI TUJUAN TERLEBIH DAHULU --}}
                <div class="p-3 rounded-4 mb-4" style="background: #fff9e8; border: 1.5px solid #fef3c7;">
                    <div class="fw-black font-monsserat text-dark small mb-2 text-uppercase" style="letter-spacing: 0.4px;">
                        <i class="bi bi-1-circle-fill me-1 text-danger"></i>Langkah 1: Pilih Sekolah & Bagian Sesi Tujuan Terlebih Dahulu
                    </div>
                    <div class="row g-2">
                        <div class="col-12 col-md-5">
                            <label class="form-label fw-bold small text-secondary mb-1">Pilih Sekolah</label>
                            <select id="masterSelectSekolahSiswa" class="form-select rounded-3 bg-white fw-semibold" onchange="filterDropdownSesiModalSiswa()">
                                <option value="">— Semua Sekolah —</option>
                                @foreach($sekolahList as $sek)
                                    <option value="{{ $sek->id }}">{{ $sek->nama_sekolah }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 col-md-7">
                            <label class="form-label fw-bold small text-secondary mb-1">Pilih Bagian Sesi Tujuan <span class="text-danger">*</span></label>
                            <select id="masterSelectSesiSiswa" class="form-select rounded-3 bg-white fw-bold" required onchange="sinkronSesiKeFormSiswa()">
                                <option value="">— Pilih Sesi (Sesi 1 / Sesi 2 / Sesi 3) —</option>
                                @foreach($sesiList as $sOpt)
                                    <option value="{{ $sOpt->id }}"
                                            data-sekolah-id="{{ $sOpt->sekolah_id }}"
                                            data-tingkatan="{{ $sOpt->tingkatan }}">
                                        {{ $sOpt->sekolah?->nama_sekolah }} — {{ $sOpt->nama_sesi }} {{ $sOpt->tingkatan ? '(' . $sOpt->tingkatan . ')' : '' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <small class="text-muted d-block mt-1.5">
                        Sesuai alur sistem: pilih sesi terlebih dahulu (misal Sesi 1, Sesi 2, atau Sesi 3) agar murid yang diinput atau diimpor dari Excel langsung masuk ke sesi tersebut.
                    </small>
                </div>

                {{-- LANGKAH 2: PILIH METODE (IMPORT EXCEL / INPUT BARU / PILIH TERDAFTAR) --}}
                <div class="mb-3">
                    <label class="form-label fw-black font-monsserat small text-dark text-uppercase mb-2" style="letter-spacing: 0.4px;">
                        <i class="bi bi-2-circle-fill me-1 text-danger"></i>Langkah 2: Pilih Metode Penambahan Murid
                    </label>
                    <div class="row g-2">
                        <div class="col-12 col-md-4">
                            <div class="p-2.5 px-3 rounded-3 border h-100" id="boxMetodeImport" onclick="pilihMetodeMurid('import')"
                                 style="cursor: pointer; transition: all 0.2s ease;">
                                <div class="d-flex align-items-center">
                                    <input class="form-check-input me-2 mt-0" type="radio" name="metode_ui" id="radioMetodeImport" value="import">
                                    <label class="form-check-label fw-bold text-dark small" for="radioMetodeImport" style="cursor: pointer;">
                                        <i class="bi bi-file-earmark-excel-fill text-success me-1"></i>Import dari Excel
                                    </label>
                                </div>
                                <small class="text-muted d-block mt-1" style="font-size: 0.72rem;">Upload tabel Nomor, Nama Lengkap, Nama Panggilan, No. Telepon</small>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="p-2.5 px-3 rounded-3 border h-100" id="boxMetodeBaru" onclick="pilihMetodeMurid('baru')"
                                 style="cursor: pointer; transition: all 0.2s ease;">
                                <div class="d-flex align-items-center">
                                    <input class="form-check-input me-2 mt-0" type="radio" name="metode_ui" id="radioMetodeBaru" value="baru" checked>
                                    <label class="form-check-label fw-bold text-dark small" for="radioMetodeBaru" style="cursor: pointer;">
                                        <i class="bi bi-person-plus-fill text-danger me-1"></i>Input Manual Baru
                                    </label>
                                </div>
                                <small class="text-muted d-block mt-1" style="font-size: 0.72rem;">Ketik 1 murid baru beserta nama panggilan & WA</small>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="p-2.5 px-3 rounded-3 border h-100" id="boxMetodePilih" onclick="pilihMetodeMurid('pilih')"
                                 style="cursor: pointer; transition: all 0.2s ease;">
                                <div class="d-flex align-items-center">
                                    <input class="form-check-input me-2 mt-0" type="radio" name="metode_ui" id="radioMetodePilih" value="pilih">
                                    <label class="form-check-label fw-bold text-dark small" for="radioMetodePilih" style="cursor: pointer;">
                                        <i class="bi bi-person-check-fill text-primary me-1"></i>Murid Terdaftar
                                    </label>
                                </div>
                                <small class="text-muted d-block mt-1" style="font-size: 0.72rem;">Masukkan murid yang sudah ada di database ke sesi ini</small>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ============================================================ --}}
                {{-- FORM A: IMPORT MURID DARI EXCEL (.xlsx / .xls / .csv) --}}
                {{-- ============================================================ --}}
                <form action="{{ route('admin.siswa.import') }}" method="POST" enctype="multipart/form-data" id="formImportExcelSiswa" class="d-none">
                    @csrf
                    <input type="hidden" name="sesi_id" id="importExcelSesiId" required>
                    <input type="hidden" name="parsed_rows" id="importExcelParsedRows">

                    <div class="p-3 rounded-4 border bg-light mb-3">
                        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2 mb-2">
                            <div class="fw-bold text-dark small">
                                <i class="bi bi-table me-1 text-success"></i>Format Header Tabel di Atas File Excel:
                            </div>
                            <a href="{{ route('admin.siswa.template') }}" class="btn btn-sm btn-outline-success rounded-pill py-1 px-3 fw-bold">
                                <i class="bi bi-download me-1"></i>Download Template Excel
                            </a>
                        </div>

                        <div class="table-responsive rounded-3 border bg-white mb-2">
                            <table class="table table-sm table-bordered mb-0 small text-center">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Nomor</th>
                                        <th>Nama Lengkap</th>
                                        <th>Nama Panggilan</th>
                                        <th>Nomor Telepon</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="text-muted">
                                        <td>1</td>
                                        <td>Keano Putra Pratama</td>
                                        <td>Keano</td>
                                        <td>081234567801</td>
                                    </tr>
                                    <tr class="text-muted">
                                        <td>2</td>
                                        <td>Aisyah Putri Maharani</td>
                                        <td>Aisyah</td>
                                        <td>081234567802</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <small class="text-muted d-block">
                            Pembeda sesi ditentukan dari <strong>Sesi Tujuan</strong> yang Anda pilih pada Langkah 1 di atas (apakah anak ini ikut di Sesi 1, Sesi 2, atau Sesi 3).
                        </small>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-12 col-md-7">
                            <label class="form-label fw-bold small text-secondary">Pilih File Excel (`.xlsx`, `.xls`, atau `.csv`) <span class="text-danger">*</span></label>
                            <input type="file" name="file_excel" id="inputFileExcelSiswa" class="form-control rounded-3" accept=".xlsx,.xls,.csv" required onchange="previewFileExcelSiswa(this)">
                        </div>
                        <div class="col-12 col-md-5">
                            <label class="form-label fw-bold small text-secondary">Tingkatan / Course Sesi (Opsional)</label>
                            <input type="text" name="default_course" id="importDefaultCourse" class="form-control rounded-3" placeholder="Contoh: Course E">
                        </div>
                    </div>

                    {{-- Preview Hasil Pembacaan File Excel sebelum di-Submit --}}
                    <div id="boxPreviewExcel" class="d-none mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="fw-bold small text-success"><i class="bi bi-check-circle-fill me-1"></i>Pratinjau Data Murid dari Excel (<span id="countPreviewExcel">0</span> baris):</span>
                        </div>
                        <div class="table-responsive border rounded-3 bg-white" style="max-height: 200px; overflow-y: auto;">
                            <table class="table table-sm table-hover align-middle mb-0 small">
                                <thead class="bg-light sticky-top">
                                    <tr>
                                        <th class="px-2">No</th>
                                        <th>Nama Lengkap</th>
                                        <th>Nama Panggilan</th>
                                        <th>Nomor Telepon</th>
                                    </tr>
                                </thead>
                                <tbody id="tbodyPreviewExcel"></tbody>
                            </table>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 pt-2 border-top">
                        <button type="button" class="btn btn-skillo-outline" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-skillo-primary" onclick="return validasiSebelumSubmitSiswa('import')">
                            <i class="bi bi-cloud-arrow-up-fill me-1"></i>Import & Daftarkan ke Sesi Ini
                        </button>
                    </div>
                </form>

                {{-- ============================================================ --}}
                {{-- FORM B: INPUT MANUAL MURID BARU / PILIH MURID TERDAFTAR --}}
                {{-- ============================================================ --}}
                <form action="{{ route('admin.siswa.store') }}" method="POST" id="formTambahMuridManual">
                    @csrf
                    <input type="hidden" name="sesi_id" id="manualSesiId" required>
                    <input type="hidden" name="tipe_input" id="hiddenTipeInputManual" value="baru">

                    {{-- Sub-bagian Input Murid Baru --}}
                    <div id="sectionFormBaru">
                        <div class="row g-3 mb-3">
                            <div class="col-12 col-md-7">
                                <label class="form-label fw-bold small text-secondary">Nama Lengkap Murid <span class="text-danger">*</span></label>
                                <input type="text" name="nama" id="inputNamaMuridBaru" class="form-control rounded-3" required placeholder="Contoh: Keano Putra Pratama">
                            </div>
                            <div class="col-12 col-md-5">
                                <label class="form-label fw-bold small text-secondary">Nama Panggilan</label>
                                <input type="text" name="nama_panggilan" id="inputPanggilanMuridBaru" class="form-control rounded-3" placeholder="Contoh: Keano">
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-12 col-md-7">
                                <label class="form-label fw-bold small text-secondary">Nomor Telepon / WhatsApp</label>
                                <div class="input-group">
                                    <span class="input-group-text border-end-0 bg-light text-muted"><i class="bi bi-telephone-fill"></i></span>
                                    <input type="text" name="no_hp" id="inputNoHpMuridBaru" class="form-control border-start-0 rounded-end-3" placeholder="Contoh: 08123456789">
                                </div>
                            </div>
                            <div class="col-12 col-md-5">
                                <label class="form-label fw-bold small text-secondary">Tingkatan / Course di Sesi Ini</label>
                                <input type="text" name="course" id="inputCourseMuridBaru" class="form-control rounded-3" placeholder="Contoh: Course E">
                            </div>
                        </div>
                    </div>

                    {{-- Sub-bagian Pilih Murid Terdaftar --}}
                    <div id="sectionFormPilih" class="d-none">
                        <div class="row g-3 mb-3">
                            <div class="col-12 col-md-8">
                                <label class="form-label fw-bold small text-secondary">Pilih Murid Yang Sudah Terdaftar <span class="text-danger">*</span></label>
                                <select name="siswa_id" id="selectSiswaTerdaftar" class="form-select rounded-3">
                                    <option value="">— Cari / Pilih Nama Murid —</option>
                                    @foreach($allSiswa as $s)
                                        <option value="{{ $s->id }}">
                                            {{ $s->nama }} {{ $s->nama_panggilan ? '(' . $s->nama_panggilan . ')' : '' }} — {{ $s->nim }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 col-md-4">
                                <label class="form-label fw-bold small text-secondary">Tingkatan / Course</label>
                                <input type="text" id="inputCourseMuridPilih" class="form-control rounded-3" placeholder="Contoh: Course E"
                                       oninput="document.getElementById('inputCourseMuridBaru').value = this.value">
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 pt-2 border-top">
                        <button type="button" class="btn btn-skillo-outline" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-skillo-primary" onclick="return validasiSebelumSubmitSiswa('manual')">
                            <i class="bi bi-check-circle-fill me-1"></i>Simpan & Daftarkan ke Sesi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- ============================================================ --}}
{{-- MODAL LIST: Lihat Daftar Murid di Setiap Sesi --}}
{{-- ============================================================ --}}
@foreach($sesiList as $sesi)
<div class="modal fade" id="modalDaftarMuridSesi-{{ $sesi->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header text-white" style="background: var(--skillo-dark);">
                <div>
                    <h6 class="modal-title fw-bold font-monsserat mb-0">
                        <i class="bi bi-mortarboard-fill me-2" style="color: var(--skillo-amber);"></i>
                        Daftar Murid — {{ $sesi->sekolah?->nama_sekolah }} ({{ $sesi->nama_sesi }})
                    </h6>
                    <small class="text-white-50">
                        Tingkatan: {{ $sesi->tingkatan ?: 'Semua Tingkatan' }} &bull;
                        Guru Pengampu: {{ $sesi->guru->pluck('name')->implode(', ') ?: 'Belum Ditugaskan' }}
                    </small>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center p-3 mb-3 rounded-3 gap-2" style="background: #fff9e8; border: 1.5px solid #fef3c7;">
                    <div>
                        <span class="text-secondary small fw-bold d-block text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.5px;">Informasi Sesi Belajar</span>
                        <h6 class="fw-black font-monsserat mb-0" style="color: var(--skillo-dark);">
                            {{ $sesi->sekolah?->nama_sekolah }} — {{ $sesi->nama_sesi }}
                            @if(!empty($sesi->tingkatan))
                                <span class="badge bg-warning text-dark ms-1">{{ $sesi->tingkatan }}</span>
                            @endif
                        </h6>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <span class="skillo-tag-amber">
                            <i class="bi bi-people-fill me-1"></i>{{ $sesi->siswa->count() }} Murid
                        </span>
                    </div>
                </div>

                @if($sesi->siswa->isEmpty())
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-people fs-2" style="color: var(--skillo-primary);"></i>
                        <h6 class="fw-bold font-monsserat text-dark mt-2">Belum Ada Murid di Sesi Ini</h6>
                        <p class="small text-muted mb-0">Gunakan tombol Tambah / Import Excel Murid untuk mengisi daftar murid pada {{ $sesi->nama_sesi }}.</p>
                    </div>
                @else
                    <div class="table-responsive border rounded-3 overflow-hidden">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light" style="border-bottom: 2px solid #f1f5f9;">
                                <tr class="text-secondary small fw-bold text-uppercase" style="font-size: 0.72rem;">
                                    <th class="px-3 py-2.5" style="width: 45px;">No</th>
                                    <th class="py-2.5" style="width: 120px;">NIM</th>
                                    <th class="py-2.5">Nama Lengkap</th>
                                    <th class="py-2.5">Nama Panggilan</th>
                                    <th class="py-2.5">Nomor Telepon</th>
                                    <th class="py-2.5">Tingkatan / Course</th>
                                    <th class="px-3 py-2.5 text-end" style="width: 130px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($sesi->siswa as $idx => $m)
                                <tr>
                                    <td class="px-3 fw-bold text-muted small">{{ $idx + 1 }}</td>
                                    <td>
                                        <span class="badge rounded-pill bg-light text-dark border font-monospace px-2.5 py-1 fw-bold">{{ $m->nim }}</span>
                                    </td>
                                    <td>
                                        <span class="fw-bold font-monsserat text-dark" style="font-size: 0.9rem;">{{ $m->nama }}</span>
                                    </td>
                                    <td>
                                        @if(!empty($m->nama_panggilan))
                                            <span class="badge rounded-pill bg-warning-subtle text-dark border border-warning-subtle px-2.5 py-1 fw-bold">
                                                {{ $m->nama_panggilan }}
                                            </span>
                                        @else
                                            <span class="text-muted small">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if(!empty($m->no_hp))
                                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $m->no_hp) }}" target="_blank"
                                               class="badge rounded-pill bg-light text-success border text-decoration-none py-1 px-2.5 fw-semibold">
                                                <i class="bi bi-whatsapp me-1 text-success"></i>{{ $m->no_hp }}
                                            </a>
                                        @else
                                            <span class="text-muted small">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <form action="{{ route('admin.sesi.siswa.update', [$sesi, $m]) }}" method="POST" class="d-flex gap-1 align-items-center">
                                            @csrf @method('PUT')
                                            <input type="text" name="course" value="{{ $m->pivot?->course ?? $sesi->tingkatan }}"
                                                   class="form-control form-control-sm rounded-pill px-2.5" style="max-width: 140px; font-size: 0.78rem;"
                                                   placeholder="Course E...">
                                            <button type="submit" class="btn btn-sm btn-outline-success rounded-pill py-0.5 px-2" title="Simpan Tingkatan/Course">
                                                <i class="bi bi-check2"></i>
                                            </button>
                                        </form>
                                    </td>
                                    <td class="px-3 text-end text-nowrap">
                                        <button type="button"
                                                class="btn btn-sm btn-outline-danger rounded-pill px-2.5 py-1 fw-semibold"
                                                style="font-size: 0.75rem;"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modalKonfirmasiKeluarkanMurid"
                                                data-action="{{ route('admin.sesi.siswa.destroy', [$sesi, $m]) }}"
                                                data-murid-nama="{{ $m->nama }}"
                                                data-murid-nim="{{ $m->nim }}"
                                                data-sesi-nama="{{ $sesi->sekolah?->nama_sekolah }} — {{ $sesi->nama_sesi }}">
                                            <i class="bi bi-box-arrow-right me-1"></i>Keluarkan
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
            <div class="modal-footer bg-light border-0">
                <button type="button" class="btn btn-sm btn-skillo-outline" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endforeach

{{-- ============================================================ --}}
{{-- MODAL: Master Data Seluruh Murid --}}
{{-- ============================================================ --}}
<div class="modal fade" id="modalSemuaMurid" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header text-white" style="background: var(--skillo-dark);">
                <div>
                    <h6 class="modal-title fw-bold font-monsserat mb-0">
                        <i class="bi bi-mortarboard-fill me-2" style="color: var(--skillo-amber);"></i>Master Data Seluruh Murid
                    </h6>
                    <small class="text-white-50">Total {{ $allSiswa->count() }} murid yang terdaftar di sistem Skillo Academy</small>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                @if($allSiswa->isEmpty())
                    <div class="text-center py-5 text-muted">Belum ada murid terdaftar.</div>
                @else
                    <div class="table-responsive border rounded-3 overflow-hidden">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light" style="border-bottom: 2px solid #f1f5f9;">
                                <tr class="text-secondary small fw-bold text-uppercase" style="font-size: 0.72rem;">
                                    <th class="px-3 py-2.5">No</th>
                                    <th class="py-2.5">NIM</th>
                                    <th class="py-2.5">Nama Lengkap</th>
                                    <th class="py-2.5">Nama Panggilan</th>
                                    <th class="py-2.5">Nomor Telepon</th>
                                    <th class="py-2.5">Sekolah & Sesi Diikuti</th>
                                    <th class="px-3 py-2.5 text-end">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($allSiswa as $idx => $s)
                                <tr>
                                    <td class="px-3 fw-bold text-muted small">{{ $idx + 1 }}</td>
                                    <td><span class="badge rounded-pill bg-light text-dark border font-monospace">{{ $s->nim }}</span></td>
                                    <td class="fw-bold font-monsserat text-dark small">{{ $s->nama }}</td>
                                    <td>{{ $s->nama_panggilan ?: '-' }}</td>
                                    <td>{{ $s->no_hp ?: '-' }}</td>
                                    <td>
                                        @forelse($s->sesi as $sesiM)
                                            <span class="badge rounded-pill bg-light text-dark border px-2 py-0.5 me-1 mb-1">
                                                {{ $sesiM->sekolah?->nama_sekolah }} — {{ $sesiM->nama_sesi }}
                                                {{ $sesiM->pivot?->course ? '(' . $sesiM->pivot->course . ')' : '' }}
                                            </span>
                                        @empty
                                            <span class="text-muted small fst-italic">Belum masuk sesi</span>
                                        @endforelse
                                    </td>
                                    <td class="px-3 text-end text-nowrap">
                                        <button type="button"
                                                class="btn btn-sm btn-outline-danger rounded-pill px-2.5 py-1 fw-bold"
                                                style="font-size: 0.75rem;"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modalKonfirmasiHapusMuridPermanen"
                                                data-action="{{ route('admin.siswa.destroy', $s) }}"
                                                data-murid-nama="{{ $s->nama }}"
                                                data-murid-nim="{{ $s->nim }}">
                                            <i class="bi bi-trash-fill me-1"></i>Hapus
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
            <div class="modal-footer bg-light border-0">
                <button type="button" class="btn btn-sm btn-skillo-outline" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

{{-- ============================================================ --}}
{{-- MODAL: Master Data Seluruh Guru & Mentor --}}
{{-- ============================================================ --}}
<div class="modal fade" id="modalSemuaGuru" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header text-white" style="background: var(--skillo-dark);">
                <div>
                    <h6 class="modal-title fw-bold font-monsserat mb-0">
                        <i class="bi bi-person-video3 me-2" style="color: #10b981;"></i>Master Data Seluruh Guru & Mentor
                    </h6>
                    <small class="text-white-50">Total {{ $totalGuru }} guru & mentor terdaftar</small>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                @if($guruList->isEmpty())
                    <div class="text-center py-5 text-muted">Belum ada akun guru terdaftar.</div>
                @else
                    <div class="table-responsive border rounded-3 overflow-hidden">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr class="text-secondary small fw-bold text-uppercase" style="font-size: 0.72rem;">
                                    <th class="px-3 py-2.5">No</th>
                                    <th class="py-2.5">Nama Mentor</th>
                                    <th class="py-2.5">Username</th>
                                    <th class="py-2.5">Email</th>
                                    <th class="py-2.5">WhatsApp</th>
                                    <th class="py-2.5">Sekolah & Sesi Diampu</th>
                                    <th class="px-3 py-2.5 text-end">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($guruList as $idx => $g)
                                <tr>
                                    <td class="px-3 fw-bold text-muted small">{{ $idx + 1 }}</td>
                                    <td class="fw-bold text-dark small">{{ $g->name }}</td>
                                    <td class="small text-muted font-monospace">{{ $g->username }}</td>
                                    <td class="small">{{ $g->email }}</td>
                                    <td class="small">{{ $g->no_hp ?: '-' }}</td>
                                    <td>
                                        @forelse($g->sesi as $s)
                                            <span class="badge rounded-pill bg-light text-dark border px-2 py-0.5 me-1 mb-1">
                                                {{ $s->sekolah?->nama_sekolah }} — {{ $s->nama_sesi }}
                                            </span>
                                        @empty
                                            <span class="text-muted small fst-italic">Belum mengampu sesi</span>
                                        @endforelse
                                    </td>
                                    <td class="px-3 text-end">
                                        <button type="button"
                                                class="btn btn-sm btn-outline-danger rounded-pill px-2.5 py-1 fw-bold"
                                                style="font-size: 0.75rem;"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modalKonfirmasiHapusGuruPermanen"
                                                data-action="{{ route('admin.guru.destroy', $g->id) }}"
                                                data-guru-nama="{{ $g->name }}"
                                                data-guru-email="{{ $g->email }}">
                                            <i class="bi bi-trash-fill me-1"></i>Hapus Permanen
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
            <div class="modal-footer bg-light border-0">
                <button type="button" class="btn btn-sm btn-skillo-outline" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

{{-- ============================================================ --}}
{{-- MODAL: Tambah Guru --}}
{{-- ============================================================ --}}
<div class="modal fade" id="modalTambahGuru" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header text-white" style="background: var(--skillo-dark);">
                <h6 class="modal-title fw-bold font-monsserat mb-0"><i class="bi bi-person-badge-fill me-2 text-warning"></i>Buat Akun Guru / Mentor</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.guru.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-secondary">Nama Lengkap Mentor <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control rounded-3" required placeholder="Contoh: Muhammad Darussalam">
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-bold small text-secondary">Username <span class="text-danger">*</span></label>
                            <input type="text" name="username" class="form-control rounded-3" required placeholder="darussalam_mentor">
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-bold small text-secondary">Nomor WhatsApp</label>
                            <input type="text" name="no_hp" class="form-control rounded-3" placeholder="0857...">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-secondary">Alamat Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control rounded-3" required placeholder="mentor@skillo.id">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-secondary">Password Awal <span class="text-danger">*</span></label>
                        <input type="password" name="password" class="form-control rounded-3" required placeholder="Minimal 6 karakter">
                    </div>
                </div>
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-skillo-outline" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-skillo-amber">Buat Akun Mentor</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ============================================================ --}}
{{-- MODAL: Arsip Guru --}}
{{-- ============================================================ --}}
@if(isset($arsipGuruList) && $arsipGuruList->isNotEmpty())
<div class="modal fade" id="modalArsipGuru" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header text-white" style="background: var(--skillo-dark);">
                <h6 class="modal-title fw-bold font-monsserat mb-0">
                    <i class="bi bi-archive me-2 text-warning"></i>Arsip Akun Guru & Mentor
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="table-responsive border rounded-3 overflow-hidden">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr class="text-secondary small fw-bold text-uppercase" style="font-size: 0.72rem;">
                                <th class="px-3 py-2.5">Nama Mentor</th>
                                <th class="py-2.5">Username</th>
                                <th class="py-2.5">Email</th>
                                <th class="px-3 py-2.5 text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($arsipGuruList as $ag)
                            <tr>
                                <td class="px-3 fw-bold text-dark small">{{ $ag->name }}</td>
                                <td class="small text-muted font-monospace">{{ $ag->username }}</td>
                                <td class="small text-muted">{{ $ag->email }}</td>
                                <td class="px-3 text-end text-nowrap">
                                    <form action="{{ route('admin.guru.restore', $ag->id) }}" method="POST" class="d-inline me-1">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-success rounded-pill px-2.5 py-1" style="font-size: 0.75rem;">
                                            <i class="bi bi-arrow-counterclockwise me-1"></i>Pulihkan
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

{{-- ============================================================ --}}
{{-- MODAL KONFIRMASI: Hapus Sekolah, Hapus Sesi, Keluarkan Murid, Hapus Murid, Hapus Guru --}}
{{-- ============================================================ --}}
<div class="modal fade" id="modalKonfirmasiHapusSekolah" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header text-white" style="background: var(--skillo-dark);">
                <h6 class="modal-title fw-bold font-monsserat mb-0">
                    <i class="bi bi-exclamation-triangle-fill me-2" style="color: var(--skillo-primary);"></i>Konfirmasi Hapus Sekolah
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="formHapusSekolah" method="POST">
                @csrf @method('DELETE')
                <div class="modal-body p-4">
                    <h6 class="fw-black font-monsserat mb-1 text-dark" id="hapusSekolahNama">Nama Sekolah</h6>
                    <p class="small text-muted mb-0" id="hapusSekolahInfo">Seluruh sesi dan riwayat absensi pada sekolah ini akan dihapus permanen.</p>
                </div>
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-skillo-outline" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-skillo-primary">Hapus Permanen Sekolah</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalKonfirmasiHapusSesi" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header text-white" style="background: var(--skillo-dark);">
                <h6 class="modal-title fw-bold font-monsserat mb-0">
                    <i class="bi bi-exclamation-triangle-fill me-2" style="color: var(--skillo-primary);"></i>Konfirmasi Hapus Sesi
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="formHapusSesi" method="POST">
                @csrf @method('DELETE')
                <div class="modal-body p-4">
                    <h6 class="fw-black font-monsserat mb-1 text-dark" id="hapusSesiNama">Nama Sesi</h6>
                    <p class="small text-muted mb-0" id="hapusSesiInfo">Seluruh pertemuan dan riwayat absensi pada sesi ini akan dihapus.</p>
                </div>
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-skillo-outline" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-skillo-primary">Hapus Sesi</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalKonfirmasiKeluarkanMurid" tabindex="-1" aria-hidden="true" style="z-index: 1070;">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header text-white" style="background: var(--skillo-dark);">
                <h6 class="modal-title fw-bold font-monsserat mb-0">
                    <i class="bi bi-box-arrow-right me-2 text-warning"></i>Keluarkan Murid dari Sesi
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="formKeluarkanMurid" method="POST">
                @csrf @method('DELETE')
                <div class="modal-body p-4">
                    <h6 class="fw-black font-monsserat mb-1 text-dark" id="keluarkanMuridNama">Nama Murid</h6>
                    <small class="text-muted d-block" id="keluarkanMuridInfo"></small>
                </div>
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-skillo-outline" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-skillo-amber">Ya, Keluarkan dari Sesi</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalKonfirmasiHapusMuridPermanen" tabindex="-1" aria-hidden="true" style="z-index: 1070;">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header text-white" style="background: var(--skillo-dark);">
                <h6 class="modal-title fw-bold font-monsserat mb-0">
                    <i class="bi bi-trash-fill me-2 text-danger"></i>Hapus Data Murid Permanen
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="formHapusMuridPermanen" method="POST">
                @csrf @method('DELETE')
                <div class="modal-body p-4">
                    <h6 class="fw-black font-monsserat mb-1 text-dark" id="hapusMuridNama">Nama Murid</h6>
                    <small class="text-muted d-block" id="hapusMuridInfo"></small>
                </div>
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-skillo-outline" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger rounded-pill px-4 fw-bold">Hapus Permanen</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalKonfirmasiHapusGuruPermanen" tabindex="-1" aria-hidden="true" style="z-index: 1070;">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header text-white" style="background: var(--skillo-dark);">
                <h6 class="modal-title fw-bold font-monsserat mb-0">Konfirmasi Hapus Permanen Mentor</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="formHapusGuruPermanen" method="POST">
                @csrf @method('DELETE')
                <input type="hidden" name="tipe_hapus" value="permanen">
                <div class="modal-body p-4">
                    <h6 class="fw-black font-monsserat mb-1 text-dark" id="hapusGuruNama">Nama Mentor</h6>
                    <small class="text-muted d-block" id="hapusGuruEmail"></small>
                </div>
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-skillo-outline" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger rounded-pill px-4 fw-bold">Hapus Permanen</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalKonfirmasiHapusGuruDaftar" tabindex="-1" aria-hidden="true" style="z-index: 1070;">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header text-white" style="background: var(--skillo-dark);">
                <h6 class="modal-title fw-bold font-monsserat mb-0">Konfirmasi Arsipkan Mentor</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="formHapusGuruDaftar" method="POST">
                @csrf @method('DELETE')
                <input type="hidden" name="tipe_hapus" value="daftar">
                <div class="modal-body p-4">
                    <h6 class="fw-black font-monsserat mb-1 text-dark" id="hapusDaftarGuruNama">Nama Mentor</h6>
                    <small class="text-muted d-block" id="hapusDaftarGuruInfo"></small>
                </div>
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-skillo-outline" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-skillo-amber rounded-pill px-4 fw-bold">Arsipkan dari Daftar</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<!-- SheetJS untuk membaca & menampilkan pratinjau file Excel (.xlsx / .xls / .csv) langsung di browser -->
<script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>

<script>
    document.getElementById('currentDate').textContent =
        new Date().toLocaleDateString('id-ID', { weekday:'long', year:'numeric', month:'long', day:'numeric' });

    // Data Guru untuk generate baris sesi baru secara dinamis
    const daftarGuruJs = @json($guruList->map(fn($g) => ['id' => $g->id, 'name' => $g->name]));
    let counterSesiBaru = 2;

    function tambahBarisSesiSekolahBaru() {
        const container = document.getElementById('containerSesiSekolahBaru');
        const idx = counterSesiBaru++;
        const nomorSesi = idx + 1;

        let checkboxesHtml = '';
        if (daftarGuruJs.length === 0) {
            checkboxesHtml = '<span class="text-muted small">Belum ada akun guru.</span>';
        } else {
            daftarGuruJs.forEach(g => {
                checkboxesHtml += `
                    <div class="form-check me-2">
                        <input class="form-check-input" type="checkbox" name="sesi[${idx}][guru_ids][]" value="${g.id}" id="new_s${idx}_g_${g.id}">
                        <label class="form-check-label small fw-semibold text-dark" for="new_s${idx}_g_${g.id}" style="cursor: pointer;">
                            ${g.name}
                        </label>
                    </div>
                `;
            });
        }

        const div = document.createElement('div');
        div.className = 'p-3 rounded-4 border bg-light item-sesi-baru position-relative';
        div.innerHTML = `
            <button type="button" class="btn btn-sm btn-outline-danger rounded-circle position-absolute top-0 end-0 m-2 py-0 px-1.5"
                    onclick="this.closest('.item-sesi-baru').remove()" title="Hapus baris sesi ini">
                <i class="bi bi-x"></i>
            </button>
            <div class="row g-2 mb-2 pe-4">
                <div class="col-12 col-sm-6">
                    <label class="form-label fw-bold small text-secondary mb-1">Nama Sesi (Ketik Manual) <span class="text-danger">*</span></label>
                    <input type="text" name="sesi[${idx}][nama_sesi]" list="saranNamaSesiList" class="form-control form-control-sm rounded-3 fw-bold" value="Sesi ${nomorSesi}" required>
                </div>
                <div class="col-12 col-sm-6">
                    <label class="form-label fw-bold small text-secondary mb-1">Tingkatan / Course Sesi</label>
                    <input type="text" name="sesi[${idx}][tingkatan]" class="form-control form-control-sm rounded-3" placeholder="Contoh: Course G / Advance">
                </div>
            </div>
            <div>
                <label class="form-label fw-bold small text-secondary mb-1">Pilih Guru Pengampu (Bisa Lebih dari 1 Guru):</label>
                <div class="d-flex flex-wrap gap-2 p-2 bg-white rounded-3 border">
                    ${checkboxesHtml}
                </div>
            </div>
        `;
        container.appendChild(div);
        setTimeout(() => {
            div.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }, 50);
    }

    // Filter tampilan baris sesi di bawah kartu sekolah tertentu
    function filterSesiSekolah(sekolahId, selectedSesiId) {
        const rows = document.querySelectorAll(`.sesi-row-sekolah-${sekolahId}`);
        rows.forEach(r => {
            if (selectedSesiId === 'all' || r.dataset.sesiId === selectedSesiId) {
                r.classList.remove('d-none');
            } else {
                r.classList.add('d-none');
            }
        });
    }

    // Filter dropdown sesi pada bagian Rekap Mingguan
    function sinkronDropdownSesiRekap() {
        const sekolahId = document.getElementById('filterRekapSekolah').value;
        const selectSesi = document.getElementById('filterRekapSesi');
        Array.from(selectSesi.options).forEach(opt => {
            if (!opt.value) return;
            const match = !sekolahId || opt.dataset.sekolahId === sekolahId;
            opt.hidden = !match;
            if (!match && opt.selected) {
                selectSesi.value = '';
            }
        });
    }

    // Pencarian cepat pada tabel Rekap Mingguan
    function filterBarisRekapLive(keyword) {
        const q = (keyword || '').toLowerCase().trim();
        document.querySelectorAll('#tabelRekapDetailMurid tbody tr.baris-rekap-item').forEach(tr => {
            const text = tr.innerText.toLowerCase();
            tr.style.display = (!q || text.includes(q)) ? '' : 'none';
        });
    }

    // Sinkronisasi Pilihan Sekolah -> Sesi di dalam Modal Tambah/Import Murid
    function filterDropdownSesiModalSiswa() {
        const sekolahId = document.getElementById('masterSelectSekolahSiswa').value;
        const selectSesi = document.getElementById('masterSelectSesiSiswa');
        let firstVisible = '';

        Array.from(selectSesi.options).forEach(opt => {
            if (!opt.value) return;
            const match = !sekolahId || opt.dataset.sekolahId === sekolahId;
            opt.hidden = !match;
            if (match && !firstVisible) {
                firstVisible = opt.value;
            }
        });

        if (sekolahId && firstVisible) {
            selectSesi.value = firstVisible;
        }
        sinkronSesiKeFormSiswa();
    }

    function sinkronSesiKeFormSiswa() {
        const selectSesi = document.getElementById('masterSelectSesiSiswa');
        const sesiId = selectSesi.value;
        const opt = selectSesi.options[selectSesi.selectedIndex];
        const tingkatan = (opt && opt.dataset && opt.dataset.tingkatan) ? opt.dataset.tingkatan : '';

        document.getElementById('importExcelSesiId').value = sesiId;
        document.getElementById('manualSesiId').value = sesiId;

        if (tingkatan) {
            const c1 = document.getElementById('importDefaultCourse');
            const c2 = document.getElementById('inputCourseMuridBaru');
            const c3 = document.getElementById('inputCourseMuridPilih');
            if (c1 && !c1.value) c1.value = tingkatan;
            if (c2 && !c2.value) c2.value = tingkatan;
            if (c3 && !c3.value) c3.value = tingkatan;
        }
    }

    function validasiSebelumSubmitSiswa(mode) {
        const sesiId = document.getElementById('masterSelectSesiSiswa').value;
        if (!sesiId) {
            document.getElementById('masterSelectSesiSiswa').focus();
            return false;
        }
        sinkronSesiKeFormSiswa();
        return true;
    }

    // Pengalih Metode di Modal Murid: 'import' | 'baru' | 'pilih'
    function pilihMetodeMurid(metode) {
        const boxImport = document.getElementById('boxMetodeImport');
        const boxBaru   = document.getElementById('boxMetodeBaru');
        const boxPilih  = document.getElementById('boxMetodePilih');

        const rImport   = document.getElementById('radioMetodeImport');
        const rBaru     = document.getElementById('radioMetodeBaru');
        const rPilih    = document.getElementById('radioMetodePilih');

        const formImport = document.getElementById('formImportExcelSiswa');
        const formManual = document.getElementById('formTambahMuridManual');
        const secBaru    = document.getElementById('sectionFormBaru');
        const secPilih   = document.getElementById('sectionFormPilih');
        const tipeManual = document.getElementById('hiddenTipeInputManual');
        const inputNama  = document.getElementById('inputNamaMuridBaru');
        const selectMurid = document.getElementById('selectSiswaTerdaftar');

        // Reset border
        [boxImport, boxBaru, boxPilih].forEach(b => {
            b.style.border = '1.5px solid #e2e8f0';
            b.style.background = '#ffffff';
        });

        if (metode === 'import') {
            rImport.checked = true;
            boxImport.style.border = '2px solid #059669';
            boxImport.style.background = '#f0fdf4';
            formImport.classList.remove('d-none');
            formManual.classList.add('d-none');
        } else if (metode === 'pilih') {
            rPilih.checked = true;
            boxPilih.style.border = '2px solid #0284c7';
            boxPilih.style.background = '#f0f9ff';
            formImport.classList.add('d-none');
            formManual.classList.remove('d-none');
            secBaru.classList.add('d-none');
            secPilih.classList.remove('d-none');
            tipeManual.value = 'pilih';
            inputNama.removeAttribute('required');
            if (selectMurid) selectMurid.setAttribute('required', 'required');
        } else {
            rBaru.checked = true;
            boxBaru.style.border = '2px solid var(--skillo-primary)';
            boxBaru.style.background = '#fff4f2';
            formImport.classList.add('d-none');
            formManual.classList.remove('d-none');
            secBaru.classList.remove('d-none');
            secPilih.classList.add('d-none');
            tipeManual.value = 'baru';
            inputNama.setAttribute('required', 'required');
            if (selectMurid) selectMurid.removeAttribute('required');
        }
    }

    // Pratinjau & Parse File Excel di Browser menggunakan SheetJS
    function previewFileExcelSiswa(input) {
        const boxPreview = document.getElementById('boxPreviewExcel');
        const tbody = document.getElementById('tbodyPreviewExcel');
        const countSpan = document.getElementById('countPreviewExcel');
        const hiddenJson = document.getElementById('importExcelParsedRows');

        tbody.innerHTML = '';
        hiddenJson.value = '';
        boxPreview.classList.add('d-none');

        if (!input.files || !input.files[0] || typeof XLSX === 'undefined') {
            return;
        }

        const reader = new FileReader();
        reader.onload = function(e) {
            try {
                const data = new Uint8Array(e.target.result);
                const workbook = XLSX.read(data, { type: 'array' });
                const firstSheet = workbook.Sheets[workbook.SheetNames[0]];
                const rawRows = XLSX.utils.sheet_to_json(firstSheet, { header: 1, defval: '' });

                if (!rawRows || rawRows.length === 0) return;

                // Deteksi baris header (Nomor, Nama Lengkap, Nama Panggilan, Nomor Telepon)
                let headerIdx = -1;
                let colMap = { nama_lengkap: 1, nama_panggilan: 2, no_hp: 3, course: 4 };

                for (let i = 0; i < Math.min(rawRows.length, 10); i++) {
                    const rowLower = rawRows[i].map(v => String(v || '').toLowerCase().trim());
                    const joined = rowLower.join(' | ');
                    if (joined.includes('nama') || joined.includes('panggilan') || joined.includes('telepon')) {
                        headerIdx = i;
                        let detected = {};
                        rowLower.forEach((colName, cIdx) => {
                            if (!colName) return;
                            if (colName.includes('panggilan') || colName.includes('nickname')) {
                                detected.nama_panggilan = cIdx;
                            } else if (colName.includes('lengkap') || colName === 'nama' || colName.includes('nama siswa') || colName.includes('nama murid')) {
                                detected.nama_lengkap = cIdx;
                            } else if (colName.includes('telepon') || colName.includes('telp') || colName.includes('hp') || colName.includes('wa') || colName.includes('whatsapp')) {
                                detected.no_hp = cIdx;
                            } else if (colName.includes('course') || colName.includes('tingkat') || colName.includes('level')) {
                                detected.course = cIdx;
                            }
                        });
                        if (detected.nama_lengkap !== undefined) {
                            colMap.nama_lengkap = detected.nama_lengkap;
                            colMap.nama_panggilan = detected.nama_panggilan ?? null;
                            colMap.no_hp = detected.no_hp ?? null;
                            colMap.course = detected.course ?? null;
                        }
                        break;
                    }
                }

                const startIdx = headerIdx >= 0 ? headerIdx + 1 : 0;
                const parsedRecords = [];

                for (let r = startIdx; r < rawRows.length; r++) {
                    const row = rawRows[r];
                    const namaLengkap = String(row[colMap.nama_lengkap] ?? '').trim();
                    if (!namaLengkap) continue;
                    if (['nama lengkap', 'nama', 'nama siswa'].includes(namaLengkap.toLowerCase()) || namaLengkap.toLowerCase().includes('[contoh]') || namaLengkap.toLowerCase().includes('[example]')) continue;

                    const namaPanggilan = colMap.nama_panggilan !== null ? String(row[colMap.nama_panggilan] ?? '').trim() : '';
                    const noHp = colMap.no_hp !== null ? String(row[colMap.no_hp] ?? '').trim() : '';
                    const course = colMap.course !== null ? String(row[colMap.course] ?? '').trim() : '';

                    parsedRecords.push({
                        nama_lengkap: namaLengkap,
                        nama_panggilan: namaPanggilan,
                        no_hp: noHp,
                        course: course
                    });
                }

                if (parsedRecords.length > 0) {
                    hiddenJson.value = JSON.stringify(parsedRecords);
                    countSpan.textContent = parsedRecords.length;
                    parsedRecords.forEach((item, idx) => {
                        const tr = document.createElement('tr');
                        tr.innerHTML = `
                            <td class="px-2 text-muted fw-bold">${idx + 1}</td>
                            <td class="fw-bold text-dark">${item.nama_lengkap}</td>
                            <td>${item.nama_panggilan || '-'}</td>
                            <td>${item.no_hp || '-'}</td>
                        `;
                        tbody.appendChild(tr);
                    });
                    boxPreview.classList.remove('d-none');
                }
            } catch (err) {
                console.warn('Pratinjau Excel di sisi klien dilewati, server akan memproses file secara langsung:', err);
            }
        };
        reader.readAsArrayBuffer(input.files[0]);
    }

    // Event Listener saat Modal Tambah/Import Siswa dibuka
    document.getElementById('modalInputSiswa').addEventListener('show.bs.modal', function(e) {
        const btn = e.relatedTarget;
        const selectSekolah = document.getElementById('masterSelectSekolahSiswa');
        const selectSesi = document.getElementById('masterSelectSesiSiswa');

        if (btn && btn.dataset) {
            if (btn.dataset.sekolahId) {
                selectSekolah.value = btn.dataset.sekolahId;
                filterDropdownSesiModalSiswa();
            } else {
                selectSekolah.value = '';
                filterDropdownSesiModalSiswa();
            }

            if (btn.dataset.sesiId) {
                selectSesi.value = btn.dataset.sesiId;
            }
            if (btn.dataset.defaultCourse) {
                document.getElementById('importDefaultCourse').value = btn.dataset.defaultCourse;
                document.getElementById('inputCourseMuridBaru').value = btn.dataset.defaultCourse;
            }
            sinkronSesiKeFormSiswa();

            const metode = btn.dataset.defaultMetode || 'baru';
            pilihMetodeMurid(metode);
        } else {
            pilihMetodeMurid('baru');
        }
    });

    // Event Listener Modal Edit Sekolah
    document.getElementById('modalEditSekolah').addEventListener('show.bs.modal', function(e) {
        const btn = e.relatedTarget;
        document.getElementById('formEditSekolah').action = '/admin/sekolah/' + btn.dataset.sekolahId;
        document.getElementById('editNamaSekolah').value = btn.dataset.sekolahNama;
        document.getElementById('editDeskripsiSekolah').value = btn.dataset.deskripsi || '';
    });

    // Event Listener Modal Tambah Sesi
    document.getElementById('modalTambahSesi').addEventListener('show.bs.modal', function(e) {
        const btn = e.relatedTarget;
        document.getElementById('tambahSesiSekolahId').value = btn.dataset.sekolahId;
        document.getElementById('tambahSesiNamaSekolahLabel').textContent = btn.dataset.sekolahNama;
    });

    // Event Listener Modal Edit Sesi
    document.getElementById('modalEditSesi').addEventListener('show.bs.modal', function(e) {
        const btn = e.relatedTarget;
        document.getElementById('formEditSesi').action = '/admin/sesi/' + btn.dataset.sesiId;
        document.getElementById('editSesiSekolahLabel').textContent = btn.dataset.sekolahNama;
        document.getElementById('editSesiNama').value = btn.dataset.namaSesi;
        document.getElementById('editSesiTingkatan').value = btn.dataset.tingkatan || '';

        let guruIds = [];
        try {
            guruIds = JSON.parse(btn.dataset.guruIds || '[]');
        } catch (err) {}

        document.querySelectorAll('.edit-sesi-guru-checkbox').forEach(cb => {
            cb.checked = guruIds.includes(parseInt(cb.value, 10));
        });
    });

    // Event Listener Modal Konfirmasi Hapus Sekolah
    document.getElementById('modalKonfirmasiHapusSekolah').addEventListener('show.bs.modal', function(e) {
        const btn = e.relatedTarget;
        document.getElementById('formHapusSekolah').action = btn.dataset.action;
        document.getElementById('hapusSekolahNama').textContent = btn.dataset.sekolahNama;
        document.getElementById('hapusSekolahInfo').textContent =
            `Sekolah ini memiliki ${btn.dataset.sesiCount || 0} sesi dan ${btn.dataset.muridCount || 0} murid terdaftar.`;
    });

    // Event Listener Modal Konfirmasi Hapus Sesi
    document.getElementById('modalKonfirmasiHapusSesi').addEventListener('show.bs.modal', function(e) {
        const btn = e.relatedTarget;
        document.getElementById('formHapusSesi').action = btn.dataset.action;
        document.getElementById('hapusSesiNama').textContent = `${btn.dataset.sekolahNama} — ${btn.dataset.namaSesi}`;
        document.getElementById('hapusSesiInfo').textContent =
            `Sesi ini memiliki ${btn.dataset.muridCount || 0} murid terdaftar. Seluruh pertemuan dan riwayat absensi sesi ini akan dihapus.`;
    });

    // Event Listener Modal Konfirmasi Keluarkan Murid dari Sesi
    document.getElementById('modalKonfirmasiKeluarkanMurid').addEventListener('show.bs.modal', function(e) {
        const btn = e.relatedTarget;
        document.getElementById('formKeluarkanMurid').action = btn.dataset.action;
        document.getElementById('keluarkanMuridNama').textContent = btn.dataset.muridNama;
        document.getElementById('keluarkanMuridInfo').textContent = `NIM: ${btn.dataset.muridNim} • Sesi: ${btn.dataset.sesiNama}`;
    });

    // Event Listener Modal Konfirmasi Hapus Permanen Murid
    document.getElementById('modalKonfirmasiHapusMuridPermanen').addEventListener('show.bs.modal', function(e) {
        const btn = e.relatedTarget;
        document.getElementById('formHapusMuridPermanen').action = btn.dataset.action;
        document.getElementById('hapusMuridNama').textContent = btn.dataset.muridNama;
        document.getElementById('hapusMuridInfo').textContent = `NIM: ${btn.dataset.muridNim}`;
    });

    // Event Listener Modal Konfirmasi Hapus Guru Permanen & Arsip
    document.getElementById('modalKonfirmasiHapusGuruPermanen').addEventListener('show.bs.modal', function(e) {
        const btn = e.relatedTarget;
        document.getElementById('formHapusGuruPermanen').action = btn.dataset.action;
        document.getElementById('hapusGuruNama').textContent = btn.dataset.guruNama;
        document.getElementById('hapusGuruEmail').textContent = btn.dataset.guruEmail || '-';
    });

    document.getElementById('modalKonfirmasiHapusGuruDaftar').addEventListener('show.bs.modal', function(e) {
        const btn = e.relatedTarget;
        document.getElementById('formHapusGuruDaftar').action = btn.dataset.action;
        document.getElementById('hapusDaftarGuruNama').textContent = btn.dataset.guruNama;
        document.getElementById('hapusDaftarGuruInfo').textContent = btn.dataset.guruEmail || '-';
    });
</script>
@endpush
