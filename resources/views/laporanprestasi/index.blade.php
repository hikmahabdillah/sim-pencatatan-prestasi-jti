@extends('layouts.template')

@section('content')
@include('layouts.navbar', ['title' => $breadcrumb->list])
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-0">
                <div class="card-header pb-0">
                </div>
                <div class="card-body px-4 pt-0 pb-2">
                    <div class="row">
                        <!-- Card A.a - Laporan Berdasarkan Mahasiswa -->
                        <div class="col-md-6 mb-4">
                            <div class="card h-100">
                                <div class="card-header bg-primary">
                                    <h5 class="mb-0">A. Laporan Berdasarkan Mahasiswa</h5>
                                </div>
                                <div class="card-body d-flex flex-column">
                                    <p class="card-text">Lihat daftar mahasiswa dan prestasi mereka berdasarkan filter tertentu</p>
                                    <div class="mt-auto">
                                        <a href="{{ route('laporan-prestasi.mahasiswa') }}" class="btn btn-primary">
                                            <i class="fas fa-user-graduate me-2"></i> Pilih Mahasiswa
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Card A.b - Laporan Berdasarkan Periode -->
                        <div class="col-md-6 mb-4">
                            <div class="card h-100">
                                <div class="card-header bg-success text-dark">
                                    <h5 class="mb-0">B. Laporan Berdasarkan Periode</h5>
                                </div>
                                <div class="card-body d-flex flex-column">
                                    <p class="card-text">Lihat prestasi mahasiswa berdasarkan periode tertentu</p>
                                    <div class="mt-auto">
                                        <a href="{{ route('laporan-prestasi.periode') }}" class="btn btn-success">
                                            <i class="fas fa-calendar-alt me-2"></i> Pilih Periode
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('layouts.footer')
</div>
@endsection

@push('css')
<style>
    .card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border: none;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .card-header {
        border-bottom: none;
        padding: 1rem 1.5rem;
    }

    .card-body {
        padding: 1.5rem;
    }

    .card-header.bg-primary h5 {
        color: white;
    }

    .card-header.bg-success h5 {
        color: white;
    }
</style>
@endpush