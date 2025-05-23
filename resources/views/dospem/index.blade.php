@extends('layouts.template', ['page' => $breadcrumb->title])

@section('content')
    @include('layouts.navbar', ['title' => $breadcrumb->list])
    <div class="container-fluid py-4 h-100 flex-grow-1">
        <button onclick="modalAction('{{ url('dospem/create') }}')" class="btn bg-gradient-info mt-1">
            Tambah Dosen Pembimbing
        </button>
        <div class="card p-3 table-responsive">
            <table id="dospem-table" class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-center">NIP</th>
                        <th class="text-center">Nama</th>
                        <th class="text-center">Program Studi</th>
                        <th class="text-center">Bidang Keahlian</th>
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

            let tableDospem;

            $(document).ready(function() {
                tableDospem = $('#dospem-table').DataTable({
                    processing: true,
                    serverSide: false,
                    ajax: {
                        url: "{{ url('dospem/list') }}",
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
                            data: 'nip',
                            width: "10%"
                        },
                        {
                            data: 'nama',
                            width: "20%"
                        },
                        {
                            data: 'prodi',
                            width: "15%"
                        },
                        {
                            data: 'kategori',
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
            });
        </script>
    @endpush