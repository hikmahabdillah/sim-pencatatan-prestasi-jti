@extends('layouts.template', ['page' => $breadcrumb->title])

@section('content')
    @include('layouts.navbar', ['title' => $breadcrumb->list])
    <div class="container-fluid py-4 h-100 flex-grow-1">
        <div class="d-flex gap-3 justify-content-between align-items-center mb-3">
            <div class="card-tools">
                <button onclick="modalAction('{{ url('mahasiswa/create') }}')" class="btn bg-gradient-info mt-1">
                    Tambah Mahasiswa
                </button>
                <button onclick="modalAction('{{ url('mahasiswa/import') }}')" class="btn bg-gradient-success mt-1">
                    Import Data
                </button>
            </div>
            <div class="d-flex gap-3 align-items-center">
                <p class="text-muted w-100">Filter status:</p>
                <select id="status_filter" name="status_filter" class="form-select mb-3 w-100"
                    style="min-width:150px; max-width: 200px;">
                    <option value="">Semua Status</option>
                    <option value="1">Aktif</option>
                    <option value="0">Non-Aktif</option>
                </select>
            </div>
        </div>
        <div class="card p-3 table-responsive">
            <table id="mahasiswa-table" class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-center">NIM</th>
                        <th class="text-center">Nama</th>
                        <th class="text-center">Angkatan</th>
                        <th class="text-center">Program Studi</th>
                        {{-- <th class="text-center">Minat Bakat</th> --}}
                        <th class="text-center">Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
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

            let tableMahasiswa;

            $(document).ready(function() {
                tableMahasiswa = $('#mahasiswa-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "{{ url('mahasiswa/list') }}",
                        type: "POST",
                        data: function(d) {
                            d.status_filter = $('#status_filter').val();
                        }
                    },
                    columns: [{
                            data: 'DT_RowIndex',
                            className: 'text-center',
                            orderable: false,
                            searchable: false,
                            width: "2%"
                        },
                        {
                            data: 'nim',
                            width: "10%"
                        },
                        {
                            data: 'nama',
                            width: "20%"
                        },
                        {
                            data: 'angkatan',
                            className: 'text-center',
                            width: "8%"
                        },
                        {
                            data: 'prodi',
                            width: "15%"
                        },
                        {
                            data: 'status',
                            className: 'text-center',
                            width: "10%"
                        },
                        {
                            data: 'aksi',
                            className: 'text-center',
                            orderable: false,
                            searchable: false,
                            width: "10%"
                        }
                    ]
                });
                $('#status_filter').change(function() {
                    tableMahasiswa.ajax.reload();
                });
            });
        </script>
    @endpush
