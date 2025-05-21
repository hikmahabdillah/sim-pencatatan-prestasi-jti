@extends('layouts.template', ['page' => $breadcrumb->title])

@section('content')
    @include('layouts.navbar', ['title' => $breadcrumb->list])
    <div class="container-fluid py-4 h-100 flex-grow-1">
        <button onclick="modalAction('{{ url('tingkat_prestasi/create') }}')" class="btn bg-gradient-info mt-1">
            Tambah Data
        </button>
        <div class="card p-3 table-responsive">
            <table id="tingkat_prestasi-table" class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th class="text-center">ID Tingkat Prestasi</th>
                        <th class="text-center">Nama Tingkat Prestasi</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
        <div id="myModal" class="modal fade animate shake" tabindex="-1" tingkat_prestasi="dialog" data-backdrop="static"
            data-keyboard="false" data-width="75%" aria-hidden="true"></div>
        @include('layouts.footer')
    @endsection

    {{-- contoh implementasi data tables dan sweet alert --}}
    @push('js')
        <script>
            function modalAction(url = '') {
                $('#myModal').load(url, function() {
                    $('#myModal').modal('show');
                });
            }

            let tablecrud;

            $(document).ready(function() {
                tablecrud = $('#tingkat_prestasi-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "{{ url('tingkat_prestasi/list') }}",
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
                            data: 'nama_tingkat_prestasi',
                            width: "20%"
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

                // Inisialisasi tooltip setiap kali tabel di-redraw
                tablecrud.on('draw', function() {
                    $('[data-bs-toggle="tooltip"]').tooltip();
                });
            });

            function showSuccess() {
                Swal.fire({
                    title: 'Berhasil!',
                    text: 'Ini alert dari SweetAlert2.',
                    icon: 'success',
                    confirmButtonText: 'OK'
                });
            }
        </script>
    @endpush
