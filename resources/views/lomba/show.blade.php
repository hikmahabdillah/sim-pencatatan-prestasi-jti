@extends('layouts.template', ['page' => $breadcrumb->title])

@section('content')
@include('layouts.navbar', ['title' => $breadcrumb->list])
@php
\Carbon\Carbon::setLocale('id');
@endphp
<div class="container-fluid py-4 h-100 flex-grow-1">

    <div class="card card-plain shadow-lg rounded-3 p-3 mb-4" style="height: 200px;">
        <div class="d-flex justify-content-center align-items-center h-100">
            <div class="blur-shadow-image position-relative overflow-hidden"
                style="width: 150px; height: 200px; border-radius: 25px;">
                @php
                $foto = $data->foto ? asset('storage/' . $data->foto) : asset('image/fotoDefault.jpg');
                @endphp
                <a href="{{ $foto }}" target="_blank" rel="noopener noreferrer">
                    <img class="w-100 h-100 shadow-lg" style="object-fit: cover;" src="{{ $foto }}" alt="Poster Lomba">
                </a>
            </div>
        </div>
    </div>
    <div class="card shadow rounded-3 border">
        <div class="card-body position-relative">
            <h5 class="fw-semibold mb-4 text-primary">Informasi Detail Lomba</h5>

            <div class="mb-2 d-flex align-items-center gap-4">
                <p class="mb-0 text-muted small fw-bold" style="min-width: 150px;">Nama Lomba</p>
                <p class="mb-0 fw-semibold">: {{ $data->nama_lomba }}</p>
            </div>

            <div class="mb-2 d-flex align-items-center gap-4">
                <p class="mb-0 text-muted small fw-bold" style="min-width: 150px;">Tingkat Lomba</p>
                <p class="mb-0 fw-semibold">: {{ $data->tingkatPrestasi->nama_tingkat_prestasi ?? '-' }}</p>
            </div>

            <div class="mb-2 d-flex align-items-center gap-4">
                <p class="mb-0 text-muted small fw-bold" style="min-width: 150px;">Kategori Lomba</p>
                <p class="mb-0 fw-semibold">
                    :
                    {{ $data->kategoris->pluck('nama_kategori')->implode(', ') }}
                </p>
            </div>

            <div class="mb-2 d-flex align-items-center gap-4">
                <p class="mb-0 text-muted small fw-bold" style="min-width: 150px;">Tipe Lomba</p>
                <p class="mb-0 fw-semibold">: {{ $data->tipe_lomba ?? '-' }}</p>
            </div>

            <div class="mb-2 d-flex align-items-center gap-4">
                <p class="mb-0 text-muted small fw-bold" style="min-width: 150px;">Penyelenggara</p>
                <p class="mb-0 fw-semibold">: {{ $data->penyelenggara ?? '-' }}</p>
            </div>

            <div class="mb-2 d-flex align-items-center gap-4">
                <p class="mb-0 text-muted small fw-bold" style="min-width: 150px;">Deskripsi</p>
                <p class="mb-0 fw-semibold">: {{ $data->deskripsi }}</p>
            </div>

            <div class="mb-2 d-flex align-items-center gap-4">
                <p class="mb-0 text-muted small fw-bold" style="min-width: 150px;">Biaya Pendaftaran</p>
                <p class="mb-0 fw-semibold">:
                    @if ($data->biaya_pendaftaran == 1)
                    Berbayar
                    @elseif ($data->biaya_pendaftaran == 0)
                    Tidak Berbayar
                    @else
                    -
                    @endif
                </p>
            </div>

            <div class="mb-2 d-flex align-items-center gap-4">
                <p class="mb-0 text-muted small fw-bold" style="min-width: 150px;">Benefit Lomba</p>
                <p class="mb-0 fw-semibold">:
                    @if ($data->berhadiah == 1)
                    Berhadiah
                    @elseif ($data->berhadiah == 0)
                    Tidak Berhadiah
                    @else
                    -
                    @endif
                </p>
            </div>

            <div class="mb-2 d-flex align-items-center gap-4">
                <p class="mb-0 text-muted small fw-bold" style="min-width: 150px;">Tanggal Mulai Lomba</p>
                <p class="mb-0 fw-semibold">: {{ \Carbon\Carbon::parse($data->tanggal_mulai)->translatedFormat('d F Y') }}</p>
            </div>

            <div class="mb-2 d-flex align-items-center gap-4">
                <p class="mb-0 text-muted small fw-bold" style="min-width: 150px;">Tanggal Selesai Lomba</p>
                <p class="mb-0 fw-semibold">: {{ \Carbon\Carbon::parse($data->tanggal_selesai)->translatedFormat('d F Y') }}</p>
            </div>

            <div class="mb-2 d-flex align-items-center gap-4">
                <p class="mb-0 text-muted small fw-bold" style="min-width: 150px;">Deadline Pendaftaran</p>
                <p class="mb-0 fw-semibold">: {{ \Carbon\Carbon::parse($data->deadline_pendaftaran)->translatedFormat('d F Y') }}</p>
            </div>

            <div class="mb-2 d-flex align-items-center gap-4">
                <p class="mb-0 text-muted small fw-bold" style="min-width: 150px;">Link Pendaftaran</p>
                <p class="mb-0 fw-semibold">:
                    @if ($data->link_pendaftaran && $data->link_pendaftaran !== '-')
                    <a href="{{ $data->link_pendaftaran }}" target="_blank">{{ $data->link_pendaftaran }}</a>
                    @else
                    -
                    @endif
                </p>
            </div>
            <div class="mb-2 d-flex align-items-center gap-4">
                <p class="mb-0 text-muted small fw-bold" style="min-width: 150px;">Status Verifikasi</p>
                <p class="mb-0 fw-semibold">:
                    @if (is_null($data->status_verifikasi))
                    Belum Diverifikasi
                    @elseif ($data->status_verifikasi == 1)
                    Disetujui
                    @elseif ($data->status_verifikasi == 0)
                    Ditolak
                    @endif
                </p>
            </div>
            @if ($data->status_verifikasi === 0 && $data->catatan_penolakan)
            <div class="mb-2 d-flex align-items-center gap-4">
                <p class="mb-0 text-muted small fw-bold" style="min-width: 150px;">Catatan Penolakan</p>
                <p class="mb-0 fw-semibold">: {{ $data->catatan_penolakan }}</p>
            </div>
            @endif
            <div class="card-footer d-flex justify-content-between align-items-center bg-transparent">
                <a href="{{ route('lomba.manajemen') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Kembali
                </a>
                <div>
                    <form id="form-setujui" action="{{ url('lomba/manajemen-lomba/' . $data->id_lomba . '/setujui') }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="button" id="btn-setujui" class="btn btn-success me-2">Setujui</button>
                    </form>

                    <form id="form-tolak" action="{{ url('lomba/manajemen-lomba/' . $data->id_lomba . '/tolak') }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="button" id="btn-tolak" class="btn btn-danger">Tolak</button>
                    </form>
                </div>
            </div>
        </div>
        @include('layouts.footer')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            document.getElementById('btn-setujui').addEventListener('click', function() {
                Swal.fire({
                    title: 'Konfirmasi',
                    text: "Apakah Anda yakin ingin menyetujui lomba ini?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, setujui',
                    cancelButtonText: 'Batal',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('form-setujui').submit();
                    }
                });
            });

            document.getElementById('btn-tolak').addEventListener('click', function() {
                Swal.fire({
                    title: 'Konfirmasi Penolakan',
                    text: "Tuliskan alasan penolakan lomba ini:",
                    input: 'textarea',
                    inputPlaceholder: 'Contoh: Lomba tidak relevan dengan bidang studi...',
                    inputAttributes: {
                        'aria-label': 'Catatan penolakan'
                    },
                    showCancelButton: true,
                    confirmButtonText: 'Tolak',
                    cancelButtonText: 'Batal',
                    inputValidator: (value) => {
                        if (!value) {
                            return 'Catatan penolakan wajib diisi!';
                        }
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Buat input hidden untuk kirim catatan
                        let form = document.getElementById('form-tolak');
                        let input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'catatan_penolakan';
                        input.value = result.value;
                        form.appendChild(input);
                        form.submit();
                    }
                });
            });
        </script>

        <!-- SweetAlert notifikasi sukses -->
        @if(session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: "{{ session('success') }}",
                timer: 2500,
                showConfirmButton: true,
            });
        </script>
        @endif

        @endsection