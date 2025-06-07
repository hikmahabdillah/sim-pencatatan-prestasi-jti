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
                    <input type="text" name="nama_lomba" class="form-control" required>
                    <div class="text-danger error-text" id="error-nama_lomba"></div>
                </div>

                <!-- Penyelenggara -->
                <div class="form-group col-md-6">
                    <label for="penyelenggara">Penyelenggara</label>
                    <input type="text" name="penyelenggara" class="form-control" required>
                    <div class="text-danger error-text" id="error-penyelenggara"></div>
                </div>

                <!-- Kategori -->
                <div class="form-group col-md-6">
                    <label for="id_kategori">Kategori</label>
                    <select name="id_kategori" class="form-control" required>
                        <option value="">-- Pilih Kategori --</option>
                        @foreach ($id_kategori as $k)
                        <option value="{{ $k->id_kategori }}">{{ $k->nama_kategori }}</option>
                        @endforeach
                    </select>
                    <div class="text-danger error-text" id="error-id_kategori"></div>
                </div>

                <!-- Tingkat Prestasi -->
                <div class="form-group col-md-6">
                    <label for="id_tingkat_prestasi">Tingkat Prestasi</label>
                    <select name="id_tingkat_prestasi" class="form-control" required>
                        <option value="">-- Pilih Tingkat Prestasi --</option>
                        @foreach ($id_tingkat_prestasi as $t)
                        <option value="{{ $t->id_tingkat_prestasi }}">{{ $t->nama_tingkat_prestasi }}</option>
                        @endforeach
                    </select>
                    <div class="text-danger error-text" id="error-id_tingkat_prestasi"></div>
                </div>

                <!-- Deskripsi -->
                <div class="form-group col-md-12">
                    <label for="deskripsi">Deskripsi</label>
                    <textarea name="deskripsi" class="form-control" required></textarea>
                    <div class="text-danger error-text" id="error-deskripsi"></div>
                </div>

                <!-- Periode -->
                <div class="form-group col-md-4">
                    <label for="periode">Periode</label>
                    <select name="periode" class="form-select" required>
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
                    <select name="biaya_pendaftaran" class="form-select" required>
                        <option value="">-- Pilih Biaya --</option>
                        <option value="1">Berbayar</option>
                        <option value="0">Gratis</option>
                    </select>
                    <div class="text-danger error-text" id="error-biaya_pendaftaran"></div>
                </div>

                <!-- Berhadiah -->
                <div class="form-group col-md-4">
                    <label for="berhadiah">Berhadiah</label>
                    <select name="berhadiah" class="form-select" required>
                        <option value="">-- Pilih Opsi --</option>
                        <option value="1">Berhadiah</option>
                        <option value="0">Tidak Berhadiah</option>
                    </select>
                    <div class="text-danger error-text" id="error-berhadiah"></div>
                </div>

                <!-- Link Pendaftaran -->
                <div class="form-group col-md-4">
                    <label for="link_pendaftaran">Link Pendaftaran</label>
                    <input type="url" name="link_pendaftaran" class="form-control">
                    <div class="text-danger error-text" id="error-link_pendaftaran"></div>
                </div>

                <!-- Tanggal Mulai -->
                <div class="form-group col-md-4">
                    <label for="tanggal_mulai">Tanggal Mulai</label>
                    <input type="date" name="tanggal_mulai" class="form-control" required>
                    <div class="text-danger error-text" id="error-tanggal_mulai"></div>
                </div>

                <!-- Tanggal Selesai -->
                <div class="form-group col-md-4">
                    <label for="tanggal_selesai">Tanggal Selesai</label>
                    <input type="date" name="tanggal_selesai" class="form-control" required>
                    <div class="text-danger error-text" id="error-tanggal_selesai"></div>
                </div>

                <!-- Deadline Pendaftaran -->
                <div class="form-group col-md-4">
                    <label for="deadline_pendaftaran">Deadline Pendaftaran</label>
                    <input type="date" name="deadline_pendaftaran" class="form-control" required>
                    <div class="text-danger error-text" id="error-deadline_pendaftaran"></div>
                </div>

                <!-- Foto -->
                <div class="form-group col-md-8">
                    <label for="foto">Foto Lomba</label>
                    <input type="file" name="foto" class="form-control" accept=".jpg,.jpeg,.png">
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
        $('#form-tambah-lomba').on('submit', function(e) {
            e.preventDefault();
            var formData = new FormData(this);

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
                            $('#error-' + key).text(value[0]);
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