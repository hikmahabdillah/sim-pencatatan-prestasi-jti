@extends('layouts.template', ['page' => $breadcrumb->title])

@section('content')
@include('layouts.navbar', ['title' => $breadcrumb->list])

<div class="container-fluid py-4 h-100 flex-grow-1">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <button onclick="modalAction('{{ url('lomba/create') }}')" class="btn bg-gradient-info">
            Tambah Data Lomba
        </button>

        <input type="text" id="searchKeyword" class="form-control" style="max-width: 300px;" placeholder="Cari nama lomba...">
    </div>

    <div class="card p-3">
        <div class="row" id="lomba-card-list">
            {{-- Data akan di-load via AJAX --}}
        </div>
    </div>

    <div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" data-backdrop="static"
        data-keyboard="false" data-width="75%" aria-hidden="true"></div>

    @include('layouts.footer')
</div>
@endsection

@push('js')
<script>
    function modalAction(url = '') {
        $('#myModal').load(url, function() {
            $('#myModal').modal('show');
        });
    }

    $(document).ready(function() {
        fetchLomba();

        // Ketika user mengetik di search input
        $('#searchKeyword').on('keyup', function() {
            fetchLomba();
        });
    });

    function fetchLomba() {
        let keyword = $('#searchKeyword').val();

        $.ajax({
            url: "{{ url('lomba/listSaya') }}",
            method: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                keyword: keyword, // kirim keyword untuk filter search
            },
            success: function(res) {
                let html = '';

                if (res.data.length === 0) {
                    html = '<p class="text-center text-muted">Tidak ada data lomba.</p>';
                } else {
                    res.data.forEach(item => {
                        html += `
                        <div class="col-md-4 mb-4">
                            <div class="card shadow h-100 d-flex flex-column">
                                <!-- Foto -->
                                <div class="d-flex justify-content-center align-items-center" style="height: 200px; width: 150px; margin: auto; border-radius: 25px; overflow: hidden;">
                                    <img src="${item.foto ? '/storage/' + item.foto : '/image/fotoDefault.jpg'}" 
                                        alt="Poster Lomba" class="w-100 h-100 shadow-lg" style="object-fit: cover; border-radius: 25px;">
                                </div>
                                <!-- Konten Card -->
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title">${item.nama_lomba}</h5>
                                     <h7 class="card-title">${item.nama_kategori}</h7>
                                    <p class="card-text text-sm mb-2">
                                        ${item.deskripsi.length > 100 ? item.deskripsi.substring(0, 100) + '...' : item.deskripsi}
                                    </p>
                                    <p class="card-text text-sm mb-2">
                                        <a href="${item.link_pendaftaran}" target="_blank">${item.link_pendaftaran}</a>
                                    </p>
                                    <!-- Tombol aksi selalu di bawah -->
                                    <div class="mt-auto d-flex justify-content-end gap-2">
                                        ${item.aksi}
                                    </div>
                                </div>
                            </div>
                        </div>
                        `;
                    });
                }

                $('#lomba-card-list').html(html);
            },
            error: function() {
                $('#lomba-card-list').html('<p class="text-danger">Gagal memuat data.</p>');
            }
        });
    }
</script>
@endpush