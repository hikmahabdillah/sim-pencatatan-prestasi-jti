@extends('layouts.template', ['page' => $breadcrumb->title])

@section('content')
    @include('layouts.navbar', ['title' => $breadcrumb->list])
    <div class="container-fluid py-4 h-100 flex-grow-1">
        <div class="row">
            <div class="mx-auto">
                <div class="card">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Detail Prestasi Mahasiswa</h4>
                        @if (auth()->user()->role_id == 3)
                            <div class="d-flex gap-2 align-items-center">
                                @if (!$prestasi->status_verifikasi)
                                    <button onclick="modalAction('/prestasi/{{ $prestasi->id_prestasi }}/edit-prestasi')"
                                        class="btn btn-sm btn-primary">
                                        <i class="fas fa-edit me-1"></i> Edit
                                    </button>
                                @endif
                                <button
                                    onclick="modalAction('/prestasi/{{ $prestasi->id_prestasi }}/confirm-delete-prestasi')"
                                    class="btn btn-sm btn-warning">
                                    <i class="fas fa-trash me-1"></i> Delete
                                </button>
                            </div>
                        @endif
                    </div>
                    <div class="card-body">
                        @if (!$prestasi)
                            <div class="alert alert-danger">
                                Data prestasi tidak ditemukan.
                            </div>
                        @else
                            <div class="row">
                                <!-- Main Information -->
                                <div class="col-md-8 position-relative">
                                    @if (auth()->user()->role_id == 1 || auth()->user()->role_id == 2)
                                        @if ($prestasi->tipe_prestasi === 'individu')
                                            <h5 class="text-dark mb-3">Mahasiswa: {{ $prestasi->anggota[0]->nama }}</h5>
                                        @endif
                                        @if ($prestasi->status_verifikasi === 1 && $prestasi->status_verifikasi_dospem === 1)
                                            <span class="badge bg-gradient-success position-absolute end-2 top-2">
                                                <i class="fas fa-check-circle me-1"></i> Terverifikasi (Admin & Dospem)
                                            </span>
                                        @elseif($prestasi->status_verifikasi === 1 && $prestasi->status_verifikasi_dospem === null)
                                            <span class="badge bg-gradient-primary position-absolute end-2 top-2">
                                                <i class="fas fa-check-circle me-1"></i> Terverifikasi Admin
                                            </span>
                                        @elseif($prestasi->status_verifikasi_dospem === 1 && $prestasi->status_verifikasi === null)
                                            <span class="badge bg-gradient-info position-absolute end-2 top-2">
                                                <i class="fas fa-check-circle me-1"></i> Terverifikasi Dospem
                                            </span>
                                        @elseif($prestasi->status_verifikasi === 0 || $prestasi->status_verifikasi_dospem === 0)
                                            <span class="badge bg-gradient-danger position-absolute end-2 top-2">
                                                <i class="fas fa-times-circle me-1"></i> Ditolak
                                                @if ($prestasi->status_verifikasi_dospem === 0)
                                                    (Dospem)
                                                @elseif($prestasi->status_verifikasi === 0)
                                                    (Admin)
                                                @endif
                                            </span>
                                        @else
                                            <span class="badge bg-gradient-secondary position-absolute end-2 top-2">
                                                <i class="fas fa-clock me-1"></i> Menunggu Verifikasi
                                            </span>
                                        @endif
                                    @endif

                                    @if (auth()->user()->role_id == 3)
                                        @if ($prestasi->status_verifikasi && $prestasi->status_verifikasi_dospem)
                                            <span class="badge bg-gradient-success position-absolute end-2 top-2">
                                                <i class="fas fa-check-circle me-1"></i> Terverifikasi (Admin & Dospem)
                                            </span>
                                        @elseif($prestasi->status_verifikasi_dospem === 1 && $prestasi->status_verifikasi === null)
                                            <span class="badge bg-gradient-info position-absolute end-2 top-2">
                                                <i class="fas fa-check-circle me-1"></i> Terverifikasi Dospem
                                            </span>
                                        @elseif($prestasi->status_verifikasi_dospem === 0 && $prestasi->status_verifikasi === null)
                                            <span class="badge bg-gradient-danger position-absolute end-2 top-2">
                                                <i class="fas fa-times-circle me-1"></i> Ditolak Dospem
                                            </span>
                                        @elseif ($prestasi->status_verifikasi === 1)
                                            <span class="badge bg-gradient-success position-absolute end-2 top-2">
                                                <i class="fas fa-check-circle me-1"></i> Terverifikasi Admin
                                            </span>
                                        @elseif($prestasi->status_verifikasi === 0)
                                            <span class="badge bg-gradient-danger position-absolute end-2 top-2">
                                                <i class="fas fa-times-circle me-1"></i> Ditolak Admin
                                            </span>
                                        @else
                                            <span class="badge bg-gradient-secondary position-absolute end-2 top-2">
                                                <i class="fas fa-clock me-1"></i> Menunggu Verifikasi
                                            </span>
                                        @endif
                                    @endif
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
                                        <p class="text-muted mb-1">
                                            <i class="fas fa-users me-2 text-success"></i>
                                            Tipe: {{ $prestasi->tipe_prestasi == 'tim' ? 'Tim' : 'Individu' }}
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
                                    <!-- Tambahkan bagian untuk menampilkan anggota tim -->
                                    @if ($prestasi->tipe_prestasi == 'tim' && $prestasi->anggota->count() > 0)
                                        @php
                                            // Pisahkan ketua dan anggota
                                            $ketua = $prestasi->anggota->first(function ($anggota) {
                                                return $anggota->pivot->peran === 'ketua';
                                            });

                                            $anggotaBiasa = $prestasi->anggota->filter(function ($anggota) {
                                                return $anggota->pivot->peran !== 'ketua';
                                            });
                                        @endphp

                                        <div class="table-responsive mt-3">
                                            <table class="table table-anggota">
                                                <thead>
                                                    <tr>
                                                        <th>Nama</th>
                                                        <th>Peran</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    {{-- Tampilkan ketua pertama --}}
                                                    @if ($ketua)
                                                        <tr class="anggota-ketua">
                                                            <td>
                                                                {{ $ketua->nama }}
                                                                <span class="badge badge-peran badge-ketua ms-2"> <i
                                                                        class="fas fa-crown text-white me-1"
                                                                        title="Ketua"></i></span>
                                                            </td>
                                                            <td>
                                                                <span class="badge badge-peran badge-ketua">
                                                                    {{ ucfirst($ketua->pivot->peran) }}
                                                                </span>
                                                            </td>
                                                        </tr>
                                                    @endif

                                                    {{-- Tampilkan anggota biasa --}}
                                                    @foreach ($anggotaBiasa as $anggota)
                                                        <tr class="anggota-biasa">
                                                            <td>{{ $anggota->nama }}</td>
                                                            <td>
                                                                <span class="badge badge-peran badge-anggota">
                                                                    {{ ucfirst($anggota->pivot->peran) }}
                                                                </span>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @endif

                                    <div class="border-top pt-3">
                                        <h6 class="text-uppercase text-sm">Deskripsi</h6>
                                        <p>{{ $prestasi->deskripsi ?? 'Tidak ada Deskripsi tambahan' }}</p>
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
                    <div class="card-footer bg-light pt-0 d-flex justify-content-between align-items-center">
                        <a href="{{ auth()->user()->role_id == 1 || auth()->user()->role_id == 2
                            ? url('/prestasi')
                            : url('/mahasiswa/' . auth()->user()->mahasiswa->id_mahasiswa . '/prestasi') }}"
                            class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Kembali
                        </a>
                        <div class="d-flex gap-2 align-items-center">
                            @if (auth()->user()->role_id == 1 && $prestasi->status_verifikasi_dospem === 1 && $prestasi->id_dospem != null)
                                <button onclick="modalAction('/prestasi/{{ $prestasi->id_prestasi }}/verifikasi-admin')"
                                    class="btn btn-md btn-primary">
                                    <i class="fas fa-check me-1"></i>
                                    {{ $prestasi->status_verifikasi != null ? 'Edit Verifikasi' : 'Verifikasi' }}
                                </button>
                            @elseif (auth()->user()->role_id == 1 && $prestasi->status_verifikasi_dospem === null && $prestasi->id_dospem == null)
                                <button onclick="modalAction('/prestasi/{{ $prestasi->id_prestasi }}/verifikasi-admin')"
                                    class="btn btn-md btn-primary">
                                    <i class="fas fa-check me-1"></i>
                                    {{ $prestasi->status_verifikasi != null ? 'Edit Verifikasi' : 'Verifikasi' }}
                                </button>
                            @elseif (auth()->user()->role_id == 1 && $prestasi->status_verifikasi_dospem === null && $prestasi->id_dospem != null)
                                <p class="text-dark">
                                    Menunggu verifikasi dari Dosen Pembimbing
                                </p>
                            @elseif (auth()->user()->role_id == 1 && $prestasi->status_verifikasi_dospem === 0)
                                <p class="text-dark">
                                    Verifikasi Dosen Pembimbing ditolak. Tidak bisa melakukan verifikasi
                                </p>
                            @endif
                            @if (auth()->user()->role_id == 2 && $prestasi->status_verifikasi === null)
                                <button onclick="modalAction('/prestasi/{{ $prestasi->id_prestasi }}/verifikasi-dospem')"
                                    class="btn btn-md btn-primary">
                                    <i class="fas fa-check me-1"></i>
                                    {{ $prestasi->status_verifikasi_dospem != null ? 'Edit Verifikasi' : 'Verifikasi' }}
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" data-backdrop="static"
        data-keyboard="false" data-width="75%" aria-hidden="true"></div>
    @include('layouts.footer')
