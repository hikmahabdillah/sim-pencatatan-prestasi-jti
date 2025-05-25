@extends('layouts.template', ['page' => $breadcrumb->title])

@section('content')
    @include('layouts.navbar', ['title' => $breadcrumb->list])
    <div class="container-fluid py-4 h-100 flex-grow-1">
        <button onclick="modalAction('{{ url('lomba/create') }}')" class="btn bg-gradient-info mt-1">
            Tambah Data
        </button>
        <div class="card p-3 table-responsive">
            <table id="lomba-table" class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-center">Nama Lomba</th>
                        <th class="text-center">Deskripsi</th>
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

            let tableLomba;

            $(document).ready(function() {
                tableLomba = $('#lomba-table').DataTable({
                    processing: true,
                    serverSide: false,
                    ajax: {
                        url: "{{ url('lomba/list') }}",
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
                            data: 'nama_lomba',
                            width: "20%"
                        },
                        {
                            data: 'deskripsi',
                            className: 'ellipsis',
                            width: "45%",
                            render: function(data, type, row) {
                                if (!data) return '';
                                const shortText = data.length > 50 ? data.substring(0, 50) + '...' :
                                    data;
                                return `<span data-bs-toggle="tooltip" 
                           data-bs-placement="top" 
                           title="${data.replace(/"/g, '&quot;')}" 
                           data-container="body" 
                           data-animation="true">
                      ${shortText}
                    </span>`;
                            }
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
                tableLomba.on('draw', function() {
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