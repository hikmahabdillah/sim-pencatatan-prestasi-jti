@extends('layouts.template')

@section('content')
    @include('layouts.navbar', ['title' => $breadcrumb->title])
    <div class="container-fluid py-4">
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="mb-0">Prestasi Saya</h5>
            </div>
            <div class="card-body">
                <!-- Info Mahasiswa -->
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

                <!-- Daftar Prestasi -->
                <h5 class="mb-3">Daftar Prestasi</h5>
                @if ($mahasiswa->prestasi->isEmpty())
                    <div class="alert alert-info">
                        Anda belum memiliki prestasi yang tercatat.
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover table-striped">
                            <thead class="table-light">
                                <tr>
                                    <th width="5%">No</th>
                                    <th width="25%">Nama Prestasi</th>
                                    <th width="10%">Juara</th>
                                    <th width="15%">Kategori</th>
                                    <th width="10%">Tingkat</th>
                                    <th width="15%">Periode</th>
                                    <th width="15%">Dosen Pembimbing</th>
                                    <th width="15%">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($mahasiswa->prestasi as $index => $prestasi)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $prestasi->nama_prestasi }}</td>
                                        <td>{{ $prestasi->juara }}</td>
                                        <td>{{ $prestasi->kategori->nama_kategori ?? 'N/A' }}</td>
                                        <td>{{ $prestasi->tingkatPrestasi->nama_tingkat_prestasi ?? 'N/A' }}</td>
                                        <td>
                                            @if ($prestasi->periode)
                                                {{ $prestasi->periode->semester }} - {{ $prestasi->periode->tahun_ajaran }}
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td>{{ $prestasi->dosenPembimbing->nama ?? 'N/A' }}</td>
                                        <td>
                                            @if ($prestasi->status_verifikasi === 1)
                                                <span class="badge bg-success">Terverifikasi</span>
                                            @elseif($prestasi->status_verifikasi === 0)
                                                <span class="badge bg-danger">Ditolak</span>
                                            @else
                                                <span class="badge bg-warning">Menunggu</span>
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
    </div>
@endsection

@push('css')
    <style>
        .badge {
            font-size: 0.85em;
            padding: 0.35em 0.65em;
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(0, 0, 0, 0.02);
        }

        .card-header {
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
        }
    </style>
@endpush
