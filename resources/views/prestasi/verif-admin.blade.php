<!-- Modal -->
<form action="{{ url('/prestasi/' . $prestasi->id_prestasi . '/update-verifikasi-admin') }}" method="POST" id="form-edit"
    enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Verifikasi Prestasi Mahasiswa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="form-group">
                        <label for="status_verifikasi" class="form-label">Status Verifikasi</label>
                        <select id="status_verifikasi" name="status_verifikasi" class="form-control" required>
                            <option value=""{{ $prestasi->status_verifikasi === null ? 'selected' : '' }}>
                                Pilih
                                Status Verifikasi</option>
                            <option value="1" {{ $prestasi->status_verifikasi === true ? 'selected' : '' }}>
                                Disetujui</option>
                            <option value="0" {{ $prestasi->status_verifikasi === false ? 'selected' : '' }}>
                                Ditolak</option>
                        </select>
                        <div id="error-status_verifikasi" class="text-danger error-text"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Kembali</button>
                    <button type="submit" class="btn bg-gradient-primary">Simpan</button>
                </div>
            </div>
        </div>
</form>

<script>
    $(document).ready(function() {
        // Initialize validation
        $("#form-edit").validate({
            rules: {
                status_verifikasi_admin: {
                    required: true
                },
            },
            messages: {
                status_verifikasi_admin: {
                    required: "Status verifikasi wajib dipilih"
                },
            },
            errorElement: 'span',
            errorPlacement: function(error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function(element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            },
            submitHandler: function(form) {
                $.ajax({
                    url: form.action,
                    type: 'PUT',
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
                            $('.error-text').text('');
                            $.each(response.msgField, function(prefix, val) {
                                $('#error-' + prefix).text(val[0]);
                            });
                            Swal.fire({
                                icon: 'error',
                                title: 'Terjadi Kesalahan',
                                text: response.message
                            });
                        }
                    }
                });
                return false;
            },
            errorElement: 'span',
            errorPlacement: function(error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function(element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            }
        });
    });
</script>
