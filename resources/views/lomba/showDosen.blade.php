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
                <a href="{{ url()->previous() }}" class="btn btn-secondary">
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
                    <div class="list-group" style="max-height: 300px; overflow-y: auto;">
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

{{-- SweetAlert + Search --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const searchInput = document.getElementById("searchMahasiswa");

        // Fungsi untuk inisialisasi pencarian
        function initSearch() {
            const mahasiswaItems = document.querySelectorAll(".mahasiswa-item");

            if (searchInput) {
                // Tampilkan semua item saat modal dibuka
                mahasiswaItems.forEach(item => {
                    item.style.display = "flex";
                });

                // Event pencarian
                searchInput.addEventListener("input", function() {
                    const keyword = this.value.toLowerCase().trim();

                    mahasiswaItems.forEach(item => {
                        const searchText = (item.dataset.search || '').toLowerCase().trim();
                        if (searchText.includes(keyword)) {
                            item.style.display = "flex";
                        } else {
                            item.style.display = "none";
                        }
                    });
                });
            }
        }

        // Jalankan pencarian saat modal ditampilkan
        const modalRekomendasi = document.getElementById('modalRekomendasi');
        if (modalRekomendasi) {
            modalRekomendasi.addEventListener('shown.bs.modal', function() {
                initSearch(); // Inisialisasi ulang tiap kali modal dibuka
            });
        }

        // Validasi form sebelum submit
        const form = document.getElementById('formRekomendasi');
        if (form) {
            form.addEventListener('submit', function(e) {
                const checkboxes = document.querySelectorAll('.mahasiswa-checkbox:checked:not([disabled])');

                if (checkboxes.length === 0) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'warning',
                        title: 'Peringatan!',
                        text: 'Pilih minimal satu mahasiswa untuk direkomendasikan.',
                        confirmButtonText: 'OK'
                    });
                    return false;
                }

                const submitBtn = document.getElementById('btnSubmitRekomendasi');
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Mengirim...';
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
        showConfirmButton: false
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
@endsection