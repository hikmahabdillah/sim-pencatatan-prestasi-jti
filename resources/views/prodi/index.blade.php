@extends('layouts.template', ['page' => $breadcrumb->title])

@section('content')
    @include('layouts.navbar', ['title' => $breadcrumb->list])
    <div class="container-fluid py-4 h-100 flex-grow-1">
        <button onclick="modalAction('{{ url('prodi/create') }}')" class="btn bg-gradient-info mt-1">
            Tambah Prodi
        </button>
        <div class="card p-3 table-responsive">
            <table id="prodi-table" class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-center">Nama Program Studi</th>
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
                $('#myModal').load(url, function () {
                    $('#myModal').modal('show');
                });
            }

            let tablecrud;

            $(document).ready(function () {
                tablecrud = $('#prodi-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "{{ url('prodi/list') }}",
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
                        data: 'nama_prodi',
                        width: "50%"
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

                tablecrud.on('draw', function () {
                    $('[data-bs-toggle="tooltip"]').tooltip();
                });
            });
        </script>
    @endpush