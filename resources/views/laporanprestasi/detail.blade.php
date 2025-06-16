@extends('layouts.template')

@section('content')
    @include('layouts.navbar', ['title' => $breadcrumb->title])
    <div class="container-fluid py-4">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <a href="{{ $isMahasiswa ? route('laporan-prestasi.index') : route('laporan-prestasi.mahasiswa') }}"
                        class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i> Kembali
                    </a>
                    <h5 class="mb-0">Detail Prestasi Mahasiswa</h5>
                    <div>
                        @if (!$isMahasiswa)
                            <div class="btn-group" role="group">
                                <button id="export_pdf" class="btn btn-danger">
                                    <i class="fas fa-file-pdf me-2"></i> Export PDF
                                </button>
                                <button id="export_excel" class="btn btn-success">
                                    <i class="fas fa-file-excel me-2"></i> Export Excel
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <table cellpadding="5">
                            <tr>
                                <th width="30%">NIM</th>
                                <td>:</td>
                                <td>{{ $mahasiswa->nim }}</td>
                            </tr>
                            <tr>
                                <th>Nama Mahasiswa</th>
                                <td>:</td>
                                <td>{{ $mahasiswa->nama }}</td>
                            </tr>
                            <tr>
                                <th>Program Studi</th>
                                <td>:</td>
                                <td>{{ $mahasiswa->prodi->nama_prodi ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Angkatan</th>
                                <td>:</td>
                                <td>{{ $mahasiswa->angkatan }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <h5 class="mb-3">Daftar Prestasi</h5>
                @if ($mahasiswa->prestasi->isEmpty())
                    <div class="alert alert-info">
                        Tidak ada data prestasi untuk mahasiswa ini.
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover table-striped">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center">No</th>
                                    <th>Nama Prestasi</th>
                                    <th>Juara</th>
                                    <th>Kategori</th>
                                    <th>Tingkat</th>
                                    <th>Periode Lomba</th>
                                    <th>Dosen Pembimbing</th>
                                    <th class="text-center">Sertifikat</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($mahasiswa->prestasi as $prestasi)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>{{ $prestasi->nama_prestasi }}</td>
                                        <td>{{ $prestasi->juara }}</td>
                                        <td>{{ $prestasi->kategori->nama_kategori ?? 'N/A' }}</td>
                                        <td>{{ $prestasi->tingkatPrestasi->nama_tingkat_prestasi ?? 'N/A' }}</td>
                                        <td>
                                            @if ($prestasi->periode)
                                                {{ $prestasi->periode->semester }} -
                                                {{ $prestasi->periode->tahun_ajaran }}
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td>{{ $prestasi->dosenPembimbing->nama ?? 'N/A' }}</td>
                                        <td class="text-center">
                                            @if ($prestasi->bukti_sertifikat)
                                                <a href="{{ asset('storage/' . $prestasi->bukti_sertifikat) }}"
                                                    target="_blank" class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
        @include('layouts.footer')
    </div>
@endsection

@push('css')
    <style>
        .table th {
            white-space: nowrap;
        }

        .table td {
            vertical-align: middle;
        }

        .card-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid #eee;
        }
    </style>
@endpush

@push('js')
    <script>
        $(document).ready(function() {
            @if (!$isMahasiswa)
                // Handler untuk tombol export
                $('#export_pdf').click(function() {
                    window.location.href =
                        "{{ route('laporan-prestasi.export-mahasiswa', $mahasiswa->id_mahasiswa) }}?format=pdf";
                });

                $('#export_excel').click(function() {
                    window.location.href =
                        "{{ route('laporan-prestasi.export-mahasiswa', $mahasiswa->id_mahasiswa) }}?format=excel";
                });
            @endif
        });
    </script>
@endpush
