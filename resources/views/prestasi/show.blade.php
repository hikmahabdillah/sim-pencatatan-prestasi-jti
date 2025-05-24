@extends('layouts.template', ['page' => $breadcrumb->title])

@section('content')
    @include('layouts.navbar', ['title' => $breadcrumb->list])
    <div class="container-fluid py-4 h-100 flex-grow-1">
        <div class="row">
            <div class="mx-auto">
                <div class="card">
                    <div class="card-header bg-light">
                        <h4 class="mb-0">Detail Prestasi Mahasiswa</h4>
                    </div>
                    <div class="card-body">
                        @if (!$prestasi)
                            <div class="alert alert-danger">
                                Data prestasi tidak ditemukan.
                            </div>
                        @else
                            <div class="row">
                                <!-- Main Information -->
                                <div class="col-md-8">
                                    <div class="mb-3">
                                        <h5 class="text-primary">{{ $prestasi->nama_prestasi }}</h5>
                                        <p class="text-muted mb-1">
                                            <i class="fas fa-trophy me-2 text-warning"></i>
                                            Juara: {{ $prestasi->juara }}
                                        </p>
                                        <p class="text-muted mb-1">
                                            <i class="fas fa-calendar me-2 text-info"></i>
                                            Tanggal:
                                            {{ \Carbon\Carbon::parse($prestasi->tanggal_prestasi)->format('d F Y') }}
                                        </p>
                                    </div>

                                    <div class="border-top pt-3">
                                        <h6 class="text-uppercase text-sm">Detail Prestasi</h6>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p class="mb-1">
                                                    <strong>Tingkat:</strong>
                                                    {{ $prestasi->tingkatPrestasi->nama_tingkat_prestasi ?? '-' }}
                                                </p>
                                                <p class="mb-1">
                                                    <strong>Kategori:</strong>
                                                    {{ $prestasi->kategori->nama_kategori ?? '-' }}
                                                </p>
                                            </div>
                                            <div class="col-md-6">
                                                <p class="mb-1">
                                                    <strong>Periode:</strong>
                                                    {{ $prestasi->periode->semester ?? '-' }} -
                                                    {{ $prestasi->periode->tahun_ajaran ?? '-' }}
                                                </p>
                                                <p class="mb-1">
                                                    <strong>Dosen Pembimbing:</strong>
                                                    {{ $prestasi->dosenPembimbing->nama ?? '-' }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="border-top pt-3">
                                        <h6 class="text-uppercase text-sm">Keterangan</h6>
                                        <p>{{ $prestasi->keterangan ?? 'Tidak ada keterangan tambahan' }}</p>
                                    </div>
                                </div>

                                <!-- Documents Section -->
                                <div class="col-md-4">
                                    <div class="card shadow-sm">
                                        <div class="card-header bg-light">
                                            <h6 class="mb-0">Dokumen Pendukung</h6>
                                        </div>
                                        <div class="card-body">
                                            @if ($prestasi->bukti_sertifikat)
                                                <div class="mb-3">
                                                    <h6 class="text-sm">Sertifikat</h6>
                                                    <a href="{{ asset('storage/' . $prestasi->bukti_sertifikat) }}"
                                                        target="_blank" class="btn btn-sm btn-outline-primary w-100">
                                                        <i class="fas fa-file-pdf me-1"></i> Lihat Sertifikat
                                                    </a>
                                                </div>
                                            @endif

                                            @if ($prestasi->foto_kegiatan)
                                                <div class="mb-3">
                                                    <h6 class="text-sm">Foto Kegiatan</h6>
                                                    <a href="{{ asset('storage/' . $prestasi->foto_kegiatan) }}"
                                                        target="_blank" class="btn btn-sm btn-outline-success w-100">
                                                        <i class="fas fa-image me-1"></i> Lihat Foto
                                                    </a>
                                                </div>
                                            @endif

                                            @if ($prestasi->surat_tugas)
                                                <div class="mb-3">
                                                    <h6 class="text-sm">Surat Tugas</h6>
                                                    <a href="{{ asset('storage/' . $prestasi->surat_tugas) }}"
                                                        target="_blank" class="btn btn-sm btn-outline-info w-100">
                                                        <i class="fas fa-file-alt me-1"></i> Lihat Surat
                                                    </a>
                                                </div>
                                            @endif

                                            @if ($prestasi->karya)
                                                <div class="mb-3">
                                                    <h6 class="text-sm">Karya</h6>
                                                    <a href="{{ asset('storage/' . $prestasi->karya) }}" target="_blank"
                                                        class="btn btn-sm btn-outline-warning w-100">
                                                        <i class="fas fa-file-archive me-1"></i> Lihat Karya
                                                    </a>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="card-footer bg-light pt-0">
                        <a href="{{ url()->previous() }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('layouts.footer')
@endsection

@push('css')
    <style>
        .card {
            border-radius: 0.5rem;
        }

        .card-header {
            border-bottom: 1px solid rgba(0, 0, 0, .125);
        }

        .text-uppercase {
            letter-spacing: 0.1em;
        }
    </style>
@endpush

@push('js')
    <script>
        // Add any necessary JavaScript here
        $(document).ready(function() {
            // Initialize tooltips if needed
            $('[data-bs-toggle="tooltip"]').tooltip();
        });
    </script>
@endpush
