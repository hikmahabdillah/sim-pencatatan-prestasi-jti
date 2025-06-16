@extends('layouts.template', ['page' => 'Lomba'])

@section('content')
    @include('layouts.navbar', ['title' => 'Lomba'])
    <div class="container-fluid py-4">
        <ul class="nav nav-tabs mb-3" id="lombaTabs" role="tablist">
            <li class="nav-item">
                <button class="nav-link active" id="semua-tab" data-bs-toggle="tab" data-bs-target="#semua" type="button"
                    role="tab">Semua Lomba</button>
            </li>
            <li class="nav-item">
                <button class="nav-link" id="rekom-tab" data-bs-toggle="tab" data-bs-target="#rekom" type="button"
                    role="tab">Rekomendasi Saya</button>
            </li>
        </ul>

        <!-- Filter (untuk kedua tab) -->
        <div id="filterSection" class="d-flex justify-content-end gap-2 mb-3">
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
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead class="bg-gradient-secondary text-white">
                            <tr>
                                <th>No</th>
                                <th>NIM Mahasiswa</th>
                                <th>Nama Mahasiswa</th>
                                <th>Lomba</th>
                                <th>Kategori Lomba</th>
                                <th>Tanggal Rekomendasi</th>
                            </tr>
                        </thead>
                        <tbody id="lomba-table-rekom">
                            <tr>
                                <td colspan="6" class="text-center text-muted">Memuat data...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        @include('layouts.footer')
    </div>
@endsection

@push('js')
    <script>
        $(document).ready(function() {
            loadLomba();
            toggleFilter(); // Tampilkan filter saat awal

            $('#searchKeyword, #filterKategori').on('input change', function() {
                loadLomba();
            });

            $('button[data-bs-toggle="tab"]').on('shown.bs.tab', function() {
                toggleFilter();
                loadLomba();
            });

            function toggleFilter() {
                const activeTab = $('.nav-link.active').attr('id');
                if (activeTab === 'semua-tab' || activeTab === 'rekom-tab') {
                    $('#filterSection').show();
                } else {
                    $('#filterSection').hide();
                }
            }
        });

        function loadLomba() {
            let keyword = $('#searchKeyword').val();
            let kategori = $('#filterKategori').val();
            let activeTab = $('.nav-link.active').attr('id');

            let url = '';
            let targetElement = '';

            if (activeTab === 'semua-tab') {
                url = "{{ url('lomba/listLomba') }}";
                targetElement = '#lomba-card-semua';
            } else if (activeTab === 'rekom-tab') {
                url = "{{ url('lomba/getRekombyDosen') }}";
                targetElement = '#lomba-table-rekom';
            }

            $.ajax({
                url: url,
                method: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    keyword: keyword,
                    kategori: kategori,
                },
                success: function(res) {
                    let html = '';

                    if (!res.data || res.data.length === 0) {
                        if (activeTab === 'semua-tab') {
                            html = '<p class="text-muted">Tidak ada lomba ditemukan.</p>';
                        } else {
                            html =
                                '<tr><td colspan="5" class="text-center text-muted">Tidak ada data rekomendasi.</td></tr>';
                        }
                    } else {
                        if (activeTab === 'semua-tab') {
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
                                                ? item.kategoris.map(k => `<span class="badge bg-warning text-wrap">${k.nama_kategori}</span>`).join('') 
                                                : '<span class="text-muted">Tanpa Kategori</span>'}
                                        </div>
                                        <p class="card-text text-sm mb-2">
                                            ${item.deskripsi.length > 100 ? item.deskripsi.substring(0, 100) + '...' : item.deskripsi}
                                        </p>
                                        <p class="card-text text-sm mb-2">
                                            ${item.link_pendaftaran && item.link_pendaftaran !== '-' 
                                                ? `<a href="${item.link_pendaftaran}" target="_blank">${item.link_pendaftaran}</a>` 
                                                : '-' }
                                        </p>
                                        <div class="mt-auto d-flex flex-wrap justify-content-end gap-2">
                                            ${item.aksi ?? ''}
                                        </div>
                                    </div>
                                </div>
                            </div>`;
                            });
                        } else {
                            res.data.forEach((item, index) => {
                                html += `
                            <tr>
                                <td>${index + 1}</td>
                                <td>${item.mahasiswa.nim}</td>
                                <td>${item.mahasiswa.nama}</td>
                                <td>${item.lomba.nama_lomba}</td>
                                <td>
                                    ${item.lomba.kategoris && item.lomba.kategoris.length > 0 
                                        ? item.lomba.kategoris.map(k => k.nama_kategori).join(', ') 
                                        : '-'}
                                </td>
                                <td>${new Date(item.tanggal_rekomendasi).toLocaleDateString('id-ID', {
                                    day: '2-digit', month: 'long', year: 'numeric'
                                })}</td>
                            </tr>`;
                            });
                        }
                    }

                    $(targetElement).html(html);
                },
                error: function() {
                    if (activeTab === 'semua-tab') {
                        $('#lomba-card-semua').html('<p class="text-danger">Gagal memuat data.</p>');
                    } else {
                        $('#lomba-table-rekom').html(
                            '<tr><td colspan="5" class="text-danger text-center">Gagal memuat data.</td></tr>'
                            );
                    }
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

        table.table {
            border-radius: 0.5rem;
            overflow: hidden;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
        }

        table.table thead {
            background: linear-gradient(60deg, #f96b00, #ffae00);
            color: white;
            text-shadow: 0 1px 1px rgba(0, 0, 0, 0.1);
            border: none;
        }

        table.table tbody tr {
            transition: background 0.2s ease;
        }

        table.table tbody tr:hover {
            background: rgb(255, 214, 170);
        }

        .badge.bg-primary {
            background-color: #007bff !important;
            font-size: 0.75rem;
            padding: 0.4em 0.6em;
        }

        .table td,
        .table th {
            vertical-align: middle !important;
        }

        .avatar-sm {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 0.5rem;
        }

        .text-muted {
            font-style: italic;
            color: #6c757d !important;
        }
    </style>
@endpush
