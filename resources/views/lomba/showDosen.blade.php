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

            {{-- Detail Fields --}}
            @php
            $fields = [
            'Nama Lomba' => $data->nama_lomba,
            'Tingkat Lomba' => $data->tingkatPrestasi->nama_tingkat_prestasi ?? '-',
            'Kategori Lomba' => $data->kategoris->pluck('nama_kategori')->implode(', '),
            'Penyelenggara' => $data->penyelenggara ?? '-',
            'Deskripsi' => $data->deskripsi,
            'Biaya Pendaftaran' => $data->biaya_pendaftaran == 1 ? 'Berbayar' : ($data->biaya_pendaftaran == 0 ? 'Tidak Berbayar' : '-'),
            'Tanggal Mulai Lomba' => \Carbon\Carbon::parse($data->tanggal_mulai)->translatedFormat('d F Y'),
            'Tanggal Selesai Lomba' => \Carbon\Carbon::parse($data->tanggal_selesai)->translatedFormat('d F Y'),
            'Deadline Pendaftaran' => \Carbon\Carbon::parse($data->deadline_pendaftaran)->translatedFormat('d F Y'),
            ];
            @endphp

            @foreach ($fields as $label => $value)
            <div class="mb-2 d-flex align-items-center gap-4">
                <p class="mb-0 text-muted small fw-bold" style="min-width: 150px;">{{ $label }}</p>
                <p class="mb-0 fw-semibold">: {!! $value !!}</p>
            </div>
            @endforeach

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

            {{-- Tombol Aksi --}}
            <div class="card-footer d-flex justify-content-between align-items-center bg-transparent">
                <a href="{{ url('lomba/indexDosen') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Kembali
                </a>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalRekomendasi">
                    <i class="fas fa-share-alt me-2"></i>Rekomendasikan
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Modal Rekomendasi --}}
<div class="modal fade" id="modalRekomendasi" tabindex="-1" aria-labelledby="modalRekomendasiLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <form action="{{ route('rekomendasi.byDosen') }}" method="POST" id="formRekomendasi">
                @csrf
                <input type="hidden" name="id_lomba" value="{{ $data->id_lomba }}">

                <div class="modal-header">
                    <h5 class="modal-title" id="modalRekomendasiLabel">Rekomendasikan ke Mahasiswa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>

                <div class="modal-body">
                    <input type="text" class="form-control mb-3" id="searchMahasiswa" placeholder="Cari nama mahasiswa...">
                    <div class="list-group" id="mahasiswaContainer" style="max-height: 300px; overflow-y: auto;">
                        @foreach ($mahasiswaList as $mhs)
                        @php
                        $sudahRekom = \DB::table('rekomendasi_lomba')
                        ->where('id_lomba', $data->id_lomba)
                        ->where('id_mahasiswa', $mhs->id_mahasiswa)
                        ->exists();
                        @endphp
                        <label class="list-group-item d-flex justify-content-between align-items-center mahasiswa-item"
                            data-search="{{ strtolower(trim(($mhs->nama ?? '') . ' ' . ($mhs->nim ?? ''))) }}">
                            <div>
                                <input type="checkbox" name="id_mahasiswa[]" value="{{ $mhs->id_mahasiswa }}"
                                    class="mahasiswa-checkbox" {{ $sudahRekom ? 'disabled' : '' }}>
                                <span class="ms-2">{{ $mhs->nama }} ({{ $mhs->nim }})</span>
                            </div>
                            @if ($sudahRekom)
                            <span class="badge bg-success">Sudah direkomendasikan</span>
                            @endif
                        </label>
                        @endforeach
                    </div>
                </div>

                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary" id="btnSubmitRekomendasi">
                        <i class="fas fa-paper-plane me-2"></i>Kirim Rekomendasi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@include('layouts.footer')
@endsection

@push('css')
<style>
    /* CSS untuk Search Mahasiswa */
    .mahasiswa-item.d-none {
        display: none !important;
    }

    .mahasiswa-item[style*="display: none"] {
        display: none !important;
    }

    .list-group-item {
        transition: all 0.2s ease;
    }

    .no-result-message {
        padding: 2rem 1rem;
        text-align: center;
        color: #6c757d;
        background-color: #f8f9fa;
        border-radius: 0.375rem;
        margin: 0.5rem 0;
    }

    .no-result-message i {
        font-size: 2rem;
        margin-bottom: 0.5rem;
        opacity: 0.5;
    }

    /* Highlight search results */
    .mahasiswa-item.highlight {
        background-color: #fff3cd;
        border-color: #ffeaa7;
    }

    /* Search input focus */
    #searchMahasiswa:focus {
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }
</style>
@endpush

