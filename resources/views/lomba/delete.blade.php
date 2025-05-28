<!-- Modal -->
<form action="{{ url('/lomba/' . $data->id_lomba . '/delete') }}" method="POST" id="form-delete-lomba">
    @csrf
    @method('DELETE')
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Nonaktifkan Lomba</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger text-white" role="alert">
                    <strong>Konfirmasi!</strong> Apakah Anda yakin ingin menghapus lomba ini?
                </div>
                <table class="table table-bordered">
                    <tr>
                        <th width="30%">Nama Lomba</th>
                        <td>{{ $data->nama_lomba }}</td>
                    </tr>
                    <tr>
                        <th>Penyelenggara</th>
                        <td>{{ $data->penyelenggara }}</td>
                    </tr>
                    <tr>
                        <th>Kategori</th>
                        <td>{{ $data->kategori->nama_kategori ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Tingkat Prestasi</th>
                        <td>{{ $data->tingkatPrestasi->nama_tingkat_prestasi ?? $data->nama_tingkat_prestasi }}</td>
                    </tr>
                    <tr>
                        <th>Deaadline Pendaftaran</th>
                        <td>{{ $data->deadline_pendaftaran }}</td>
                    </tr>
                    <tr>
                        <th>Status Verifikasi</th>
                        <td>
                            @if ($data->status_verifikasi === 1)
                            <span class="badge bg-success">Disetujui</span>
                            @elseif ($data->status_verifikasi === 0)
                            <span class="badge bg-danger">Ditolak</span>
                            @else
                            <span class="badge bg-secondary">Belum Diverifikasi</span>
                            @endif
                        </td>

                    </tr>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Kembali</button>
                <button type="submit" class="btn bg-gradient-danger">Hapus</button>
            </div>
        </div>
    </div>
</form>

<script>
    $(document).ready(function() {
        $("#form-delete-lomba").validate({
            submitHandler: function(form) {
                $.ajax({
                    url: form.action,
                    type: 'DELETE',
                    data: $(form).serialize(),
                    success: function(response) {
                        if (response.status) {
                            $('#form-delete-lomba').closest('.modal').modal('hide');
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: response.message
                            });
                        }
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Terjadi Kesalahan',
                            text: 'Gagal menghapus data lomba.'
                        });
                    }
                });
                return false;
            }
        });
    });
</script>