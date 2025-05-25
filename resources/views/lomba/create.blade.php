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
                <div class="form-group col-md-6">
                    <label for="nama_lomba">Nama Lomba</label>
                    <input type="text" name="nama_lomba" class="form-control" value="{{ old('nama_lomba') }}" required>
                    <div class="text-danger error-text" id="error-nama_lomba"></div>
                </div>

                <div class="form-group col-md-6">
                    <label for="penyelenggara">Penyelenggara</label>
                    <input type="text" name="penyelenggara" class="form-control" value="{{ old('penyelenggara') }}" required>
                    <div class="text-danger error-text" id="error-penyelenggara"></div>
                </div>

                <div class="form-group col-md-6">
                    <label for="id_kategori">Kategori</label>
                    <select name="id_kategori" class="form-control" required>
                        <option value="">-- Pilih Kategori --</option>
                        @foreach ($id_kategori as $k)
                            <option value="{{ $k->id_kategori }}" {{ old('id_kategori') == $k->id_kategori ? 'selected' : '' }}>
                                {{ $k->nama_kategori }}
                            </option>
                        @endforeach
                    </select>
                    <div class="text-danger error-text" id="error-id_kategori"></div>
                </div>

                <div class="form-group col-md-6">
                    <label for="id_tingkat_prestasi">Tingkat Prestasi</label>
                    <select name="id_tingkat_prestasi" class="form-control" required>
                        <option value="">-- Pilih Tingkat Prestasi --</option>
                        @foreach ($id_tingkat_prestasi as $t)
                            <option value="{{ $t->id_tingkat_prestasi }}" {{ old('id_tingkat_prestasi') == $t->id_tingkat_prestasi ? 'selected' : '' }}>
                                {{ $t->nama_tingkat_prestasi }}
                            </option>
                        @endforeach
                    </select>
                    <div class="text-danger error-text" id="error-id_tingkat_prestasi"></div>
                </div>

                <div class="form-group col-md-12">
                    <label for="deskripsi">Deskripsi</label>
                    <textarea name="deskripsi" class="form-control" required>{{ old('deskripsi') }}</textarea>
                    <div class="text-danger error-text" id="error-deskripsi"></div>
                </div>

                <div class="form-group col-md-4">
                    <label for="periode" class="form-label">Periode</label>
                    <select id="periode" name="periode" class="form-select" required>
                        <option value="">-- Pilih Periode --</option>
                        @foreach($periode as $p)
                            <option value="{{ $p->id_periode }}" {{ old('periode') == $p->id_periode ? 'selected' : '' }}>
                                {{ $p->semester }} - {{ $p->tahun_ajaran }}
                            </option>
                        @endforeach
                    </select>
                    <div id="error-periode" class="text-danger error-text"></div>
                </div>
                
                <div class="form-group col-md-4">
                    <label for="biaya_pendaftaran" class="form-label">Biaya Pendaftaran</label>
                    <select id="biaya_pendaftaran" name="biaya_pendaftaran" class="form-select" required>
                    <option value="" {{ old('biaya_pendaftaran') === null ? 'selected' : '' }}>-- Pilih Biaya Pendaftaran --</option>
                    <option value="1" {{ old('biaya_pendaftaran') === '1' ? 'selected' : '' }}>Berbayar</option>
                    <option value="0" {{ old('biaya_pendaftaran') === '0' ? 'selected' : '' }}>Gratis</option>
                    </select>
                    <div id="error-biaya_pendaftaran" class="text-danger error-text"></div>
                </div>

                <div class="form-group col-md-4">
                    <label for="link_pendaftaran">Link Pendaftaran</label>
                    <input type="url" name="link_pendaftaran" class="form-control" value="{{ old('link_pendaftaran') }}" required>
                    <div class="text-danger error-text" id="error-link_pendaftaran"></div>
                </div>

                <div class="form-group col-md-4">
                    <label for="tanggal_mulai">Tanggal Mulai</label>
                    <input type="date" name="tanggal_mulai" class="form-control" value="{{ old('tanggal_mulai') }}" required>
                    <div class="text-danger error-text" id="error-tanggal_mulai"></div>
                </div>

                <div class="form-group col-md-4">
                    <label for="tanggal_selesai">Tanggal Selesai</label>
                    <input type="date" name="tanggal_selesai" class="form-control" value="{{ old('tanggal_selesai') }}" required>
                    <div class="text-danger error-text" id="error-tanggal_selesai"></div>
                </div>

                <div class="form-group col-md-4">
                    <label for="deadline_pendaftaran">Deadline Pendaftaran</label>
                    <input type="date" name="deadline_pendaftaran" class="form-control" value="{{ old('deadline_pendaftaran') }}" required>
                    <div class="text-danger error-text" id="error-deadline_pendaftaran"></div>
                </div>

                <div class="form-group col-md-12">
                    <label for="foto">Foto Lomba (Opsional)</label>
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

<script>
     $(document).ready(function () {
        $('#form-tambah-lomba').on('submit', function(e) {
            e.preventDefault(); 

            var formData = new FormData(this);

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
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Terjadi kesalahan pada server.'
                    });
                }
            });
        });
    });
</script>