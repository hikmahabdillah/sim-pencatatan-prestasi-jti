<!-- Modal -->
<form action="{{ url('/dospem/' . $data->id_dospem . '/delete') }}" method="POST" id="form-delete">
    @csrf
    @method('DELETE')
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Konfirmasi Hapus Dosen Pembimbing</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger text-white" role="alert">
                    <strong>Konfirmasi!</strong> Apakah anda yakin ingin menonaktifkan dosen pembimbing ini?
                </div>
                <table class="table table-bordered">
                    <tr>
                        <th width="20%">NIP</th>
                        <td>{{ $data->nip }}</td>
                    </tr>
                    <tr>
                        <th>Nama</th>
                        <td>{{ $data->nama }}</td>
                    </tr>
                    <tr>
                        <th>Program Studi</th>
                        <td>{{ $data->prodi->nama_prodi }}</td>
                    </tr>
                </table>
                <div class="form-group">
                    <label for="keterangan_nonaktif" class="form-label">Keterangan nonaktif</label>
                    <textarea id="keterangan_nonaktif" name="keterangan_nonaktif" class="form-control" required>{{ $data->pengguna->keterangan_nonaktif }}</textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Kembali</button>
                <button type="submit" class="btn bg-gradient-danger">Nonaktifkan</button>
            </div>
        </div>
    </div>
</form>
<script>
    $(document).ready(function() {
        $("#form-delete").validate({
            rules: {
                keterangan_nonaktif: {
                    required: true
                }
            },
            messages: {
                keterangan_nonaktif: {
                    required: "Keterangan nonaktif wajib diisi ketika status nonaktif"
                }
            },
            submitHandler: function(form) {
                $.ajax({
                    url: form.action,
                    type: 'DELETE',
                    data: $(form).serialize(),
                    success: function(response) {
                        if (response.status) {
                            $('#myModal').modal('hide');
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message
                            });
                            tableDospem.ajax.reload();
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Terjadi Kesalahan',
                                text: response.message
                            });
                        }
                    }
                });
                return false;
            }
        });
    });
</script>
