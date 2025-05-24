<!-- Modal -->
<form action="{{ url('/admin/' . $data->id_admin . '/update-profile') }}" method="POST" id="form-edit-profile">
    @csrf
    @method('PUT')
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Data Admin</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" id="username" name="username" class="form-control" value="{{ $data->username }}" readonly>
                </div>
                <div class="form-group">
                    <label for="nama_admin" class="form-label">Nama Lengkap</label>
                    <input type="text" id="nama_admin" name="nama_admin" class="form-control" value="{{ $data->nama_admin }}" required>
                    <div id="error-nama_admin" class="text-danger error-text"></div>
                </div>
                <div class="form-group">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" id="email" name="email" class="form-control" value="{{ $data->email }}" required>
                    <div id="error-email" class="text-danger error-text"></div>
                </div>
                <div class="form-group">
                    <label for="status_aktif" class="form-label">Status Akun</label>
                    <select id="status_aktif" name="status_aktif" class="form-control">
                        <option value="1" {{ $data->pengguna->status_aktif ? 'selected' : '' }}>Aktif</option>
                        <option value="0" {{ !$data->pengguna->status_aktif ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                    <div id="error-status_aktif" class="text-danger error-text"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Kembali</button>
                <button type="submit" class="btn bg-gradient-primary">Simpan Perubahan</button>
            </div>
        </div>
    </div>
</form>

<script>
    $(document).ready(function() {
        $("#form-edit-profile").validate({
            rules: {
                // username: {
                //     required: true,
                //     maxlength: 50
                // },
                nama_admin: {
                    required: true,
                    maxlength: 200
                },
                email: {
                    required: true,
                    email: true
                }
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
                            tableAdmin.ajax.reload();
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