@endsection
@push('css')
    <style>
        /* Tambahkan ini ke bagian CSS Anda */
        .table-anggota {
            border-collapse: separate;
            border-spacing: 0;
            border-radius: 8px;
            overflow: hidden;
        }

        .table-anggota thead th {
            background-color: #f8f9fa;
            color: #495057;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
        }

        .table-anggota tbody tr {
            transition: all 0.2s ease;
        }

        .table-anggota tbody tr:hover {
            background-color: rgba(0, 123, 255, 0.05);
        }

        /* Styling khusus untuk ketua tim */
        .anggota-ketua {
            background-color: #e3f2fd !important;
            position: relative;
        }

        .anggota-ketua td:first-child {
            border-left: 4px solid #2196F3;
        }

        /* Styling untuk anggota biasa */
        .anggota-biasa {
            background-color: #f8f9fa;
        }

        /* Badge untuk peran */
        .badge-peran {
            font-size: 0.75rem;
            padding: 4px 8px;
            border-radius: 4px;
            text-transform: capitalize;
        }

        .badge-ketua {
            background-color: #2196F3;
            color: white;
        }

        .badge-anggota {
            background-color: #6c757d;
            color: white;
        }
    </style>
@endpush
@push('js')
    <script>
        function modalAction(url = '') {
            $('#myModal').load(url, function() {
                $('#myModal').modal('show');
            });
        }
    </script>
@endpush
