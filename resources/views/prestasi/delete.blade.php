<!-- Modal -->
<form action="{{ url('/prestasi/' . $prestasi->id_prestasi . '/delete-prestasi') }}" method="POST" id="form-delete">
    @csrf
    @method('DELETE')
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Konfirmasi Hapus Prestasi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger text-white" role="alert">
                    <strong>Konfirmasi!</strong> Apakah anda yakin ingin menghapus prestasi ini? Aksi ini tidak dapat
                    dibatalkan.
                </div>
                <table class="table table-bordered">
                    <tr>
                        <th>Nama Prestasi</th>
                        <td>{{ $prestasi->nama_prestasi }}</td>
                    </tr>
                    <tr>
                        <th>Tingkat</th>
                        <td>{{ $prestasi->tingkatPrestasi->nama_tingkat_prestasi ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Juara</th>
                        <td>{{ $prestasi->juara }}</td>
                    </tr>
                    <tr>
                        <th>Tanggal</th>
                        <td>{{ \Carbon\Carbon::parse($prestasi->tanggal_prestasi)->format('d/m/Y') }}</td>
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
        $("#form-delete").validate({
            rules: {},
            submitHandler: function(form) {
                $.ajax({
                    url: form.action,
                    type: form.method,
                    data: $(form).serialize(),
                    success: function(response) {
                        if (response.status) {
                            $('#myModal').modal('hide');
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message
                            });
                            window.location.href = response.redirect_url;
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Terjadi Kesalahan',
                                text: response.message
                            });
                        }
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: xhr.responseJSON?.message ||
                                'Terjadi kesalahan'
                        });
                    }
                });
                return false;
            }
        });
    });
</script>
