<!-- Modal -->
<form action="{{ url('/prestasi/' . $prestasi->id_prestasi . '/update-prestasi') }}" method="POST" id="form-edit"
    enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Prestasi Mahasiswa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nama_prestasi" class="form-label">Nama Prestasi</label>
                            <input value="{{ $prestasi->nama_prestasi }}" type="text" id="nama_prestasi"
                                name="nama_prestasi" class="form-control" required>
                            <div id="error-nama_prestasi" class="text-danger error-text"></div>
                        </div>

                        <div class="form-group">
                            <label for="id_tingkat_prestasi" class="form-label">Tingkat Prestasi</label>
                            <select id="id_tingkat_prestasi" name="id_tingkat_prestasi" class="form-control" required>
                                @foreach ($tingkatPrestasi as $tingkat)
                                    <option value="{{ $tingkat->id_tingkat_prestasi }}"
                                        {{ $prestasi->id_tingkat_prestasi == $tingkat->id_tingkat_prestasi ? 'selected' : '' }}>
                                        {{ $tingkat->nama_tingkat_prestasi }}
                                    </option>
                                @endforeach
                            </select>
                            <div id="error-id_tingkat_prestasi" class="text-danger error-text"></div>
                        </div>

                        <div class="form-group">
                            <label for="id_kategori" class="form-label">Kategori</label>
                            <select id="id_kategori" name="id_kategori" class="form-control" required>
                                @foreach ($kategori as $kat)
                                    <option value="{{ $kat->id_kategori }}"
                                        {{ $prestasi->id_kategori == $kat->id_kategori ? 'selected' : '' }}>
                                        {{ $kat->nama_kategori }}
                                    </option>
                                @endforeach
                            </select>
                            <div id="error-id_kategori" class="text-danger error-text"></div>
                        </div>

                        <div class="form-group">
                            <label for="juara" class="form-label">Juara</label>
                            <input value="{{ $prestasi->juara }}" type="text" id="juara" name="juara"
                                class="form-control" required>
                            <div id="error-juara" class="text-danger error-text"></div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="tanggal_prestasi" class="form-label">Tanggal Prestasi</label>
                            <input value="{{ $prestasi->tanggal_prestasi }}" type="date" id="tanggal_prestasi"
                                name="tanggal_prestasi" class="form-control" required max="">
                            <div id="error-tanggal_prestasi" class="text-danger error-text"></div>
                        </div>

                        <div class="form-group">
                            <label for="id_periode" class="form-label">Periode</label>
                            <select id="id_periode" name="id_periode" class="form-control" required>
                                @foreach ($periode as $per)
                                    <option value="{{ $per->id_periode }}"
                                        {{ $prestasi->id_periode == $per->id_periode ? 'selected' : '' }}>
                                        {{ $per->semester }} - {{ $per->tahun_ajaran }}
                                    </option>
                                @endforeach
                            </select>
                            <div id="error-id_periode" class="text-danger error-text"></div>
                        </div>

                        <div class="form-group">
                            <label for="id_dospem" class="form-label">Dosen Pembimbing</label>
                            <select id="id_dospem" name="id_dospem" class="form-control">
                                <option value="">-- Pilih Dosen --</option>
                                @foreach ($dosenPembimbing as $dosen)
                                    <option value="{{ $dosen->id_dospem }}"
                                        {{ $prestasi->id_dospem == $dosen->id_dospem ? 'selected' : '' }}>
                                        {{ $dosen->nama }}
                                    </option>
                                @endforeach
                            </select>
                            <div id="error-id_dospem" class="text-danger error-text"></div>
                        </div>

                        <div class="form-group">
                            <label for="deskripsi" class="form-label">Deskripsi</label>
                            <textarea id="deskripsi" name="deskripsi" class="form-control">{{ $prestasi->deskripsi }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="foto_kegiatan" class="form-label">Foto Kegiatan</label>
                            <input type="file" id="foto_kegiatan" name="foto_kegiatan" class="form-control"
                                accept="image/jpeg,image/png,image/jpg,image/gif">
                            @if ($prestasi->foto_kegiatan)
                                <small class="text-muted">File saat ini: <a
                                        href="{{ asset('storage/' . $prestasi->foto_kegiatan) }}"
                                        target="_blank">Lihat</a></small>
                            @endif
                            <div id="error-foto_kegiatan" class="text-danger error-text"></div>
                        </div>

                        <div class="form-group">
                            <label for="bukti_sertifikat" class="form-label">Bukti Sertifikat</label>
                            <input type="file" id="bukti_sertifikat" name="bukti_sertifikat" class="form-control"
                                accept="application/pdf,image/jpeg,image/png,image/jpg">
                            @if ($prestasi->bukti_sertifikat)
                                <small class="text-muted">File saat ini: <a
                                        href="{{ asset('storage/' . $prestasi->bukti_sertifikat) }}"
                                        target="_blank">Lihat</a></small>
                            @endif
                            <div id="error-bukti_sertifikat" class="text-danger error-text"></div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="surat_tugas" class="form-label">Surat Tugas</label>
                            <input type="file" id="surat_tugas" name="surat_tugas" class="form-control"
                                accept="application/pdf">
                            @if ($prestasi->surat_tugas)
                                <small class="text-muted">File saat ini: <a
                                        href="{{ asset('storage/' . $prestasi->surat_tugas) }}"
                                        target="_blank">Lihat</a></small>
                            @endif
                            <div id="error-surat_tugas" class="text-danger error-text"></div>
                        </div>

                        <div class="form-group">
                            <label for="karya" class="form-label">Karya</label>
                            <input type="file" id="karya" name="karya" class="form-control"
                                accept="application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/vnd.ms-powerpoint,application/vnd.openxmlformats-officedocument.presentationml.presentation">
                            @if ($prestasi->karya)
                                <small class="text-muted">File saat ini: <a
                                        href="{{ asset('storage/' . $prestasi->karya) }}"
                                        target="_blank">Lihat</a></small>
                            @endif
                            <div id="error-karya" class="text-danger error-text"></div>
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
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('tanggal_prestasi').setAttribute('max', today);

        // Add custom validation method for file types
        $.validator.addMethod("accept", function(value, element, param) {
            // If no file is selected, skip validation (use 'required' if file is mandatory)
            if (value === "") return true;

            // Get the file extension
            var extension = value.split('.').pop().toLowerCase();

            // Check MIME type (for Chrome/Firefox) or extension (for IE)
            if (element.files && element.files[0]) {
                var fileType = element.files[0].type.toLowerCase();
                var validTypes = param.split(',');

                for (var i = 0; i < validTypes.length; i++) {
                    if (fileType.match(validTypes[i].replace('*', '').toLowerCase())) {
                        return true;
                    }
                }
            }

            // Check by extension
            var validExtensions = [];
            var types = param.split(',');

            types.forEach(function(type) {
                if (type.indexOf('/') > -1) {
                    // This is a MIME type, convert to extension
                    var parts = type.split('/');
                    if (parts[1] === 'jpeg' || parts[1] === 'jpg') {
                        validExtensions.push('jpg');
                        validExtensions.push('jpeg');
                    } else {
                        validExtensions.push(parts[1]);
                    }
                } else {
                    // This is already an extension
                    validExtensions.push(type.replace('.', ''));
                }
            });

            return $.inArray(extension, validExtensions) !== -1;
        }, "Format file tidak didukung");

        // Add custom validation method for file size
        $.validator.addMethod("filesize", function(value, element, param) {
            // If no file is selected, skip validation
            if (value === "" || !element.files || element.files.length === 0) return true;

            var size = element.files[0].size;
            var maxSize = param * 1024 * 1024; // Convert MB to bytes

            return size <= maxSize;
        }, "Ukuran file terlalu besar");

        // Initialize validation
        $("#form-edit").validate({
            rules: {
                nama_prestasi: {
                    required: true,
                    maxlength: 255
                },
                id_tingkat_prestasi: {
                    required: true
                },
                id_kategori: {
                    required: true
                },
                juara: {
                    required: true,
                    maxlength: 50
                },
                tanggal_prestasi: {
                    required: true,
                    date: true,
                    max: today
                },
                id_periode: {
                    required: true
                },
                foto_kegiatan: {
                    accept: "image/jpeg,image/png,image/jpg",
                    filesize: 2
                },
                bukti_sertifikat: {
                    accept: "application/pdf,image/jpeg,image/png,image/jpg",
                    filesize: 5
                },
                surat_tugas: {
                    accept: "application/pdf,image/jpeg,image/png,image/jpg",
                    filesize: 5
                },
                karya: {
                    accept: "application/pdf,application/zip,application/x-rar-compressed",
                    filesize: 10
                }
            },
            messages: {
                nama_prestasi: {
                    required: "Nama prestasi wajib diisi",
                    maxlength: "Maksimal 255 karakter"
                },
                id_tingkat_prestasi: {
                    required: "Tingkat prestasi wajib dipilih"
                },
                id_kategori: {
                    required: "Kategori wajib dipilih"
                },
                juara: {
                    required: "Juara wajib diisi",
                    maxlength: "Maksimal 50 karakter"
                },
                tanggal_prestasi: {
                    required: "Tanggal prestasi wajib diisi",
                    date: "Format tanggal tidak valid",
                    max: "Tanggal prestasi tidak boleh melebihi hari ini"
                },
                id_periode: {
                    required: "Periode wajib dipilih"
                },
                foto_kegiatan: {
                    accept: "Hanya file gambar (JPEG, PNG, JPG) yang diperbolehkan",
                    filesize: "Ukuran file maksimal 2MB"
                },
                bukti_sertifikat: {
                    accept: "Hanya file PDF atau gambar yang diperbolehkan",
                    filesize: "Ukuran file maksimal 5MB"
                },
                surat_tugas: {
                    accept: "Hanya file PDF atau gambar yang diperbolehkan",
                    filesize: "Ukuran file maksimal 5MB"
                },
                karya: {
                    accept: "Hanya file PDF atau arsip (ZIP, RAR) yang diperbolehkan",
                    filesize: "Ukuran file maksimal 10MB"
                }
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
            submitHandler: function(form, event) {
                event.preventDefault();
                var formData = new FormData(form);

                // Show loading indicator
                Swal.fire({
                    title: 'Memproses...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading()
                    }
                });

                $.ajax({
                    url: form.action,
                    type: form.method,
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        Swal.close();
                        if (response.status) {
                            $('#myModal').modal('hide');
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message ||
                                    'Data berhasil diperbarui'
                            })
                            window.location.href = response.redirect_url;
                        } else {
                            // Clear previous errors
                            $('.error-text').text('');

                            // Show validation errors if any
                            if (response.errors) {
                                $.each(response.errors, function(prefix, val) {
                                    $('#error-' + prefix).text(val[0]);
                                });
                            }

                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: response.message ||
                                    'Terjadi kesalahan saat menyimpan data'
                            });
                        }
                    },
                    error: function(xhr) {
                        Swal.close();
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: xhr.responseJSON?.message ||
                                'Terjadi kesalahan saat mengirim data'
                        });
                    }
                });
            }
        });
    });
</script>
