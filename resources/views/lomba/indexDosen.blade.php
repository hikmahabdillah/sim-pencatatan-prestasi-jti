@extends('layouts.template', ['page' => 'Lomba'])

@section('content')
@include('layouts.navbar', ['title' => 'Lomba'])

<div class="container-fluid py-4">
    <ul class="nav nav-tabs mb-3" id="lombaTabs" role="tablist">
        <li class="nav-item">
            <button class="nav-link active" id="semua-tab" data-bs-toggle="tab" data-bs-target="#semua" type="button" role="tab">Semua Lomba</button>
        </li>
        <li class="nav-item">
            <button class="nav-link" id="rekom-tab" data-bs-toggle="tab" data-bs-target="#rekom" type="button" role="tab">Rekomendasi Saya</button>
        </li>
    </ul>

    <!-- Filter -->
    <div class="d-flex justify-content-end gap-2 mb-3">
        <select id="filterKategori" class="form-control" style="max-width: 200px;">
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

    <!-- Tab Content -->
    <div class="tab-content">
        <div class="tab-pane fade show active" id="semua" role="tabpanel">
            <div class="row" id="lomba-card-semua"></div>
        </div>
        <div class="tab-pane fade" id="rekom" role="tabpanel">
            <div class="row" id="lomba-card-rekom"></div>
        </div>
    </div>

    @include('layouts.footer')
</div>
@endsection

@push('js')
<script>
    $(document).ready(function() {
        loadLomba();

        $('#searchKeyword, #filterKategori').on('input change', function() {
            loadLomba();
        });
    });

    function loadLomba() {
        let keyword = $('#searchKeyword').val();
        let kategori = $('#filterKategori').val();

        $.ajax({
            url: "{{ url('lomba/listLomba') }}",
            method: 'POST',
            data: {
                _token: "{{ csrf_token() }}",
                keyword: keyword,
                kategori: kategori,
            },
            success: function(res) {
                let html = '';

                if (!res.data || res.data.length === 0) {
                    html = '<p class="text-muted">Tidak ada lomba ditemukan.</p>';
                } else {
                    res.data.forEach(item => {
                        html += `
                            <div class="col-12 col-md-6 col-lg-4 mb-6 d-flex">
                                <div class="card shadow w-100 d-flex flex-column position-relative">
                                    <div class="d-flex justify-content-center align-items-center" 
                                        style="height: 200px; width: 150px; margin: auto; border-radius: 25px; overflow: hidden;">
                                        <a href="${item.foto ? '/storage/' + item.foto : '/image/fotoDefault.jpg'}" 
                                            target="_blank" rel="noopener noreferrer">
                                            <img src="${item.foto ? '/storage/' + item.foto : '/image/fotoDefault.jpg'}" 
                                                alt="Poster Lomba" class="w-100 h-100 shadow-lg" style="object-fit: cover; border-radius: 25px;">
                                        </a>
                                    </div>
                                    <div class="card-body d-flex flex-column">
                                        <h5 class="card-title">${item.nama_lomba}</h5>
                                        <div class="mb-2 d-flex flex-wrap gap-1">
                                            ${item.kategoris.length > 0 
                                                ? item.kategoris.map(k => `<span class="badge bg-warning">${k.nama_kategori}</span>`).join('') 
                                                : '<span class="text-muted">Tanpa Kategori</span>'}
                                        </div>
                                        <p class="card-text text-sm mb-2">
                                            ${item.deskripsi.length > 100 ? item.deskripsi.substring(0, 100) + '...' : item.deskripsi}
                                        </p>
                                        <p class="card-text text-sm mb-2">
                                            ${item.link_pendaftaran && item.link_pendaftaran !== '-' 
                                                ? `<a href="${item.link_pendaftaran}" target="_blank">${item.link_pendaftaran}</a>` 
                                                : '-'}
                                        </p>
                                        <div class="mt-auto d-flex flex-wrap justify-content-end gap-2">
                                            ${item.aksi}
                                        </div>
                                    </div>
                                </div>
                            </div>`;
                    });
                }

                $('#lomba-card-semua').html(html);
            },
            error: function() {
                $('#lomba-card-semua').html('<p class="text-danger">Gagal memuat data.</p>');
            }
        });
    }
</script>
@endpush

@push('css')
<style>
    .card {
        border-radius: 1rem;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .card:hover {
        transform: translateY(-6px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    }

    .card-body {
        padding: 1rem 1.25rem;
    }

    .card-title {
        font-weight: 700;
        font-size: 1.25rem;
    }

    .badge.bg-warning {
        font-weight: 600;
        font-size: 0.85rem;
    }

    .card-text a {
        text-decoration: underline;
        color: #0d6efd;
        transition: color 0.2s ease;
    }

    .card-text a:hover {
        color: #0a58ca;
    }
</style>
@endpush