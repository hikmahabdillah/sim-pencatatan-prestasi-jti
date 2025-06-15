@extends('layouts.template', ['page' => $breadcrumb->title])

@section('content')
    @include('layouts.navbar', ['title' => $breadcrumb->list])

    <div class="container-fluid py-4 h-100 flex-grow-1">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <button onclick="modalAction('{{ url('lomba/create') }}')" class="btn bg-gradient-info mt-1">
                Tambah Data Lomba
            </button>

            <div class="d-flex gap-2">
                <select id="filterStatus" class="form-control">
                    <option value="">Semua Status</option>
                    <option value="1">Disetujui</option>
                    <option value="0">Ditolak</option>
                </select>

                <div class="input-group" style="width: 350px;">
                    <input type="text" id="searchKeyword" class="form-control" placeholder="Cari nama lomba...">
                    <span class="input-group-text bg-gradient-info text-white">
                        <i class="fas fa-search"></i>
                    </span>
                </div>
            </div>
        </div>

        <!-- Card Lomba -->
        <div class="card p-3">
            <div class="mx-auto" style="width: 80vw; max-width: 1200px;">
                <div id="lomba-card-list" class="d-flex flex-wrap gap-3">
                    {{-- Data akan di-load via AJAX --}}
                </div>
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

            // Trigger ketika keyword diketik
            $('#searchKeyword').on('keyup', function() {
                fetchLomba();
            });

            // Trigger ketika status verifikasi diubah
            $('#filterStatus').on('change', function() {
                fetchLomba();
            });
        });

        function fetchLomba() {
            let keyword = $('#searchKeyword').val();
            let status = $('#filterStatus').val();

            $.ajax({
                url: "{{ url('lomba/listInput') }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    keyword: keyword,
                    status_verifikasi: status,
                },
                success: function(res) {
                    let html = '';

                    if (res.data.length === 0) {
                        html = '<p class="text-center text-muted">Tidak ada data lomba.</p>';
                    } else {
                        res.data.forEach(item => {
                            html += `
                        <div class="mb-4 flex-grow-1" style="flex: 0 0 calc(33.333% - 1rem); max-width: calc(33.333% - 1rem);">
                            <div class="card shadow h-100 d-flex flex-column">
                                <!-- Foto -->
                                <div class="d-flex justify-content-center align-items-center" 
                                style="height: 200px; width: 150px; margin: auto; border-radius: 25px; overflow: hidden;">
                                <a href="${item.foto ? '/storage/' + item.foto : '/image/fotoDefault.jpg'}" 
                                    target="_blank">
                                    <img src="${item.foto ? '/storage/' + item.foto : '/image/fotoDefault.jpg'}" 
                                        alt="Poster Lomba" class="w-100 h-100" style="object-fit: cover; border-radius: 25px;">
                                </a>
                            </div>
                                <!-- Konten Card -->
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title">${item.nama_lomba}</h5>
                                   <div class="mb-2">
                                    ${item.nama_kategori.split(',').map(k => `<span class="badge bg-gradient-warning text-wrap me-1">${k.trim()}</span>`).join('')}
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
                                    <!-- Tombol aksi -->
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
