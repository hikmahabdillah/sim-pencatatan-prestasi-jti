<!-- Modal Tambah Lomba -->
<form action="{{ url('/lomba/store') }}" method="POST" id="form-tambah-lomba" enctype="multipart/form-data">
    @csrf
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content" id="modalTambahLomba">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Lomba</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body row">
                <!-- Nama Lomba -->
                <div class="form-group col-md-6">
                    <label for="nama_lomba">Nama Lomba</label>
                    <input type="text" name="nama_lomba" class="form-control">
                    <div class="text-danger error-text" id="error-nama_lomba"></div>
                </div>

                <!-- Penyelenggara -->
                <div class="form-group col-md-6">
                    <label for="penyelenggara">Penyelenggara</label>
                    <input type="text" name="penyelenggara" class="form-control">
                    <div class="text-danger error-text" id="error-penyelenggara"></div>
                </div>

                <!-- Kategori -->
                <div class="form-group col-md-6">
                    <label for="id_kategori" class="form-label">Kategori (Maksimal 3)</label>
                    <select name="id_kategori[]" id="id_kategori" class="form-control select2" multiple>
                        @foreach ($id_kategori as $k)
                        <option value="{{ $k->id_kategori }}">{{ $k->nama_kategori }}</option>
                        @endforeach
                    </select>
                    <small class="text-muted">Pilih 1-3 kategori lomba</small>
                    <div class="text-danger error-text" id="error-id_kategori"></div>
                </div>

                <!-- Tingkat Prestasi -->
                <div class="form-group col-md-6">
                    <label for="id_tingkat_prestasi">Tingkat Prestasi</label>
                    <select name="id_tingkat_prestasi" class="form-control">
                        <option value="">-- Pilih Tingkat Prestasi --</option>
                        @foreach ($id_tingkat_prestasi as $t)
                        <option value="{{ $t->id_tingkat_prestasi }}">{{ $t->nama_tingkat_prestasi }}</option>
                        @endforeach
                    </select>
                    <div class="text-danger error-text" id="error-id_tingkat_prestasi"></div>
                </div>

                <!-- Deskripsi -->
                <div class="form-group col-md-8">
                    <label for="deskripsi">Deskripsi</label>
                    <textarea name="deskripsi" class="form-control"></textarea>
                    <div class="text-danger error-text" id="error-deskripsi"></div>
                </div>

                <!-- Periode -->
                <div class="form-group col-md-4">
                    <label for="periode">Periode</label>
                    <select name="periode" class="form-select">
                        <option value="">-- Pilih Periode --</option>
                        @foreach ($periode as $p)
                        <option value="{{ $p->id_periode }}">{{ $p->semester }} - {{ $p->tahun_ajaran }}</option>
                        @endforeach
                    </select>
                    <div class="text-danger error-text" id="error-periode"></div>
                </div>

                <!-- Biaya Pendaftaran -->
                <div class="form-group col-md-4">
                    <label for="biaya_pendaftaran">Biaya Pendaftaran</label>
                    <select name="biaya_pendaftaran" class="form-select">
                        <option value="">-- Pilih Biaya --</option>
                        <option value="1">Berbayar</option>
                        <option value="0">Gratis</option>
                    </select>
                    <div class="text-danger error-text" id="error-biaya_pendaftaran"></div>
                </div>

                <!-- Berhadiah -->
                <div class="form-group col-md-4">
                    <label for="berhadiah">Benefit Lomba</label>
                    <select name="berhadiah" class="form-select">
                        <option value="">-- Pilih Benefit --</option>
                        <option value="1">Berhadiah</option>
                        <option value="0">Tidak Berhadiah</option>
                    </select>
                    <div class="text-danger error-text" id="error-berhadiah"></div>
                </div>

                <!-- Tipe Lomba -->
                <div class="form-group col-md-4">
                    <label for="tipe_lomba">Tipe Lomba</label>
                    <select name="tipe_lomba" class="form-select">
                        <option value="">-- Pilih Tipe Lomba --</option>
                        <option value="individu">Individu</option>
                        <option value="tim">Tim</option>
                    </select>
                    <div class="text-danger error-text" id="error-tipe_lomba"></div>
                </div>

                <!-- Link Pendaftaran -->
                <div class="form-group col-md-4">
                    <label for="link_pendaftaran">Link Pendaftaran (Opsional)</label>
                    <input type="url" name="link_pendaftaran" class="form-control">
                    <div class="text-danger error-text" id="error-link_pendaftaran"></div>
                </div>

                <!-- Tanggal Mulai -->
                <div class="form-group col-md-4">
                    <label for="tanggal_mulai">Tanggal Mulai</label>
                    <input type="date" name="tanggal_mulai" class="form-control">
                    <div class="text-danger error-text" id="error-tanggal_mulai"></div>
                </div>

                <!-- Tanggal Selesai -->
                <div class="form-group col-md-4">
                    <label for="tanggal_selesai">Tanggal Selesai</label>
                    <input type="date" name="tanggal_selesai" class="form-control">
                    <div class="text-danger error-text" id="error-tanggal_selesai"></div>
                </div>

                <!-- Deadline Pendaftaran -->
                <div class="form-group col-md-4">
                    <label for="deadline_pendaftaran">Deadline Pendaftaran</label>
                    <input type="date" name="deadline_pendaftaran" class="form-control">
                    <div class="text-danger error-text" id="error-deadline_pendaftaran"></div>
                </div>

                <!-- Foto -->
                <div class="form-group col-md-8">
                    <label for="foto">Foto Lomba</label>
                    <input type="file" name="foto" class="form-control" accept=".jpg,.jpeg,.png">
                    <small class="form-text text-muted">
                        Format yang diperbolehkan: .jpg, .jpeg, .png. Maksimal ukuran file: 2MB.
                    </small>
                    <div class="text-danger error-text" id="error-foto"></div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-primary">Simpan Lomba</button>
            </div>
        </div>
    </div>