@push('js')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const searchInput = document.getElementById("searchMahasiswa");
        const modalRekomendasi = document.getElementById('modalRekomendasi');
        const mahasiswaContainer = document.getElementById('mahasiswaContainer');
        let mahasiswaItems = [];

        // Fungsi untuk filter mahasiswa
        function filterMahasiswaList() {
            const keyword = searchInput.value.toLowerCase().trim();
            console.log('Searching for:', keyword);

            let visibleCount = 0;

            mahasiswaItems.forEach((item, index) => {
                const searchText = item.getAttribute('data-search') || '';
                const isMatch = keyword === '' || searchText.includes(keyword);

                if (isMatch) {
                    // Tampilkan item
                    item.classList.remove('d-none');
                    item.style.removeProperty('display');
                    item.style.display = 'flex';
                    visibleCount++;
                    console.log(`Item ${index + 1} (${searchText}) - SHOWN`);
                } else {
                    // Sembunyikan item
                    item.classList.add('d-none');
                    item.style.display = 'none';
                    console.log(`Item ${index + 1} (${searchText}) - HIDDEN`);
                }
            });

            console.log(`Total visible items: ${visibleCount}/${mahasiswaItems.length}`);

            // Tambahkan pesan jika tidak ada hasil
            let noResultMsg = mahasiswaContainer.querySelector('.no-result-message');

            if (visibleCount === 0 && keyword !== '') {
                if (!noResultMsg) {
                    noResultMsg = document.createElement('div');
                    noResultMsg.className = 'no-result-message';
                    noResultMsg.innerHTML = '<i class="fas fa-search"></i><br>Tidak ada mahasiswa yang cocok dengan pencarian "<strong>' + keyword + '</strong>"';
                    mahasiswaContainer.appendChild(noResultMsg);
                } else {
                    noResultMsg.innerHTML = '<i class="fas fa-search"></i><br>Tidak ada mahasiswa yang cocok dengan pencarian "<strong>' + keyword + '</strong>"';
                }
                noResultMsg.style.display = 'block';
            } else {
                if (noResultMsg) {
                    noResultMsg.style.display = 'none';
                }
            }
        }

        // Event listener untuk input search
        if (searchInput) {
            searchInput.addEventListener("input", function() {
                console.log('Input changed:', this.value);
                filterMahasiswaList();
            });

            // Clear search dengan Escape key
            searchInput.addEventListener("keydown", function(e) {
                if (e.key === 'Escape') {
                    this.value = '';
                    filterMahasiswaList();
                }
            });
        }

        // Inisialisasi saat modal dibuka
        if (modalRekomendasi) {
            modalRekomendasi.addEventListener('shown.bs.modal', function() {
                console.log('Modal opened');

                // Ambil semua item mahasiswa
                mahasiswaItems = Array.from(document.querySelectorAll(".mahasiswa-item"));
                console.log('Found items:', mahasiswaItems.length);

                // Reset search input
                if (searchInput) {
                    searchInput.value = '';
                    searchInput.focus(); // Auto focus ke search input
                }

                // Tampilkan semua item awalnya
                mahasiswaItems.forEach(item => {
                    item.classList.remove('d-none');
                    item.style.removeProperty('display');
                    item.style.display = 'flex';
                });

                // Hide no result message if exists
                const noResultMsg = mahasiswaContainer.querySelector('.no-result-message');
                if (noResultMsg) {
                    noResultMsg.style.display = 'none';
                }
            });

            // Reset saat modal ditutup
            modalRekomendasi.addEventListener('hidden.bs.modal', function() {
                if (searchInput) {
                    searchInput.value = '';
                }
            });
        }

        // Validasi sebelum submit
        const form = document.getElementById('formRekomendasi');
        if (form) {
            form.addEventListener('submit', function(e) {
                const checked = document.querySelectorAll('.mahasiswa-checkbox:checked:not([disabled])');
                if (checked.length === 0) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'warning',
                        title: 'Peringatan!',
                        text: 'Pilih minimal satu mahasiswa untuk direkomendasikan.',
                        confirmButtonText: 'OK'
                    });
                } else {
                    const submitBtn = document.getElementById('btnSubmitRekomendasi');
                    if (submitBtn) {
                        submitBtn.disabled = true;
                        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Mengirim...';
                    }
                }
            });
        }
    });
</script>

@if(session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: "{{ session('success') }}",
        timer: 3000,
        showConfirmButton: true
    });
</script>
@endif

@if(session('error'))
<script>
    Swal.fire({
        icon: 'error',
        title: 'Gagal!',
        text: "{{ session('error') }}",
        confirmButtonText: 'OK'
    });
</script>
@endif

@if(session('info'))
<script>
    Swal.fire({
        icon: 'info',
        title: 'Informasi!',
        text: "{{ session('info') }}",
        confirmButtonText: 'OK'
    });
</script>
@endif
@endpush