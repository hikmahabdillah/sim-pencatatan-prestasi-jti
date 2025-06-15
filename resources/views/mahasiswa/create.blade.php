<!-- Modal -->
<form action="{{ url('/mahasiswa/store') }}" method="POST" id="form-tambah">
    @csrf
    <div id="modal-master" class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Mahasiswa</h5>
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
                                placeholder="Masukkan NIM" required>
                            <div id="error-nim" class="text-danger error-text"></div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="nama" class="form-label">Nama Lengkap</label>
                            <input type="text" id="nama" name="nama" class="form-control"
                                placeholder="Masukkan nama lengkap" required>
                            <div id="error-nama" class="text-danger error-text"></div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="angkatan" class="form-label">Angkatan</label>
                            <input type="number" id="angkatan" name="angkatan" class="form-control"
                                placeholder="Masukkan tahun angkatan" required>
                            <div id="error-angkatan" class="text-danger error-text"></div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" id="email" name="email" class="form-control"
                                placeholder="Masukkan email" required>
                            <div id="error-email" class="text-danger error-text"></div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="no_hp" class="form-label">No. HP</label>
                            <input type="number" id="no_hp" name="no_hp" class="form-control"
                                placeholder="Masukkan nomor HP" required>
                            <div id="error-no_hp" class="text-danger error-text"></div>
                        </div>
                        <div class="form-group mb-3">
                            <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                            <input type="date" id="tanggal_lahir" name="tanggal_lahir" class="form-control"
                                max="2008-12-31" required>
                            <div id="error-tanggal_lahir" class="text-danger error-text"></div>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="col-md-6">

                        <div class="form-group mb-3">
                            <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                            <select id="jenis_kelamin" name="jenis_kelamin" class="form-control" required>
                                <option value="">Pilih Jenis Kelamin</option>
                                <option value="L">Laki-laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                            <div id="error-jenis_kelamin" class="text-danger error-text"></div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="id_prodi" class="form-label">Program Studi</label>
                            <select id="id_prodi" name="id_prodi" class="form-control" required>
                                <option value="">Pilih Program Studi</option>
                                @foreach ($prodi as $p)
                                    <option value="{{ $p->id_prodi }}">{{ $p->nama_prodi }}</option>
                                @endforeach
                            </select>
                            <div id="error-id_prodi" class="text-danger error-text"></div>
                        </div>
                        <div class="form-group mb-3">
                            <label for="alamat" class="form-label">Alamat</label>
                            <textarea id="alamat" name="alamat" class="form-control" placeholder="Masukkan alamat" rows="3" required></textarea>
                            <div id="error-alamat" class="text-danger error-text"></div>
                        </div>
                    </div>
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
        $("#form-tambah").validate({
            rules: {
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
            },
            messages: {
                angkatan: {
                    min: "Tahun angkatan minimal 2000",
                    max: "Tahun angkatan maksimal {{ date('Y') }}"
                }
            },
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
