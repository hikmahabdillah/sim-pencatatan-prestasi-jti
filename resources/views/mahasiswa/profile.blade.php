@extends('layouts.template', ['page' => $breadcrumb->title])

@section('content')
    @include('layouts.navbar', ['title' => $breadcrumb->list])
    <div class="container-fluid py-4 h-100 flex-grow-1">
        <h4>Profile Mahasiswa</h4>
        {{-- <p>{{ auth()->user() }}</p> --}}
        <form id="updateFotoForm" action="{{ url('/mahasiswa/' . $data->id_mahasiswa . '/update-foto') }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <input type="file" name="foto" id="fotoInput" accept="image/*" style="display: none;" onchange="updateFoto()">

            <div class="card card-plain shadow-lg rounded-3 p-3 mb-4" style="height: 180px;">
                <div class="row justify-content-start h-100 gap-3">
                    <div style="width: max-content">
                        <div class="blur-shadow-image h-100 position-relative overflow-hidden"
                            style="max-width: 150px; max-height: 150px; cursor: pointer; border-radius: 25px;"
                            onclick="document.getElementById('fotoInput').click()">
                            @php
                                $foto = auth()->user()->foto
                                    ? asset('storage/' . auth()->user()->foto)
                                    : asset('image/fotoDefault.jpg');
                            @endphp
                            <img id="fotoPreview" class="w-100 h-100 shadow-lg fotoPreview" style="object-fit: cover;"
                                src="{{ $foto }}">
                            <div class="position-absolute bottom-0 end-0 bg-white p-1 rounded-circle d-flex justify-content-center align-items-center"
                                style="width: 30px; height: 30px;">
                                <i class="fas fa-camera"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-7 ps-0 my-auto">
                        <div class="card-body text-left p-0">
                            <div class="p-md-0 pt-3 my-auto">
                                <h5 class="font-weight-bolder mb-0 text-uppercase">{{ $data->nama }}</h5>
                                <p class="text-uppercase text-sm font-weight-bold">{{ $data->prodi->nama_prodi }} -
                                    {{ $data->nim }}</p>
                                <div id="loadingIndicator" style="display: none;">
                                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                    Mengunggah...
                                </div>
                                <div id="successMessage" class="text-success" style="display: none;">
                                    <i class="fas fa-check-circle"></i> Foto berhasil diperbarui
                                </div>
                                <div id="errorMessage" class="text-danger" style="display: none;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <div class="card shadow rounded-3 border">
            <div class="card-body position-relative">
                <h5 class="fw-semibold mb-4 text-warning">Personal Information</h5>

                <!-- Tombol Edit -->
                <a href="{{ 'mahasiswa/' . auth()->user()->mahasiswa->id_mahasiswa . '/edit-profile' }}"
                    class="btn btn-warning position-absolute top-0 end-0 mt-3 me-3 btn-sm fs-6 font-weight-normal">
                    Edit
                </a>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <p class="mb-1 text-muted small font-weight-bold">Minat Bakat</p>
                        <p class="fw-semibold">{{ $data->kategori->nama_kategori }}</p>

                        <p class="mb-1 text-muted small font-weight-bold">Email Address</p>
                        <p class="fw-semibold">{{ $data->email }}</p>

                        <p class="mb-1 text-muted small font-weight-bold">Tanggal Lahir</p>
                        <p class="fw-semibold">{{ \Carbon\Carbon::parse($data->tanggal_lahir)->translatedFormat('d F Y') }}
                        </p>

                        <p class="mb-1 text-muted small font-weight-bold">Jenis Kelamin</p>
                        <p class="fw-semibold"> {{ $data->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</p>


                    </div>

                    <div class="col-md-6 mb-3">
                        <p class="mb-1 text-muted small font-weight-bold">Phone Number</p>
                        <p class="fw-semibold">{{ $data->no_hp }}</p>

                        <p class="mb-1 text-muted small font-weight-bold">Alamat</p>
                        <p class="fw-semibold text-wrap">{{ $data->alamat }}</p>

                        <p class="mb-1 text-muted small font-weight-bold">Angkatan</p>
                        <p class="fw-semibold">
                            {{ $data->angkatan }} - {{ $data->pengguna->status_aktif ? 'Aktif' : 'Non-Aktif' }}
                        </p>

                        {{-- Tambahkan data tambahan jika perlu di sisi kanan --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="myModal" class="modal fade animate shake" tabindex="-1" tingkat_prestasi="dialog" data-backdrop="static"
        data-keyboard="false" data-width="75%" aria-hidden="true"></div>
    @include('layouts.footer')
@endsection

@push('js')
    <script>
        $(document).ready(function() {
            // Preview photo when selected
            function previewFoto(input) {
                if (input.files && input.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        $('#fotoPreview').attr('src', e.target.result);
                    };
                    reader.readAsDataURL(input.files[0]);
                }
            }

            // Handle photo update
            function updateFoto() {
                const form = $('#updateFotoForm')[0];
                const formData = new FormData(form);

                // Show loading indicator
                $('#loadingIndicator').show();
                $('#successMessage').hide();
                $('#errorMessage').hide();

                // Clear previous errors
                $('.invalid-feedback').remove();
                $('.is-invalid').removeClass('is-invalid');

                $.ajax({
                    url: $(form).attr('action'),
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.status) {
                            // Show success message
                            $('#successMessage').show().delay(3000).fadeOut();

                            // Update photo URL in case it changed
                            if (response.foto_url) {
                                $('#fotoPreview').attr('src', response.foto_url);
                            }
                        } else {
                            // Handle validation errors
                            if (response.msgField) {
                                $.each(response.msgField, function(prefix, val) {
                                    $('#' + prefix).addClass('is-invalid');
                                    $('#' + prefix).after('<span class="invalid-feedback">' +
                                        val[0] + '</span>');
                                });
                            }

                            // Show error message
                            $('#errorMessage').text(response.message || 'Gagal memperbarui foto')
                                .show();

                            // Revert to old photo
                            $('#fotoPreview').attr('src',
                                '{{ auth()->user()->foto ? asset('storage/' . auth()->user()->foto) : asset('image/fotoDefault.jpg') }}'
                            );
                        }
                    },
                    error: function(xhr) {
                        let errorMessage = 'Terjadi kesalahan pada server';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        $('#errorMessage').text(errorMessage).show();

                        // Revert to old photo
                        $('#fotoPreview').attr('src',
                            '{{ auth()->user()->foto ? asset('storage/' . auth()->user()->foto) : asset('image/fotoDefault.jpg') }}'
                        );
                    },
                    complete: function() {
                        $('#loadingIndicator').hide();
                    }
                });
            }

            // Trigger update when file is selected
            $('#fotoInput').change(function() {
                previewFoto(this);
                updateFoto();
            });
        });
    </script>
@endpush
