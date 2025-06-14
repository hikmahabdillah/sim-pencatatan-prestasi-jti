@extends('layouts.template')

@section('content')
@include('layouts.navbar')

<div class="container-fluid py-4">
    {{-- Alert jika kosong --}}
    @if($empty)
    <div class="alert alert-info text-center">
        {{ $message }}
    </div>
    @else

    <div class="card shadow p-3">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h4 class="mb-0">
                    <i class="fas fa-users me-2"></i>
                    Mahasiswa Rekomendasi - {{ $lomba->nama_lomba }}
                </h4>
                <small class="text-muted">
                    Tingkat: {{ $lomba->tingkatPrestasi->nama_tingkat_prestasi }}
                </small>
            </div>

            @if($dospemList->isEmpty())
            <div class="alert alert-warning text-white bg-warning mb-0 ms-auto">
                <i class="fas fa-exclamation-circle me-1"></i>
                Belum ada dosen pembimbing dengan minat yang cocok dengan kategori lomba ini.
            </div>
            @else
            {{-- Form Pilih Dospem (pakai AJAX) --}}
            <form id="formDospem" class="d-flex align-items-center ms-auto">
                @csrf
                <input type="hidden" name="id_lomba" value="{{ $lomba->id_lomba }}">
                <input type="hidden" name="id_pengusul" value="{{ auth()->user()->id_pengguna }}">
                <label class="me-2 mb-0">Dosen Pembimbing:</label>
                <select name="id_dospem" class="form-select form-select-sm me-2" style="width: 270px;" required>
                    <option value="">Pilih Rekomendasi Dosen Pembimbing</option>
                    @foreach($dospemList as $dospem)
                    <option value="{{ $dospem->id_pengguna }}"
                        {{ $dospemTerpilih == $dospem->id_pengguna ? 'selected' : '' }}>
                        {{ $dospem->dosen->nama ?? '-' }}
                    </option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-success btn-sm">Simpan</button>
            </form>
            @endif
        </div>

        {{-- Card Mahasiswa --}}
        <div class="row">
            @foreach($rekomendasi as $item)
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm" style="cursor: pointer;" onclick="showDetail('{{ $item->id_rekomendasi }}')">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">{{ $item->mahasiswa->nama }}</h5>
                        <p class="mb-1 text-sm text-muted">NIM: {{ $item->mahasiswa->nim }}</p>
                        <p class="mb-1 text-sm text-muted">
                            Program Studi: {{ $item->mahasiswa->prodi->nama_prodi ?? 'Tidak diketahui' }}
                        </p>
                        <button type="button" onclick="event.stopPropagation(); modalAction('{{ url('mahasiswa/' . $item->mahasiswa->id_mahasiswa . '/show') }}')" class="btn btn-info btn-sm mt-auto">
                            Detail
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    <div class="mt-3">
        <a href="{{ route('lomba.manajemen') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left me-1"></i> Kembali
        </a>
    </div>
    @endif
</div>

{{-- Modal Detail Mahasiswa --}}
<div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog"
    data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true"></div>

@include('layouts.footer')
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Show modal detail mahasiswa
    function modalAction(url = '') {
        $.ajax({
            url: url,
            type: 'GET',
            success: function(response) {
                $('#myModal').html(response);
                $('#myModal').modal('show');
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: 'Tidak dapat memuat detail mahasiswa.'
                });
            }
        });
    }

    // Submit form dospem pakai AJAX + SweetAlert di bawah
    $('#formDospem').on('submit', function(e) {
        e.preventDefault();

        let formData = $(this).serialize();

        $.ajax({
            url: "{{ route('rekomendasi.simpanDospem') }}",
            method: 'POST',
            data: formData,
            success: function(response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'Dosen pembimbing berhasil disimpan.',
                    confirmButtonColor: '#3085d6',
                });
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: xhr.responseJSON?.message ?? 'Terjadi kesalahan saat menyimpan.',
                    confirmButtonColor: '#d33',
                });
            }
        });
    });
</script>
@endpush