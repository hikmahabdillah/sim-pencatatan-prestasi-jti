@extends('layouts.template')

@section('content')
    @include('layouts.navbar', ['title' => $breadcrumb->list])
    <div class="container-fluid py-4">
        <div class="card">
            <div class="card-header pb-1">
                <div class="d-flex justify-content-between align-items-center">
                    <a href="{{ route('laporan-prestasi.index') }}" class="btn btn-secondary mt-3">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                    <div class="d-flex align-items-center gap-2"> <!-- Tambahkan div wrapper ini -->
                        <select id="periode_filter" class="form-select" style="width: 200px; height: 42px; margin-top: 0px;">
                            <!-- Atur width disini -->
                            <option value="">Pilih Periode</option>
                            @foreach ($periode as $p)
                                <option value="{{ $p->id_periode }}">
                                    {{ $p->semester }} - {{ $p->tahun_ajaran }}
                                </option>
                            @endforeach
                        </select>
                        <div class="btn-group mt-3" role="group">
                            <button id="export_pdf" class="btn btn-danger me-0" disabled>
                                <i class="fas fa-file-pdf me-1"></i> Export PDF
                            </button>
                            <button id="export_excel" class="btn btn-success" disabled>
                                <i class="fas fa-file-excel me-1"></i> Export Excel
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body pt-0">
                <div class="table-responsive">
                    <table id="prestasi_table" class="table table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Mahasiswa</th>
                                <th>NIM</th>
                                <th>Kategori</th>
                                <th>Nama Kegiatan</th>
                                <th>Tingkat</th>
                                <th>Periode Lomba</th>
                                <th>Bukti Sertifikat</th>
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
            var table = $('#prestasi_table').DataTable({
                processing: true,
                serverSide: false,
                ajax: {
                    url: "{{ route('laporan-prestasi.list-by-periode') }}",
                    data: function(d) {
                        d.id_periode = $('#periode_filter').val();
                    },
                    error: function(xhr, error, thrown) {
                        console.log('Ajax Error:', xhr.responseText);
                        alert('Error loading data. See console for details.');
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'nama_mahasiswa',
                        name: 'nama_mahasiswa',
                        render: function(data, type, row) {
                            try {
                                // Parse JSON string to array
                                const anggota = JSON.parse(data);
                                if (Array.isArray(anggota)) {
                                    // Extract all names and join with comma
                                    return anggota.map(m => m.nama).join(', ');
                                }
                                return '-';
                            } catch (e) {
                                console.error('Error parsing nama_mahasiswa:', e);
                                return '-';
                            }
                        }
                    },
                    {
                        data: 'nim',
                        name: 'nim',
                        render: function(data, type, row) {
                            try {
                                // Parse JSON string to array
                                const anggota = JSON.parse(data);
                                if (Array.isArray(anggota)) {
                                    // Extract all NIMs and join with comma
                                    return anggota.map(m => m.nim).join(', ');
                                }
                                return '-';
                            } catch (e) {
                                console.error('Error parsing nim:', e);
                                return '-';
                            }
                        }
                    },
                    {
                        data: 'kategori',
                        name: 'kategori'
                    },
                    {
                        data: 'nama_prestasi',
                        name: 'nama_prestasi'
                    },
                    {
                        data: 'tingkat',
                        name: 'tingkat'
                    },
                    {
                        data: 'periode',
                        name: 'periode'
                    },
                    {
                        data: 'bukti_sertifikat',
                        name: 'bukti_sertifikat',
                        orderable: false,
                        searchable: false
                    }
                ],
                language: {
                    emptyTable: "Tidak ada data prestasi untuk periode yang dipilih"
                }
            });

            $('#periode_filter').change(function() {
                if ($(this).val()) {
                    $('#export_btn').removeClass('btn-secondary').addClass('btn-primary').prop('disabled',
                        false);
                } else {
                    $('#export_btn').removeClass('btn-primary').addClass('btn-secondary').prop('disabled',
                        true);
                }
                table.ajax.reload();
            });

            $('#export_btn').click(function() {
                const periodeId = $('#periode_filter').val();
                if (periodeId) {
                    window.location.href = "{{ url('laporan-prestasi/export-periode') }}/" + periodeId;
                }
            });

            $('#periode_filter').change(function() {
                const periodeId = $(this).val();
                if (periodeId) {
                    $('#export_pdf, #export_excel').prop('disabled', false);

                    // Update URL export dengan periode yang dipilih
                    $('#export_pdf').off('click').on('click', function() {
                        window.location.href =
                            `{{ url('laporan-prestasi/periode/export') }}/${periodeId}?format=pdf`;
                    });

                    $('#export_excel').off('click').on('click', function() {
                        window.location.href =
                            `{{ url('laporan-prestasi/periode/export') }}/${periodeId}?format=excel`;
                    });
                } else {
                    $('#export_pdf, #export_excel').prop('disabled', true);
                }
            });
        });
    </script>
@endpush
