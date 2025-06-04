<!-- Modal -->
<form action="{{ url('/mahasiswa/' . $data->id_mahasiswa . '/update') }}" method="POST" id="form-edit">
    @csrf
    @method('PUT')
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Data Mahasiswa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <!-- Left Column -->
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="nim" class="form-label">NIM</label>
                            <input type="text" id="nim" name="nim" class="form-control"
                                value="{{ $data->nim }}">
                            <div id="error-nim" class="text-danger error-text"></div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="nama" class="form-label">Nama Lengkap</label>
                            <input type="text" id="nama" name="nama" class="form-control"
                                value="{{ $data->nama }}" required>
                            <div id="error-nama" class="text-danger error-text"></div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="angkatan" class="form-label">Angkatan</label>
                            <input type="number" id="angkatan" name="angkatan" class="form-control"
                                value="{{ $data->angkatan }}" required>
                            <div id="error-angkatan" class="text-danger error-text"></div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" id="email" name="email" class="form-control"
                                value="{{ $data->email }}" required>
                            <div id="error-email" class="text-danger error-text"></div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="no_hp" class="form-label">No. HP</label>
                            <input type="number" id="no_hp" name="no_hp" class="form-control"
                                value="{{ $data->no_hp }}" required>
                            <div id="error-no_hp" class="text-danger error-text"></div>
                        </div>
                        <div class="form-group mb-3">
                            <label for="alamat" class="form-label">Alamat</label>
                            <textarea id="alamat" name="alamat" class="form-control" required>{{ $data->alamat }}</textarea>
                            <div id="error-alamat" class="text-danger error-text"></div>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="col-md-6">

                        <div class="form-group mb-3">
                            <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                            <input type="date" id="tanggal_lahir" name="tanggal_lahir" class="form-control"
                                value="{{ $data->tanggal_lahir }}" required>
                            <div id="error-tanggal_lahir" class="text-danger error-text"></div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                            <select id="jenis_kelamin" name="jenis_kelamin" class="form-control" required>
                                <option value="L" {{ $data->jenis_kelamin == 'L' ? 'selected' : '' }}>Laki-laki
                                </option>
                                <option value="P" {{ $data->jenis_kelamin == 'P' ? 'selected' : '' }}>Perempuan
                                </option>
                            </select>
                            <div id="error-jenis_kelamin" class="text-danger error-text"></div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="id_prodi" class="form-label">Program Studi</label>
                            <select id="id_prodi" name="id_prodi" class="form-control" required>
                                @foreach ($prodi as $p)
                                    <option value="{{ $p->id_prodi }}"
                                        {{ $p->id_prodi == $data->id_prodi ? 'selected' : '' }}>
                                        {{ $p->nama_prodi }}
                                    </option>
                                @endforeach
                            </select>
                            <div id="error-id_prodi" class="text-danger error-text"></div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="minat_bakat" class="form-label">Minat Bakat (Maksimal 3)</label>
                            <select id="minat_bakat" name="minat_bakat[]" class="form-control select2"
                                multiple="multiple" required>
                                @foreach ($kategori as $k)
                                    <option value="{{ $k->id_kategori }}"
                                        {{ in_array($k->id_kategori, $data->pengguna->minatBakat->pluck('id_kategori')->toArray()) ? 'selected' : '' }}>
                                        {{ $k->nama_kategori }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">Pilih 1-3 minat bakat</small>
                            <div id="error-minat_bakat" class="text-danger error-text"></div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="status_aktif" class="form-label">Status Akun</label>
                            <select id="status_aktif" name="status_aktif" class="form-control" required>
                                <option value="1" {{ $data->pengguna->status_aktif ? 'selected' : '' }}>Aktif
                                </option>
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
            placeholder: "Pilih Minat Bakat",
            allowClear: true,
            maximumSelectionLength: 3
        });

        $('#status_aktif').change(function() {
            if ($(this).val() === '0') {
                $('#keterangan-nonaktif-group').show();
                $('#keterangan_nonaktif').prop('required', true);
            } else {
                $('#keterangan-nonaktif-group').hide();
                $('#keterangan_nonaktif').prop('required', false);
            }
        });

        $("#form-edit").validate({
            rules: {
                'minat_bakat[]': {
                    required: true,
                    minlength: 1,
                    maxlength: 3
                },
                nim: {
                    required: true,
                    maxlength: 20
                },
                nama: {
                    required: true,
                    maxlength: 200
                },
                angkatan: {
                    required: true,
                    digits: true,
                    min: 2000,
                    max: {{ date('Y') }}
                },
                status_aktif: {
                    required: true
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
                jenis_kelamin: {
                    required: true
                },
                id_prodi: {
                    required: true
                },
                id_kategori: {
                    required: true
                },
                keterangan_nonaktif: {
                    required: function() {
                        return $('#status_aktif').val() === '0';
                    }
                }
            },
            messages: {
                angkatan: {
                    min: "Tahun angkatan minimal 2000",
                    max: "Tahun angkatan maksimal {{ date('Y') }}"
                },
                keterangan_nonaktif: {
                    required: "Keterangan nonaktif wajib diisi ketika status nonaktif"
                },
                'minat_bakat[]': {
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
                            tableMahasiswa.ajax.reload();
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
