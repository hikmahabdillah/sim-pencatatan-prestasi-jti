<!-- Modal -->
<form action="{{ url('/prestasi/store') }}" method="POST" id="form-tambah" enctype="multipart/form-data">
    @csrf
    <div id="modal-master" class="modal-dialog modal-dialog-centered w-100" style="max-width: 800px" role="document">
        <div class="modal-content w-100">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Prestasi Mahasiswa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="id_tingkat_prestasi" class="form-label">Tingkat Prestasi</label>
                            <select id="id_tingkat_prestasi" name="id_tingkat_prestasi" class="form-control text-dark"
                                required>
                                <option value="">Pilih Tingkat Prestasi</option>
                                @foreach ($tingkatPrestasi as $tingkat)
                                    <option value="{{ $tingkat->id_tingkat_prestasi }}">
                                        {{ $tingkat->nama_tingkat_prestasi }}
                                    </option>
                                @endforeach
                            </select>
                            <div id="error-id_tingkat_prestasi" class="text-danger error-text"></div>
                        </div>

                        <input type="hidden" name="id_mahasiswa" value="{{ auth()->user()->mahasiswa->id_mahasiswa }}">
                        <div class="form-group">
                            <label for="id_dospem" class="form-label">Dosen Pembimbing</label>
                            <select id="id_dospem" name="id_dospem" class="form-control">
                                <option value="">Pilih Dosen Pembimbing</option>
                                @foreach ($dosenPembimbing as $dosen)
                                    <option value="{{ $dosen->id_dospem }}">{{ $dosen->nama }}</option>
                                @endforeach
                            </select>
                            <div id="error-id_dospem" class="text-danger error-text"></div>
                        </div>

                        <div class="form-group">
                            <label for="id_kategori" class="form-label">Kategori</label>
                            <select id="id_kategori" name="id_kategori" class="form-control" required>
                                <option value="">Pilih Kategori</option>
                                @foreach ($kategori as $kat)
                                    <option value="{{ $kat->id_kategori }}">{{ $kat->nama_kategori }}</option>
                                @endforeach
                            </select>
                            <div id="error-id_kategori" class="text-danger error-text"></div>
                        </div>

                        <div class="form-group">
                            <label for="nama_prestasi" class="form-label">Nama Prestasi</label>
                            <input type="text" id="nama_prestasi" name="nama_prestasi" class="form-control"
                                placeholder="Masukkan nama prestasi" required>
                            <div id="error-nama_prestasi" class="text-danger error-text"></div>
                        </div>

                        <div class="form-group">
                            <label for="juara" class="form-label">Juara</label>
                            <input type="text" id="juara" name="juara" class="form-control"
                                placeholder="Masukkan juara yang diraih" required>
                            <div id="error-juara" class="text-danger error-text"></div>
                        </div>

                        <div class="form-group">
                            <label for="tanggal_prestasi" class="form-label">Tanggal Prestasi</label>
                            <input type="date" id="tanggal_prestasi" name="tanggal_prestasi" class="form-control"
                                required>
                            <div id="error-tanggal_prestasi" class="text-danger error-text"></div>
                        </div>
                        <div class="form-group">
                            <label for="id_periode" class="form-label">Periode</label>
                            <select id="id_periode" name="id_periode" class="form-control" required>
                                <option value="">Pilih Periode</option>
                                @foreach ($periode as $per)
                                    <option value="{{ $per->id_periode }}">{{ $per->semester }} -
                                        {{ $per->tahun_ajaran }}</option>
                                @endforeach
                            </select>
                            <div id="error-id_periode" class="text-danger error-text"></div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="keterangan" class="form-label">Keterangan</label>
                            <textarea id="keterangan" name="keterangan" class="form-control" placeholder="Masukkan keterangan tambahan"
                                rows="3"></textarea>
                            <div id="error-keterangan" class="text-danger error-text"></div>
                        </div>

                        <div class="form-group">
                            <label for="foto_kegiatan" class="form-label">Foto Kegiatan</label>
                            <input type="file" id="foto_kegiatan" name="foto_kegiatan" class="form-control">
                            <small class="text-muted">Format: jpeg, png, jpg, gif (max 2MB)</small>
                            <div id="error-foto_kegiatan" class="text-danger error-text"></div>
                        </div>

                        <div class="form-group">
                            <label for="bukti_sertifikat" class="form-label">Bukti Sertifikat</label>
                            <input type="file" id="bukti_sertifikat" name="bukti_sertifikat"
                                class="form-control">
                            <small class="text-muted">Format: pdf, jpeg, png, jpg (max 2MB)</small>
                            <div id="error-bukti_sertifikat" class="text-danger error-text"></div>
                        </div>

                        <div class="form-group">
                            <label for="surat_tugas" class="form-label">Surat Tugas</label>
                            <input type="file" id="surat_tugas" name="surat_tugas" class="form-control">
                            <small class="text-muted">Format: pdf (max 2MB)</small>
                            <div id="error-surat_tugas" class="text-danger error-text"></div>
                        </div>

                        <div class="form-group">
                            <label for="karya" class="form-label">Karya</label>
                            <input type="file" id="karya" name="karya" class="form-control">
                            <small class="text-muted">Format: pdf, doc, docx, ppt, pptx (max 5MB)</small>
                            <div id="error-karya" class="text-danger error-text"></div>
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
        $("#form-tambah").validate({
            rules: {
                id_tingkat_prestasi: {
                    required: true
                },
                id_mahasiswa: {
                    required: true
                },
                id_kategori: {
                    required: true
                },
                nama_prestasi: {
                    required: true,
                    maxlength: 255
                },
                juara: {
                    required: true,
                    maxlength: 100
                },
                tanggal_prestasi: {
                    required: true,
                    date: true
                },
                id_periode: {
                    required: true
                },
                foto_kegiatan: {
                    accept: "image/jpeg,image/png,image/jpg,image/gif",
                    filesize: 2
                },
                bukti_sertifikat: {
                    accept: "application/pdf,image/jpeg,image/png,image/jpg",
                    filesize: 2
                },
                surat_tugas: {
                    accept: "application/pdf",
                    filesize: 2
                },
                karya: {
                    accept: "application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/vnd.ms-powerpoint,application/vnd.openxmlformats-officedocument.presentationml.presentation",
                    filesize: 5
                }
            },
            messages: {
                id_tingkat_prestasi: {
                    required: "Tingkat prestasi wajib dipilih"
                },
                id_mahasiswa: {
                    required: "Mahasiswa wajib dipilih"
                },
                id_kategori: {
                    required: "Kategori wajib dipilih"
                },
                nama_prestasi: {
                    required: "Nama prestasi wajib diisi",
                    maxlength: "Maksimal 255 karakter"
                },
                juara: {
                    required: "Juara wajib diisi",
                    maxlength: "Maksimal 100 karakter"
                },
                tanggal_prestasi: {
                    required: "Tanggal prestasi wajib diisi",
                    date: "Format tanggal tidak valid"
                },
                id_periode: {
                    required: "Periode wajib dipilih"
                },
                foto_kegiatan: {
                    accept: "Hanya file gambar (JPEG, PNG, JPG, GIF) yang diperbolehkan",
                    filesize: "Ukuran file maksimal 2MB"
                },
                bukti_sertifikat: {
                    accept: "Hanya file PDF atau gambar yang diperbolehkan",
                    filesize: "Ukuran file maksimal 2MB"
                },
                surat_tugas: {
                    accept: "Hanya file PDF yang diperbolehkan",
                    filesize: "Ukuran file maksimal 2MB"
                },
                karya: {
                    accept: "Hanya file PDF, DOC, DOCX, PPT, atau PPTX yang diperbolehkan",
                    filesize: "Ukuran file maksimal 5MB"
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
                                    'Data berhasil disimpan'
                            });
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
