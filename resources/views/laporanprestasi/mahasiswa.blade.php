@extends('layouts.template')

@section('content')
    @include('layouts.navbar', ['title' => 'Laporan Prestasi - Mahasiswa'])
    <div class="container-fluid py-4">
        <div class="card">
            <div class="card-header pb-1">
                <div class="d-flex justify-content-between align-items-center">
                    <a href="{{ route('laporan-prestasi.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                    <div class="d-flex align-items-center gap-2">
                        <select id="prodi_filter" class="form-select" style="width: 200px;">
                            <option value="">Semua Prodi</option>
                            @foreach ($prodi as $p)
                                <option value="{{ $p->id_prodi }}">{{ $p->nama_prodi }}</option>
                            @endforeach
                        </select>
                        <select id="angkatan_filter" class="form-select" style="width: 150px;">
                            <option value="">Semua Angkatan</option>
                            @for ($i = date('Y'); $i >= date('Y') - 5; $i--)
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                </div>
            </div>
            <div class="card-body pt-0">
                <div class="table-responsive">
                    <table id="mahasiswa_table" class="table table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>NIM</th>
                                <th>Nama Mahasiswa</th>
                                <th>Prodi</th>
                                <th>Angkatan</th>
                                <th>Jumlah Prestasi</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
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
            var table = $('#mahasiswa_table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('laporan-prestasi.mahasiswa.list') }}",
                    data: function(d) {
                        d.prodi = $('#prodi_filter').val();
                        d.angkatan = $('#angkatan_filter').val();
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'nim',
                        name: 'nim'
                    },
                    {
                        data: 'nama',
                        name: 'nama'
                    },
                    {
                        data: 'prodi',
                        name: 'prodi'
                    },
                    {
                        data: 'angkatan',
                        name: 'angkatan'
                    },
                    {
                        data: 'prestasi_count',
                        name: 'prestasi_count'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ],
                language: {
                    emptyTable: "Tidak ada data mahasiswa"
                }
            });

            $('#prodi_filter, #angkatan_filter').change(function() {
                table.ajax.reload();
            });
        });
    </script>
@endpush
