<!-- Modal -->
<form action="{{ url('/dospem/' . $data->id_dospem . '/update') }}" method="POST" id="form-edit">
    @csrf
    @method('PUT')
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Dosen Pembimbing</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="nip" class="form-label">NIP</label>
                    <input type="text" id="nip" name="nip" class="form-control" value="{{ $data->nip }}"
                        required>
                    <div id="error-nip" class="text-danger error-text"></div>
                </div>
                <div class="form-group">
                    <label for="nama" class="form-label">Nama Lengkap</label>
                    <input type="text" id="nama" name="nama" class="form-control" value="{{ $data->nama }}"
                        required>
                    <div id="error-nama" class="text-danger error-text"></div>
                </div>
                <div class="form-group">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" id="email" name="email" class="form-control" value="{{ $data->email }}"
                        required>
                    <div id="error-email" class="text-danger error-text"></div>
                </div>
                <div class="form-group">
                    <label for="id_prodi" class="form-label">Program Studi</label>
                    <select id="id_prodi" name="id_prodi" class="form-control" required>
                        @foreach ($prodi as $p)
                            <option value="{{ $p->id_prodi }}" {{ $p->id_prodi == $data->id_prodi ? 'selected' : '' }}>
                                {{ $p->nama_prodi }}
                            </option>
                        @endforeach
                    </select>
                    <div id="error-id_prodi" class="text-danger error-text"></div>
                </div>
                <div class="form-group mb-3">
                    <label for="newPassword" class="form-label">Password Baru</label>
                    <input type="password" id="newPassword" name="newPassword" class="form-control">
                    <div id="error-newPassword" class="text-danger error-text"></div>
                </div>
                <div class="form-group">
                    <label for="status_aktif" class="form-label">Status Akun</label>
                    <select id="status_aktif" name="status_aktif" class="form-control" required>
                        <option value="1" {{ $data->pengguna->status_aktif ? 'selected' : '' }}>Aktif</option>
                        <option value="0" {{ !$data->pengguna->status_aktif ? 'selected' : '' }}>Nonaktif
                        </option>
                    </select>
                    <div id="error-status_aktif" class="text-danger error-text"></div>
                </div>
                <div class="form-group mb-3" id="keterangan-nonaktif-group"
                    style="{{ $data->pengguna->status_aktif ? 'display: none;' : '' }}">
                    <label for="keterangan_nonaktif" class="form-label">Keterangan Nonaktif</label>
                    <textarea id="keterangan_nonaktif" name="keterangan_nonaktif" class="form-control"
                        {{ !$data->pengguna->status_aktif ? 'required' : '' }}>{{ $data->pengguna->keterangan_nonaktif }}</textarea>
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
        $('#status_aktif').change(function() {
            if ($(this).val() === '0') {
                $('#keterangan-nonaktif-group').show();
                $('#keterangan_nonaktif').attr('required', true);
            } else {
                $('#keterangan-nonaktif-group').hide();
                $('#keterangan_nonaktif').removeAttr('required');
            }
        });
        $("#form-edit").validate({
            rules: {
                nip: {
                    required: true,
                    maxlength: 20
                },
                nama: {
                    required: true,
                    maxlength: 200
                },
                newPassword: {
                    minlength: 6
                },
                status_aktif: {
                    required: true
                },
                email: {
                    required: true,
                    email: true
                },
                id_prodi: {
                    required: true
                },
                keterangan_nonaktif: {
                    required: function() {
                        return $('#status_aktif').val() === '0';
                    }
                }
            },
            messages: {
                keterangan_nonaktif: {
                    required: "Keterangan nonaktif wajib diisi ketika status nonaktif"
                },
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
                            tableDospem.ajax.reload();
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
                    },
                    error: function(xhr) {
                        // Handle error response
                        if (xhr.status === 422) {
                            var errors = xhr.responseJSON.errors;
                            $('.error-text').text('');
                            $.each(errors, function(prefix, val) {
                                $('#error-' + prefix).text(val[0]);
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
