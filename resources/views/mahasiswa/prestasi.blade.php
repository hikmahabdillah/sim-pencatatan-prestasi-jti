@extends('layouts.template', ['page' => $breadcrumb->title])

@section('content')
    @include('layouts.navbar', ['title' => $breadcrumb->list])
    <div class="container-fluid py-4 h-100 flex-grow-1">
        <div class="card shadow rounded-3 border">
            <div class="card-header bg-light pb-0 d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Daftar Prestasi</h4>
                <button onclick="modalAction('/prestasi/tambah-prestasi')" class="btn btn-sm btn-primary">
                    <i class="fas fa-add me-1"></i> Tambah Prestasi
                </button>
            </div>
            <div class="card-body">
                @if ($prestasi->isEmpty())
                    <div class="alert alert-info text-white">
                        Belum ada data prestasi.
                    </div>
                @else
                    <div class="row">
                        @foreach ($prestasi as $item)
                            <div class="w-100 mb-4" style="max-width: 400px">
                                <div class="card card-profile card-plain h-100">
                                    <div class="card-body bg-white shadow border-radius-lg p-3 d-flex flex-column">
                                        <!-- Status Verifikasi -->
                                        <div class="mb-2 text-end">
                                            @if (is_null($item->status_verifikasi))
                                                <span class="badge bg-warning text-white rounded-pill px-3 py-1">
                                                    <i class="fas fa-clock me-1"></i> Menunggu Verifikasi
                                                </span>
                                            @elseif($item->status_verifikasi)
                                                <span class="badge bg-success rounded-pill px-3 py-1">
                                                    <i class="fas fa-check-circle me-1"></i> Terverifikasi
                                                </span>
                                            @else
                                                <span class="badge bg-danger rounded-pill px-3 py-1">
                                                    <i class="fas fa-times-circle me-1"></i> Ditolak
                                                </span>
                                            @endif
                                        </div>

                                        <!-- PDF Preview -->
                                        @if ($item->bukti_sertifikat)
                                            <div class="pdf-preview-container border-radius-md mb-3 flex-grow-1"
                                                style="height: 180px; background-color: #f8f9fa; overflow: hidden; padding: 0;">
                                                <iframe
                                                    src="{{ asset('storage/' . $item->bukti_sertifikat) }}#toolbar=0&view=fit&scrollbar=0"
                                                    style="width: 100%; height: 100%; border: none; overflow: hidden;"
                                                    scrolling="no">
                                                </iframe>
                                            </div>
                                        @else
                                            <div class="pdf-preview-container border-radius-md mb-3 flex-grow-1"
                                                style="height: 180px; background-color: #f8f9fa; display: flex; align-items: center; justify-content: center;">
                                                <div class="text-center">
                                                    <i class="fas fa-file-alt fa-3x text-secondary"></i>
                                                    <p class="mt-2 mb-0 text-sm">Tidak ada dokumen</p>
                                                </div>
                                            </div>
                                        @endif

                                        <!-- Informasi Prestasi -->
                                        <div class="mt-auto">
                                            <div class="d-flex justify-content-between align-items-center mb-1">
                                                <span class="badge bg-primary bg-gradient">
                                                    {{ $item->kategori->nama_kategori }}
                                                </span>
                                                <small class="text-muted">
                                                    {{ $item->tingkatPrestasi->nama_tingkat_prestasi ?? '-' }}
                                                </small>
                                            </div>

                                            <h6 class="mb-1 text-dark">{{ $item->nama_prestasi }}</h6>
                                            <p class="mb-2 text-success fw-bold">
                                                <i class="fas fa-trophy me-1"></i> {{ $item->juara }}
                                            </p>
                                        </div>

                                        <!-- Tombol Detail -->
                                        <a class="btn btn-sm btn-outline-primary w-100 mt-2"
                                            href="/prestasi/{{ $item->id_prestasi }}/detail-prestasi">
                                            Lihat Detail
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
            transition: all 0.3s ease;
            border: 1px solid rgba(0, 0, 0, 0.1);
        }

        .card-profile:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            border-color: rgba(0, 0, 0, 0.2);
        }

        .pdf-preview-container {
            border: 1px solid #dee2e6;
            border-radius: 0.5rem;
        }

        .pdf-preview-container iframe::-webkit-scrollbar {
            display: none;
        }

        .pdf-preview-container iframe {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        .badge {
            font-size: 0.7rem;
            font-weight: 500;
            padding: 0.35em 0.65em;
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
