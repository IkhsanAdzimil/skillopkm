@extends('layouts.app')

@section('title', 'Jurnal & Sesi Mengajar Guru')

@section('content')

{{-- Header & Filter Sesi / Tanggal --}}
<div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center mb-4 gap-3">
    <div>
        <div class="d-flex align-items-center gap-2 mb-2">
            <span class="skillo-tag">
                <i class="bi bi-journal-bookmark-fill"></i> Jurnal Mengajar Multi-Sesi
            </span>
            <span class="text-secondary small fw-semibold" id="currentDate"></span>
        </div>
        <h2 class="fw-black mb-1 font-monsserat" style="font-size: 1.85rem; letter-spacing: -0.03em;">
            Halo, {{ auth()->user()->name }}! 👋
        </h2>
        <p class="text-secondary small mb-0">
            Kelola presensi rekan guru pengampu, absensi siswa, serta 2 catatan progres & sesi pengganti di seluruh sesi yang Anda ampu.
        </p>
    </div>

    @if($daftarSesi->isNotEmpty())
    <div class="card border-0 shadow-sm p-3" style="border-radius: 18px; background: #ffffff;">
        <form action="{{ route('guru.dashboard') }}" method="GET" class="row g-2 align-items-end">
            <div class="col-12 col-sm-6">
                <label class="form-label fw-bold small text-secondary mb-1">
                    <i class="bi bi-layers-fill text-danger me-1"></i>Pilih Tampilan Sesi:
                </label>
                <select name="sesi_id" class="form-select form-select-sm rounded-pill px-3 fw-semibold" onchange="this.form.submit()">
                    <option value="semua" {{ $selectedSesiId === 'semua' ? 'selected' : '' }}>
                        Semua Sesi yang Diampu ({{ $daftarSesi->count() }} Sesi Sekaligus)
                    </option>
                    @foreach($daftarSesi as $optSesi)
                        <option value="{{ $optSesi->id }}" {{ (string)$selectedSesiId === (string)$optSesi->id ? 'selected' : '' }}>
                            {{ $optSesi->sekolah?->nama_sekolah }} — {{ $optSesi->nama_sesi }} {{ $optSesi->tingkatan ? '(' . $optSesi->tingkatan . ')' : '' }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 col-sm-6">
                <label class="form-label fw-bold small text-secondary mb-1">
                    <i class="bi bi-calendar-event-fill text-warning me-1"></i>Tanggal Pertemuan:
                </label>
                <div class="d-flex gap-2">
                    <input type="date" name="tanggal" value="{{ $tanggal }}"
                           class="form-control form-control-sm rounded-pill px-3 fw-semibold"
                           onchange="this.form.submit()">
                    <button type="submit" class="btn btn-sm btn-skillo-dark px-3">
                        <i class="bi bi-funnel-fill"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>
    @endif
</div>

@if($daftarSesi->isEmpty())
<div class="card p-4 text-center my-4 border-warning" style="background: #fff9e8; border-radius: 20px;">
    <div class="d-inline-flex align-items-center justify-content-center rounded-circle mx-auto mb-3"
         style="width: 60px; height: 60px; background: rgba(245, 158, 11, 0.15);">
        <i class="bi bi-exclamation-triangle-fill fs-2 text-warning"></i>
    </div>
    <h5 class="fw-black font-monsserat text-dark">Belum Ada Penugasan Sesi Sekolah</h5>
    <p class="text-secondary small mb-0">Anda belum ditugaskan ke sesi sekolah manapun. Silakan hubungi Administrator untuk mengatur jadwal sesi Anda.</p>
</div>
@else

    {{-- Navigasi Cepat Antar Sesi bila menampilkan semua sesi sekaligus --}}
    @if($sesiDitampilkan->count() > 1)
    <div class="d-flex flex-wrap align-items-center gap-2 mb-4">
        <span class="small fw-bold text-secondary me-1"><i class="bi bi-compass me-1"></i>Lompat ke Sesi:</span>
        @foreach($sesiDitampilkan as $navSesi)
            <a href="#card-sesi-{{ $navSesi->id }}" class="btn btn-sm btn-skillo-outline py-1 px-3">
                <i class="bi bi-building me-1 text-danger"></i>{{ $navSesi->sekolah?->nama_sekolah }} — <strong>{{ $navSesi->nama_sesi }}</strong>
            </a>
        @endforeach
    </div>
    @endif

    {{-- Loop Setiap Sesi yang Diampu Guru --}}
    @foreach($sesiDitampilkan as $sesiItem)
        @php
            $pertemuan    = $pertemuanMap[$sesiItem->id] ?? null;
            $absensiHari  = $absensiSiswaMap[$sesiItem->id] ?? collect();
            $absensiGuru  = $absensiGuruMap[$sesiItem->id] ?? collect();
        @endphp

        <div class="card mb-4 shadow-sm" id="card-sesi-{{ $sesiItem->id }}">
            <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2"
                 style="background: linear-gradient(135deg, #070B1F 0%, #1e293b 100%); color: #fff;">
                <div>
                    <div class="d-flex flex-wrap align-items-center gap-2 mb-1">
                        <span class="badge rounded-pill bg-amber-300 text-dark fw-black px-3 py-1" style="background: var(--skillo-amber); font-size: 0.75rem;">
                            <i class="bi bi-layers-fill me-1"></i>{{ $sesiItem->nama_sesi }}
                        </span>
                        @if(!empty($sesiItem->tingkatan))
                            <span class="badge rounded-pill text-white px-3 py-1 fw-bold" style="background: var(--skillo-primary); font-size: 0.75rem;">
                                <i class="bi bi-award-fill me-1"></i>Tingkatan: {{ $sesiItem->tingkatan }}
                            </span>
                        @endif
                        <span class="badge rounded-pill bg-white bg-opacity-10 text-white border border-white border-opacity-25 px-2.5 py-1" style="font-size: 0.72rem;">
                            <i class="bi bi-people-fill me-1"></i>{{ $sesiItem->siswa->count() }} Murid
                        </span>
                    </div>
                    <h5 class="fw-black mb-0 font-monsserat text-white" style="font-size: 1.25rem;">
                        <i class="bi bi-building me-1 text-warning"></i>{{ $sesiItem->sekolah?->nama_sekolah ?? 'Sekolah' }} — {{ $sesiItem->nama_sesi }}
                    </h5>
                </div>
                <div class="text-md-end">
                    <span class="badge rounded-pill bg-white text-dark px-3 py-2 fw-bold shadow-xs">
                        <i class="bi bi-calendar3 me-1 text-danger"></i>{{ \Carbon\Carbon::parse($tanggal)->translatedFormat('l, d F Y') }}
                    </span>
                </div>
            </div>

            <div class="card-body p-0">
                <form action="{{ route('guru.absensi.simpan') }}" method="POST" class="form-sesi-absensi" data-sesi-id="{{ $sesiItem->id }}">
                    @csrf
                    <input type="hidden" name="sesi_id" value="{{ $sesiItem->id }}">
                    <input type="hidden" name="tanggal" value="{{ $tanggal }}">

                    {{-- ============================================================ --}}
                    {{-- BAGIAN ATAS: DAFTAR & PRESENSI GURU PENGAMPU DI SESI INI --}}
                    {{-- Tiap guru di sesi ini dapat saling mengabsenkan rekan gurunya --}}
                    {{-- ============================================================ --}}
                    <div class="p-3 p-md-4 border-bottom" style="background: #f8fafc;">
                        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center mb-2 gap-1">
                            <div class="fw-extrabold text-dark font-monsserat" style="font-size: 0.9rem;">
                                <i class="bi bi-person-workspace me-1.5 text-success"></i>Guru Pengampu Sesi Ini & Status Kehadiran Guru
                            </div>
                            <small class="text-muted">
                                <i class="bi bi-info-circle me-1"></i>Sesama guru pengampu dapat saling mengabsenkan kehadiran rekan guru di sesi ini.
                            </small>
                        </div>

                        <div class="row g-2 mt-1">
                            @foreach($sesiItem->guru as $gPengampu)
                                @php
                                    $recGuru    = $absensiGuru[$gPengampu->id] ?? null;
                                    $statusGuru = $recGuru?->status ?? 'hadir';
                                @endphp
                                <div class="col-12 col-md-6 col-xl-4">
                                    <div class="p-2.5 px-3 rounded-4 border bg-white d-flex align-items-center justify-content-between gap-2 shadow-xs">
                                        <div class="d-flex align-items-center gap-2 overflow-hidden">
                                            <img src="https://ui-avatars.com/api/?name={{ urlencode($gPengampu->name) }}&background=f0fdf4&color=059669&bold=true&rounded=true"
                                                 class="avatar-sm border shrink-0" style="width: 34px; height: 34px;" alt="">
                                            <div class="text-truncate">
                                                <div class="fw-bold text-dark small text-truncate">
                                                    {{ $gPengampu->name }}
                                                    @if($gPengampu->id === auth()->id())
                                                        <span class="badge bg-success-subtle text-success border border-success-subtle ms-1" style="font-size: 0.65rem;">Anda</span>
                                                    @endif
                                                </div>
                                                <small class="text-muted d-block" style="font-size: 0.7rem;">Guru Pengampu</small>
                                            </div>
                                        </div>

                                        <div class="d-flex align-items-center gap-2 shrink-0">
                                            <div class="form-check form-check-inline m-0">
                                                <input class="form-check-input" type="radio"
                                                       name="absensi_guru[{{ $gPengampu->id }}]"
                                                       id="guru_hadir_{{ $sesiItem->id }}_{{ $gPengampu->id }}"
                                                       value="hadir"
                                                       {{ $statusGuru === 'hadir' ? 'checked' : '' }} style="cursor: pointer;">
                                                <label class="form-check-label small fw-bold text-success"
                                                       for="guru_hadir_{{ $sesiItem->id }}_{{ $gPengampu->id }}" style="cursor: pointer;">
                                                    Hadir
                                                </label>
                                            </div>
                                            <div class="form-check form-check-inline m-0">
                                                <input class="form-check-input" type="radio"
                                                       name="absensi_guru[{{ $gPengampu->id }}]"
                                                       id="guru_tidakhadir_{{ $sesiItem->id }}_{{ $gPengampu->id }}"
                                                       value="tidak_hadir"
                                                       {{ $statusGuru === 'tidak_hadir' ? 'checked' : '' }} style="cursor: pointer;">
                                                <label class="form-check-label small fw-bold text-danger"
                                                       for="guru_tidakhadir_{{ $sesiItem->id }}_{{ $gPengampu->id }}" style="cursor: pointer;">
                                                    Tidak Hadir
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Input Topik / Materi Pembelajaran Sesi Ini --}}
                    <div class="p-3 border-bottom" style="background: #fff9e8;">
                        <div class="row align-items-center g-2">
                            <label class="col-md-3 col-form-label fw-bold small text-dark">
                                <i class="bi bi-bookmark-star-fill text-warning me-1"></i>Materi / Topik Sesi Ini:
                            </label>
                            <div class="col-md-9">
                                <input type="text" name="materi" value="{{ $pertemuan?->materi ?? '' }}"
                                       class="form-control form-control-sm bg-white rounded-pill px-3 fw-medium"
                                       placeholder="Contoh: Course E Lesson 8 — Nested Loops & Debugging (opsional)">
                            </div>
                        </div>
                    </div>

                    @if($sesiItem->siswa->isEmpty())
                        <div class="text-center py-5 text-muted">
                            <i class="bi bi-people fs-1 text-secondary opacity-50"></i>
                            <p class="mt-2 fw-semibold mb-1">Belum ada murid yang terdaftar pada <strong>{{ $sesiItem->nama_sesi }}</strong> ini.</p>
                            <small class="text-muted">Admin dapat menambahkan atau mengimpor murid dari Excel ke sesi ini melalui Dashboard Admin.</small>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light" style="border-bottom: 2px solid #f1f5f9;">
                                    <tr class="text-secondary small fw-bold text-uppercase" style="letter-spacing: 0.5px; font-size: 0.72rem;">
                                        <th class="px-3 py-3" style="width:4%">No</th>
                                        <th class="py-3" style="width:20%">Murid & Panggilan</th>
                                        <th class="py-3" style="width:13%">Tingkatan / Course</th>
                                        <th class="py-3" style="width:23%">Status Kehadiran</th>
                                        <th class="py-3" style="width:20%">Catatan 1 (Sesi / Tingkatan)</th>
                                        <th class="py-3 pe-3" style="width:20%">Catatan 2 (Progres / Sesi Pengganti)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($sesiItem->siswa as $i => $siswa)
                                    @php
                                        $absen        = $absensiHari[$siswa->id] ?? null;
                                        $status       = $absen?->status ?? 'hadir';
                                        $notes1       = $absen?->notes_1 ?? '';
                                        $notes2       = $absen?->notes_2 ?? '';
                                        $siswaCourse  = $siswa->pivot?->course ?? $sesiItem->tingkatan ?? '';
                                    @endphp
                                    <tr class="siswa-row" data-sesi-id="{{ $sesiItem->id }}" data-siswa-id="{{ $siswa->id }}">
                                        <td class="px-3 fw-bold text-muted">{{ $i + 1 }}</td>
                                        <td>
                                            <div class="fw-bold font-monsserat text-dark" style="font-size: 0.92rem;">
                                                {{ $siswa->nama }}
                                                @if(!empty($siswa->nama_panggilan))
                                                    <span class="badge rounded-pill bg-warning-subtle text-dark border border-warning-subtle ms-1 fw-bold" style="font-size: 0.7rem;">
                                                        {{ $siswa->nama_panggilan }}
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="d-flex flex-wrap align-items-center gap-1 mt-1">
                                                <span class="badge rounded-pill bg-light text-secondary border font-monospace" style="font-size: 0.68rem;">
                                                    {{ $siswa->nim }}
                                                </span>
                                                @if(!empty($siswa->no_hp))
                                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $siswa->no_hp) }}" target="_blank"
                                                       class="badge rounded-pill bg-light text-success border text-decoration-none" style="font-size: 0.68rem;" title="Hubungi via WhatsApp">
                                                        <i class="bi bi-whatsapp me-1"></i>{{ $siswa->no_hp }}
                                                    </a>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <input type="text"
                                                   name="course[{{ $siswa->id }}]"
                                                   value="{{ $siswaCourse }}"
                                                   class="form-control form-control-sm rounded-pill px-2.5 bg-light fw-semibold"
                                                   style="font-size: 0.8rem;"
                                                   placeholder="Misal: Course E">
                                        </td>
                                        <td>
                                            <div class="d-flex flex-wrap gap-2">
                                                <div class="form-check m-0">
                                                    <input class="form-check-input" type="radio"
                                                           name="absensi[{{ $siswa->id }}]"
                                                           id="hadir_{{ $sesiItem->id }}_{{ $siswa->id }}" value="hadir"
                                                           {{ $status === 'hadir' ? 'checked' : '' }} style="cursor: pointer;">
                                                    <label class="form-check-label fw-bold text-dark small" for="hadir_{{ $sesiItem->id }}_{{ $siswa->id }}" style="cursor: pointer;">
                                                        Hadir
                                                    </label>
                                                </div>
                                                <div class="form-check m-0">
                                                    <input class="form-check-input" type="radio"
                                                           name="absensi[{{ $siswa->id }}]"
                                                           id="sakit_{{ $sesiItem->id }}_{{ $siswa->id }}" value="sakit"
                                                           {{ $status === 'sakit' ? 'checked' : '' }} style="cursor: pointer;">
                                                    <label class="form-check-label fw-bold text-dark small" for="sakit_{{ $sesiItem->id }}_{{ $siswa->id }}" style="cursor: pointer;">
                                                        Sakit
                                                    </label>
                                                </div>
                                                <div class="form-check m-0">
                                                    <input class="form-check-input" type="radio"
                                                           name="absensi[{{ $siswa->id }}]"
                                                           id="izin_{{ $sesiItem->id }}_{{ $siswa->id }}" value="izin"
                                                           {{ $status === 'izin' ? 'checked' : '' }} style="cursor: pointer;">
                                                    <label class="form-check-label fw-bold text-dark small" for="izin_{{ $sesiItem->id }}_{{ $siswa->id }}" style="cursor: pointer;">
                                                        Izin
                                                    </label>
                                                </div>
                                                <div class="form-check m-0">
                                                    <input class="form-check-input" type="radio"
                                                           name="absensi[{{ $siswa->id }}]"
                                                           id="alfa_{{ $sesiItem->id }}_{{ $siswa->id }}" value="alfa"
                                                           {{ $status === 'alfa' ? 'checked' : '' }} style="cursor: pointer;">
                                                    <label class="form-check-label fw-bold text-dark small" for="alfa_{{ $sesiItem->id }}_{{ $siswa->id }}" style="cursor: pointer;">
                                                        Alpa
                                                    </label>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-2">
                                            <div class="position-relative">
                                                <input type="text" class="form-control form-control-sm rounded-pill px-3 bg-light note-input note-1-input"
                                                       data-sesi-id="{{ $sesiItem->id }}"
                                                       data-siswa-id="{{ $siswa->id }}"
                                                       name="notes_1[{{ $siswa->id }}]"
                                                       value="{{ $notes1 }}"
                                                       placeholder="Misal: {{ $siswa->nama_panggilan ?? $siswa->nama }} {{ $sesiItem->nama_sesi }} {{ $siswaCourse ?: 'Course E' }}">
                                                <span class="note-saved-badge text-success small position-absolute end-0 top-50 translate-middle-y me-2.5 d-none" style="pointer-events: none;">
                                                    <i class="bi bi-check-circle-fill"></i>
                                                </span>
                                            </div>
                                        </td>
                                        <td class="py-2 pe-3">
                                            <div class="position-relative">
                                                <input type="text" class="form-control form-control-sm rounded-pill px-3 bg-light note-input note-2-input"
                                                       data-sesi-id="{{ $sesiItem->id }}"
                                                       data-siswa-id="{{ $siswa->id }}"
                                                       name="notes_2[{{ $siswa->id }}]"
                                                       value="{{ $notes2 }}"
                                                       placeholder="Progres / info sesi pengganti (Enter simpan)...">
                                                <span class="note-saved-badge text-success small position-absolute end-0 top-50 translate-middle-y me-2.5 d-none" style="pointer-events: none;">
                                                    <i class="bi bi-check-circle-fill"></i>
                                                </span>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif

                    <div class="card-footer bg-white border-top d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2 py-3 px-4">
                        <small class="text-muted">
                            <i class="bi bi-keyboard me-1"></i>Tips: Tekan <strong>Enter</strong> pada kolom Catatan 1 atau Catatan 2 untuk menyimpan cepat per anak, atau klik tombol simpan di kanan.
                        </small>
                        <button type="submit" class="btn btn-skillo-primary px-4 py-2 shrink-0">
                            <i class="bi bi-check2-all me-1"></i>Simpan Jurnal & Absensi {{ $sesiItem->nama_sesi }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endforeach

@endif

@endsection

@push('scripts')
<script>
    const now = new Date();
    const opts = { weekday:'long', year:'numeric', month:'long', day:'numeric' };
    const formatted = now.toLocaleDateString('id-ID', opts);
    const el1 = document.getElementById('currentDate');
    if (el1) el1.textContent = formatted;

    // Simpan Catatan 1 & Catatan 2 per murid dengan cepat saat menekan ENTER
    document.querySelectorAll('.note-input').forEach(function(input) {
        input.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();

                const row = this.closest('tr.siswa-row');
                const form = this.closest('form.form-sesi-absensi');
                if (!row || !form) return;

                const sesiId = this.dataset.sesiId;
                const siswaId = this.dataset.siswaId;
                const tanggal = form.querySelector('input[name="tanggal"]').value;
                const csrfToken = form.querySelector('input[name="_token"]').value;

                const note1Elem = row.querySelector('.note-1-input');
                const note2Elem = row.querySelector('.note-2-input');
                const statusRadio = row.querySelector(`input[name="absensi[${siswaId}]"]:checked`);
                const status = statusRadio ? statusRadio.value : 'hadir';

                const inputElem = this;
                const badgeElem = this.parentElement.querySelector('.note-saved-badge');

                fetch("{{ route('guru.absensi.catatan') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        sesi_id: sesiId,
                        siswa_id: siswaId,
                        tanggal: tanggal,
                        notes_1: note1Elem ? note1Elem.value : '',
                        notes_2: note2Elem ? note2Elem.value : '',
                        status: status
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        inputElem.classList.remove('bg-light');
                        inputElem.classList.add('border-success');
                        if (badgeElem) badgeElem.classList.remove('d-none');

                        setTimeout(() => {
                            inputElem.classList.remove('border-success');
                            inputElem.classList.add('bg-light');
                            if (badgeElem) badgeElem.classList.add('d-none');
                        }, 1200);
                    }
                })
                .catch(err => {
                    console.error('Gagal menyimpan catatan:', err);
                });
            }
        });
    });
</script>
@endpush
