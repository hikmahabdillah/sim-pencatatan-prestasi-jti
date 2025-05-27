@extends('layouts.template', ['page' => $breadcrumb->title])

@section('content')
@include('layouts.navbar', ['title' => $breadcrumb->list])

<div class="container-fluid py-4 h-100 flex-grow-1">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <button onclick="modalAction('{{ url('lomba/create') }}')" class="btn bg-gradient-info mt-1">
            Tambah Data Lomba
        </button>

        <div class="d-flex gap-2">
            <select id="filterKategori" class="form-control">
                <option value="">Semua Kategori</option>
                @foreach ($kategori as $k)
                <option value="{{ $k->id_kategori }}">{{ $k->nama_kategori }}</option>
                @endforeach
            </select>

            <div class="input-group" style="width: 300px;">
                <input type="text" id="searchKeyword" class="form-control" placeholder="Cari nama lomba...">
                <span class="input-group-text bg-gradient-info text-white">
                    <i class="fas fa-search"></i>
                </span>
            </div>
        </div>
    </div>

    <!-- Card Lomba -->
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

        // Trigger fetch on typing search and on category change
        $('#searchKeyword').on('keyup', function() {
            fetchLomba();
        });

        $('#filterKategori').on('change', function() {
            fetchLomba();
        });
    });

    function fetchLomba() {
        let keyword = $('#searchKeyword').val();
        let kategori = $('#filterKategori').val();

        $.ajax({
            url: "{{ url('lomba/listSemua') }}",
            method: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                keyword: keyword,
                kategori: kategori
            },
            success: function(res) {
                let html = '';

                if (res.data.length === 0) {
                    html = '<p class="text-muted">Tidak ada data lomba ditemukan.</p>';
                } else {
                    res.data.forEach(item => {
                        html += `
                        <div class="col-md-4 mb-4">
                            <div class="card shadow h-100 d-flex flex-column">
                                <div class="d-flex justify-content-center align-items-center" style="height: 200px; width: 150px; margin: auto; border-radius: 25px; overflow: hidden;">
                                    <img src="${item.foto ? '/storage/' + item.foto : '/image/fotoDefault.jpg'}" 
                                        alt="Poster Lomba" class="w-100 h-100 shadow-lg" style="object-fit: cover; border-radius: 25px;">
                                </div>
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title">${item.nama_lomba}</h5>
                                    <h7 class="card-title">${item.nama_kategori}</h7>
                                    <p class="card-text text-sm mb-2">
                                        ${item.deskripsi.length > 100 ? item.deskripsi.substring(0, 100) + '...' : item.deskripsi}
                                    </p>
                                    <p class="card-text text-sm mb-2">
                                        <a href="${item.link_pendaftaran}" target="_blank">${item.link_pendaftaran}</a>
                                    </p>
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