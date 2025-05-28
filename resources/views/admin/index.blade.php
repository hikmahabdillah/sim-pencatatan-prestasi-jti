@extends('layouts.template', ['page' => $breadcrumb->title])

@section('content')
    @include('layouts.navbar', ['title' => $breadcrumb->list])
    <div class="container-fluid py-4 h-100 flex-grow-1">
        <button onclick="modalAction('{{ url('admin/create') }}')" class="btn bg-gradient-info mt-1">
            Tambah Admin
        </button>
        <div class="card p-3 table-responsive">
            <table id="admin-table" class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-center">Username</th>
                        <th class="text-center">Nama Admin</th>
                        <th class="text-center">Email</th>
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

            let tableAdmin;

            $(document).ready(function() {
                tableAdmin = $('#admin-table').DataTable({
                    processing: true,
                    serverSide: false,
                    ajax: {
                        url: "{{ url('admin/list') }}",
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
                            data: 'username',
                            width: "15%"
                        },
                        {
                            data: 'nama_admin',
                            width: "25%"
                        },
                        {
                            data: 'email',
                            width: "25%"
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
                            width: "15%"
                        }
                    ]
                });
            });
        </script>
    @endpush