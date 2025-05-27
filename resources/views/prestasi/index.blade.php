@extends('layouts.template', ['page' => $breadcrumb->title])

@section('content')
    @include('layouts.navbar', ['title' => $breadcrumb->list])
    <div class="container-fluid py-4 h-100 flex-grow-1">
        <button onclick="modalAction('{{ url('prestasi/create') }}')" class="btn bg-gradient-info mt-1">
            Tambah Prestasi
        </button>
        <div class="card p-3 table-responsive">
            <table id="prestasi-table" class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-center">Nama Prestasi</th>
                        <th class="text-center">Mahasiswa</th>
                        <th class="text-center">Kategori</th>
                        <th class="text-center">Tingkat Prestasi</th>
                        <th class="text-center">Status Verifikasi</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
        <div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" data-backdrop="static"
            data-keyboard="false" data-width="75%" aria-hidden="true"></div>
        @include('layouts.footer')
    @endsection

    @push('js')
        <script>
            function modalAction(url = '') {
                $('#myModal').load(url, function() {
                    $('#myModal').modal('show');
                });
            }

            let tablecrud;

            $(document).ready(function() {
                tablecrud = $('#prestasi-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "{{ url('prestasi/list') }}",
                        type: "POST",
                    },
                    columns: [{
                            data: 'DT_RowIndex',
                            className: 'text-center',
                            orderable: false,
                            searchable: false,
                            width: "2%"
                        },
                        {
                            data: 'nama_prestasi',
                            width: "15%"
                        },
                        {
                            data: 'mahasiswa',
                            width: "15%"
                        },
                        {
                            data: 'kategori',
                            width: "10%"
                        },
                        {
                            data: 'tingkat_prestasi',
                            width: "10%"
                        },
                        {
                            data: 'status_verifikasi',
                            className: 'text-center',
                            width: "10%"
                        },
                        {
                            data: 'aksi',
                            className: 'text-center',
                            orderable: false,
                            searchable: false,
                            width: "15%"
                        }
                    ]
                });

                tablecrud.on('draw', function() {
                    $('[data-bs-toggle="tooltip"]').tooltip();
                });
            });
        </script>
    @endpush
