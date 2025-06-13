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
                    <label for="bidang_keahlian" class="form-label">Bidang Keahlian (Maksimal 3)</label>
                    <select id="bidang_keahlian" name="bidang_keahlian[]" class="form-control select2"
                        multiple="multiple" required>
                        @foreach ($kategori as $k)
                            <option value="{{ $k->id_kategori }}"
                                {{ in_array($k->id_kategori, $data->pengguna->minatBakat->pluck('id_kategori')->toArray()) ? 'selected' : '' }}>
                                {{ $k->nama_kategori }}
                            </option>
                        @endforeach
                    </select>
                    <small class="text-muted">Pilih 1-3 Bidang Keahlian</small>
                    <div id="error-bidang_keahlian" class="text-danger error-text"></div>
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
        $('.select2').select2({
            placeholder: "Pilih Bidang Keahlian",
            allowClear: true,
            maximumSelectionLength: 3
        });

        $("#form-edit").validate({
            rules: {
                'bidang_keahlian[]': {
                    required: true,
                    minlength: 1,
                    maxlength: 3
                },
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
                }
            },
            messages: {
                'bidang_keahlian[]': {
                    required: "Pilih minimal satu minat bakat",
                    minlength: "Pilih minimal satu minat bakat",
                    maxlength: "Maksimal memilih 3 minat bakat"
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
