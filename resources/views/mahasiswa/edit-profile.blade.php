<!-- Modal -->
<form action="{{ url('/mahasiswa/' . $data->id_mahasiswa . '/update-profile') }}" method="POST" id="form-edit-profile">
    @csrf
    @method('PUT')
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Data Mahasiswa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="nim" class="form-label">NIM</label>
                    <input type="text" id="nim" name="nim" class="form-control" value="{{ $data->nim }}"
                        readonly>
                </div>
                <div class="form-group">
                    <label for="nama" class="form-label">Nama Lengkap</label>
                    <input type="text" id="nama" name="nama" class="form-control" value="{{ $data->nama }}"
                        required>
                    <div id="error-nama" class="text-danger error-text"></div>
                </div>
                <div class="form-group">
                    <label for="angkatan" class="form-label">Angkatan</label>
                    <input type="number" id="angkatan" name="angkatan" class="form-control"
                        value="{{ $data->angkatan }}" disabled>
                    <div id="error-angkatan" class="text-danger error-text"></div>
                </div>
                <div class="form-group">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" id="email" name="email" class="form-control" value="{{ $data->email }}"
                        required>
                    <div id="error-email" class="text-danger error-text"></div>
                </div>
                <div class="form-group">
                    <label for="no_hp" class="form-label">No. HP</label>
                    <input type="text" id="no_hp" name="no_hp" class="form-control" value="{{ $data->no_hp }}"
                        required>
                    <div id="error-no_hp" class="text-danger error-text"></div>
                </div>
                <div class="form-group">
                    <label for="alamat" class="form-label">Alamat</label>
                    <textarea id="alamat" name="alamat" class="form-control" required>{{ $data->alamat }}</textarea>
                    <div id="error-alamat" class="text-danger error-text"></div>
                </div>
                <div class="form-group">
                    <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                    <input type="date" id="tanggal_lahir" name="tanggal_lahir" class="form-control"
                        value="{{ $data->tanggal_lahir }}" required>
                    <div id="error-tanggal_lahir" class="text-danger error-text"></div>
                </div>
                <div class="form-group">
                    <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                    <select id="jenis_kelamin" name="jenis_kelamin" class="form-control" disabled>
                        <option value="L" {{ $data->jenis_kelamin == 'L' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="P" {{ $data->jenis_kelamin == 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                    <div id="error-jenis_kelamin" class="text-danger error-text"></div>
                </div>
                <div class="form-group">
                    <label for="id_prodi" class="form-label">Program Studi</label>
                    <select id="id_prodi" name="id_prodi" class="form-control" disabled>
                        @foreach ($prodi as $p)
                            <option value="{{ $p->id_prodi }}"
                                {{ $p->id_prodi == $data->id_prodi ? 'selected' : '' }}>
                                {{ $p->nama_prodi }}
                            </option>
                        @endforeach
                    </select>
                    <div id="error-id_prodi" class="text-danger error-text"></div>
                </div>
                <div class="form-group">
                    <label for="id_kategori" class="form-label">Minat Bakat</label>
                    <select id="id_kategori" name="id_kategori" class="form-control" required>
                        @foreach ($kategori as $k)
                            <option value="{{ $k->id_kategori }}"
                                {{ $k->id_kategori == $data->id_kategori ? 'selected' : '' }}>
                                {{ $k->nama_kategori }}
                            </option>
                        @endforeach
                    </select>
                    <div id="error-id_kategori" class="text-danger error-text"></div>
                </div>
                <div class="form-group">
                    <label for="status_aktif" class="form-label">Status Akun</label>
                    <select id="status_aktif" name="status_aktif" class="form-control" disabled>
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
        $("#form-edit-profile").validate({
            rules: {
                nama: {
                    required: true,
                    maxlength: 200
                },
                email: {
                    required: true,
                    email: true
                },
                no_hp: {
                    required: true,
                    maxlength: 20
                },
                alamat: {
                    required: true
                },
                tanggal_lahir: {
                    required: true,
                    date: true
                },
                id_kategori: {
                    required: true
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
                            setTimeout(() => {
                                window.location.reload();
                            }, 1000);
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
