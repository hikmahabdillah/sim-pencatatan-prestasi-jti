@extends('layouts.template', ['page' => $breadcrumb->title])

@section('content')
    @include('layouts.navbar', ['title' => $breadcrumb->list])
    <div class="container-fluid py-4 h-100 flex-grow-1">
        <div class="card shadow rounded-3 border">
            <div class="card-header bg-light pb-0">
                <h4 class="mb-0">Daftar Prestasi</h4>
            </div>
            <div class="card-body">
                @if ($prestasi->isEmpty())
                    <div class="alert alert-info">
                        Belum ada data prestasi.
                    </div>
                @else
                    <div class="row">
                        @foreach ($prestasi as $item)
                            <div class="col-md-6 col-lg-4 mb-4">
                                <div class="card card-profile card-plain">
                                    <div class="card-body bg-white shadow border-radius-lg p-3">
                                        <!-- PDF Preview Image -->
                                        @if ($item->bukti_sertifikat)
                                            <!-- Show PDF preview using PDF.js -->
                                            <div class="pdf-preview-container border-radius-md mb-2"
                                                style="height: 200px; background-color: #f8f9fa; overflow: hidden;">
                                                <iframe
                                                    src="{{ asset('storage/' . $item->bukti_sertifikat) }}#toolbar=0&view=fit"
                                                    style="width: 100%; height: 100%; border: none;"></iframe>
                                            </div>
                                        @else
                                            <!-- Default image when no document exists -->
                                            <div class="pdf-preview-container border-radius-md mb-2"
                                                style="height: 200px; background-color: #f8f9fa; display: flex; align-items: center; justify-content: center;">
                                                <div class="text-center">
                                                    <i class="fas fa-file-alt fa-3x text-secondary"></i>
                                                    <p class="mt-2 mb-0 text-sm">Tidak ada dokumen</p>
                                                </div>
                                            </div>
                                        @endif

                                        <div class="d-flex justify-content-between align-items-center">
                                            <p class="mb-1 text-xs font-weight-bold">
                                                {{ $item->tingkatPrestasi->nama_tingkat_prestasi ?? '-' }}</p>
                                            <p class="mb-1 text-xs font-weight-bold text-primary">
                                                {{ $item->kategori->nama_kategori }}
                                            </p>
                                        </div>
                                        <h5 class="mt-2 mb-0">{{ $item->nama_prestasi }}</h5>
                                        <p class="mb-2 text-sm font-weight-bolder text-warning text-gradient">
                                            {{ $item->juara }}
                                        </p>

                                        <a class="btn btn-sm btn-info mt-2 w-100 mb-0"
                                            href="/mahasiswa/{{ $item->id_prestasi }}/detail-prestasi">
                                            Detail
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Existing Modal -->
    <div id="myModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">

    </div>

    @include('layouts.footer')
@endsection

@push('css')
    <style>
        .card-profile {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card-profile:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .pdf-preview-container {
            border: 1px solid #eee;
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
