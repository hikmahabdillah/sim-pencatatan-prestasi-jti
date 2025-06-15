@extends('layouts.template', ['page' => 'Lomba Mahasiswa'])

@section('content')
    @include('layouts.navbar', ['title' => 'Lomba Mahasiswa'])

    <div class="container-fluid py-4">
        <!-- Tabs -->
        <ul class="nav nav-tabs mb-3" id="lombaTabs" role="tablist">
            <li class="nav-item">
                <button class="nav-link active" id="semua-tab" data-bs-toggle="tab" data-bs-target="#semua" type="button"
                    role="tab">Semua Lomba</button>
            </li>
            <li class="nav-item">
                <button class="nav-link" id="rekom-tab" data-bs-toggle="tab" data-bs-target="#rekom" type="button"
                    role="tab">Rekomendasi Lomba</button>
            </li>
        </ul>

        <!-- Filter -->
        <div class="d-flex justify-content-end gap-2 mb-3">
            <!-- Filter Kategori hanya untuk tab Semua Lomba -->
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

            <!-- Filter tipe lomba khusus untuk tab rekom -->
            <select id="filterTipeLomba" class="form-control" style="max-width: 150px; display:none;">
                <option value="">Semua Tipe Lomba</option>
                <option value="tim">Tim</option>
                <option value="individu">Individu</option>
            </select>
        </div>

        <!-- Tab Contents -->
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
            function toggleFilters() {
                const activeTab = $('#lombaTabs .nav-link.active').attr('id');
                if (activeTab === 'rekom-tab') {
                    $('#filterKategori').hide();
                    $('#filterKategori').val('');
                    $('#filterTipeLomba').show();
                } else {
                    $('#filterKategori').show();
                    $('#filterTipeLomba').hide();
                    $('#filterTipeLomba').val('');
                }
            }

            toggleFilters();

            // Load awal
            loadLomba('semua');
            loadLomba('rekom');

            // Tab switching
            $('button[data-bs-toggle="tab"]').on('shown.bs.tab', function(e) {
                toggleFilters();
                const tab = $(e.target).data('bs-target').substring(1);
                loadLomba(tab);
            });

            // Search & filter
            $('#searchKeyword, #filterKategori, #filterTipeLomba').on('input change', function() {
                const activeTab = $('#lombaTabs .nav-link.active').data('bs-target').substring(1);
                loadLomba(activeTab);
            });
        });

        function loadLomba(status) {
            let keyword = $('#searchKeyword').val();
            let kategori = '';
            let tipeLomba = '';

            if (status === 'semua') {
                kategori = $('#filterKategori').val();
            } else if (status === 'rekom') {
                tipeLomba = $('#filterTipeLomba').val();
            }

            $.ajax({
                url: status === 'semua' ? "{{ url('lomba/listLomba') }}" : "{{ url('lomba/listRekom') }}",
                method: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    keyword: keyword,
                    kategori: kategori,
                    tipe_lomba: tipeLomba,
                },
                success: function(res) {
                    let html = '';

                    if (!res.data || res.data.length === 0) {
                        html = '<p class="text-muted">Tidak ada lomba ditemukan.</p>';
                    } else {
                        res.data.forEach(item => {
                            // Definisi warna berdasarkan ranking
                            let rankStyles = {
                                1: {
                                    bg: '#FFD700',
                                    color: '#8B4513',
                                    shadow: 'rgba(255, 215, 0, 0.5)'
                                }, // Gold
                                2: {
                                    bg: '#C0C0C0',
                                    color: '#2F4F4F',
                                    shadow: 'rgba(192, 192, 192, 0.5)'
                                }, // Silver
                                3: {
                                    bg: '#CD7F32',
                                    color: '#FFFFFF',
                                    shadow: 'rgba(205, 127, 50, 0.5)'
                                }, // Bronze
                                default: {
                                    bg: '#fc6630',
                                    color: '#FFFFFF',
                                    shadow: 'rgba(252, 102, 48, 0.5)'
                                } // Coral
                            };

                            let style = rankStyles[item.rank] || rankStyles.default;

                            html += `
                            <div class="col-12 col-md-6 col-lg-4 mb-6 d-flex">
                                <div class="card shadow w-100 d-flex flex-column position-relative">
                                    ${
                                        status === 'rekom' && item.rank
                                            ? `<div class="ranking-badge position-absolute top-0 start-0 m-3 px-3 py-1 rounded-pill fw-bold d-flex align-items-center gap-1" 
                                                     style="z-index: 10; background-color: ${style.bg}; color: ${style.color}; box-shadow: 0 0 8px ${style.shadow};">
                                                    <i class="fas fa-trophy" style="color: ${style.color}; font-size: 1.2rem;"></i> ${item.rank}
                                                   </div>`
                                            : ''
                                    }
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
                                                ? item.kategoris.map(k => `<span class="badge bg-warning text-wrap">${k.nama_kategori}</span>`).join('') 
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

                    $(`#lomba-card-${status}`).html(html);
                },
                error: function() {
                    $(`#lomba-card-${status}`).html('<p class="text-danger">Gagal memuat data.</p>');
                }
            });
        }
    </script>
@endpush

@push('css')
    <style>
        .ranking-badge {
            font-size: 1.1rem;
            letter-spacing: 1px;
            user-select: none;
            /* Background color dan color akan diset via JavaScript */
        }

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
