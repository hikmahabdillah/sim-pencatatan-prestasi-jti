@extends('layouts.template', ['page' => $breadcrumb->title])

@section('content')
    @include('layouts.navbar', ['title' => $breadcrumb->list])

    <div class="container-fluid py-4 h-100 flex-grow-1">
        <!-- Tabs -->
        <ul class="nav nav-tabs mb-3" id="verifTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="belum-tab" data-bs-toggle="tab" data-bs-target="#belum" type="button"
                    role="tab" aria-controls="belum" aria-selected="true">Belum Diverifikasi</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="sudah-tab" data-bs-toggle="tab" data-bs-target="#sudah" type="button"
                    role="tab" aria-controls="sudah" aria-selected="false">Terverifikasi</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="rekom-tab" data-bs-toggle="tab" data-bs-target="#rekom" type="button"
                    role="tab" aria-controls="rekom" aria-selected="false">
                    Rekomendasi Mahasiswa
                </button>
            </li>
        </ul>

        <!-- Tombol dan Filter -->
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

        <!-- Tab Contents -->
        <div class="tab-content" id="verifTabsContent">
            <div class="tab-pane fade show active" id="belum" role="tabpanel" aria-labelledby="belum-tab">
                <div class="card p-3">
                    <div class="row" id="lomba-card-belum"></div>
                </div>
            </div>
            <div class="tab-pane fade" id="sudah" role="tabpanel" aria-labelledby="sudah-tab">
                <div class="card p-3">
                    <div class="row" id="lomba-card-sudah"></div>
                </div>
            </div>
            <div class="tab-pane fade" id="rekom" role="tabpanel" aria-labelledby="rekom-tab">
                <div class="card p-3">
                    <div class="row" id="lomba-card-rekom"></div>
                </div>
            </div>
        </div>

        <!-- Modal -->
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
            // Load data awal untuk kedua tab
            loadData('belum');
            loadData('sudah');

            // Event saat tab diganti
            $('button[data-bs-toggle="tab"]').on('shown.bs.tab', function(e) {
                const target = $(e.target).attr('data-bs-target');
                if (target) {
                    const status = target.substring(1); // hapus tanda #
                    loadData(status);
                }
            });

            // Event filter dan search (keyup untuk input text, change untuk select)
            $('#searchKeyword').on('keyup', function() {
                const currentTab = $('#verifTabs button.nav-link.active').attr('data-bs-target');
                if (currentTab) {
                    loadData(currentTab.substring(1));
                }
            });

            $('#filterKategori').on('change', function() {
                const currentTab = $('#verifTabs button.nav-link.active').attr('data-bs-target');
                if (currentTab) {
                    loadData(currentTab.substring(1));
                }
            });
        });

        function loadData(status) {
            let keyword = $('#searchKeyword').val();
            let kategori = $('#filterKategori').val();

            $.ajax({
                url: "{{ url('lomba/listAdmin') }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    keyword: keyword,
                    kategori: kategori,
                    status_verifikasi: status
                },
                success: function(res) {
                    let html = '';

                    if (!res.data || res.data.length === 0) {
                        html = '<p class="text-muted">Tidak ada data lomba ditemukan.</p>';
                    } else {
                        res.data.forEach(item => {
                            html += `
                    <div class="col-12 col-md-6 col-lg-4 mb-6 d-flex">
                        <div class="card shadow w-100 d-flex flex-column">
                            <div class="d-flex justify-content-center align-items-center" 
                                style="height: 200px; width: 150px; margin: auto; border-radius: 25px; overflow: hidden;">
                                <a href="${item.foto ? '/storage/' + item.foto : '/image/fotoDefault.jpg'}" 
                                    target="_blank">
                                    <img src="${item.foto ? '/storage/' + item.foto : '/image/fotoDefault.jpg'}" 
                                        alt="Poster Lomba" class="w-100 h-100" style="object-fit: cover; border-radius: 25px;">
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
                                     ${item.link_pendaftaran && item.link_pendaftaran !== '-' ? 
                                    `<a href="${item.link_pendaftaran}" target="_blank">${item.link_pendaftaran}</a>` :  '-'}
                                    </p>
                                   <p class="card-text text-sm mb-2">
                                     ${item.status_verifikasi === 1
                                        ? `<span class="text-success fw-bold">Disetujui</span>`
                                     : item.status_verifikasi === 0
                                     ? `<span class="text-danger fw-bold">Ditolak</span>`
                                     : `<span class="text-secondary fw-bold">Belum Diverifikasi</span>`}
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