</form>

<!-- Script AJAX -->
<script>
    $(document).ready(function() {

        // Inisialisasi Select2 untuk kategori
        $('#id_kategori').select2({
            placeholder: "Pilih kategori lomba",
            allowClear: true,
            maximumSelectionLength: 3
        });

        $('#form-tambah-lomba').on('submit', function(e) {
            e.preventDefault();
            var formData = new FormData(this);

            // Ambil semua kategori dari Select2 dan tambahkan satu per satu ke FormData
            var kategoriTerpilih = $('#id_kategori').val(); 
            if (kategoriTerpilih) {
                kategoriTerpilih.forEach(function(value, index) {
                    formData.append('id_kategori[' + index + ']', value);
                });
            }

            // Reset error text
            $('.error-text').text('');

            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: formData,
                dataType: 'json',
                contentType: false,
                processData: false,
                success: function(response) {
                    if (response.status) {
                        $('#modalTambahLomba').closest('.modal').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: response.message
                        });
                        location.reload();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: response.message
                        });
                    }
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;

                        $.each(errors, function(key, value) {
                            let message = '';
                            let field = key.replace(/\.\d+$/, ''); //untuk menangani array key seperti id_kategori.0

                            switch (field) {
                                case 'nama_lomba':
                                    message = 'Nama lomba wajib diisi dan maksimal 255 karakter.';
                                    break;
                                case 'penyelenggara':
                                    message = 'Penyelenggara wajib diisi dan maksimal 255 karakter.';
                                    break;
                                case 'id_kategori':
                                    message = 'Minimal satu kategori harus dipilih.';
                                    break;
                                case 'id_tingkat_prestasi':
                                    message = 'Tingkat prestasi harus dipilih.';
                                    break;
                                case 'deskripsi':
                                    message = 'Deskripsi wajib diisi.';
                                    break;
                                case 'periode':
                                    message = 'Periode harus dipilih.';
                                    break;
                                case 'link_pendaftaran':
                                    message = 'Link pendaftaran harus berupa URL yang valid.';
                                    break;
                                case 'biaya_pendaftaran':
                                    message = 'Silakan pilih apakah lomba ini berbayar atau tidak.';
                                    break;
                                case 'berhadiah':
                                    message = 'Silakan pilih apakah lomba ini berhadiah atau tidak.';
                                    break;
                                case 'tipe_lomba':
                                    message = 'Tipe lomba harus dipilih (Individu atau Tim).';
                                    break;
                                case 'tanggal_mulai':
                                    message = 'Tanggal mulai wajib diisi dan harus berupa tanggal yang valid.';
                                    break;
                                case 'tanggal_selesai':
                                    message = 'Tanggal selesai harus diisi dan tidak boleh lebih awal dari tanggal mulai.';
                                    break;
                                case 'deadline_pendaftaran':
                                    message = 'Deadline pendaftaran harus sebelum atau sama dengan tanggal mulai.';
                                    break;
                                case 'foto':
                                    message = 'Foto wajib diunggah (format: jpg/jpeg/png, maks 2MB).';
                                    break;
                                default:
                                    message = value[0]; // fallback
                            }

                            $('#error-' + field).text(message);
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Terjadi kesalahan pada server.'
                        });
                    }
                }
            });
        });
    });
</script>