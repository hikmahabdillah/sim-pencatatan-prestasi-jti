<!-- Modal Edit Lomba -->
<form action="{{ url('/lomba/' . $lomba->id_lomba . '/update') }}" method="POST" id="form-edit" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            @if ($lomba && $lomba->status_verifikasi == 1)
            <div class="alert alert-warning text-center text-white">
                <strong>Lomba sudah diverifikasi, sehingga tidak dapat diedit.</strong>
            </div>
            @else
            <div class="modal-header">
                <h5 class="modal-title">Edit Data Lomba</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label>Nama Lomba</label>
                        <input type="text" name="nama_lomba" value="{{ $lomba->nama_lomba }}" class="form-control" required>
                        <div class="text-danger error-text" id="error-nama_lomba"></div>
                    </div>
                    <div class="col-md-6">
                        <label>Penyelenggara</label>
                        <input type="text" name="penyelenggara" value="{{ $lomba->penyelenggara }}" class="form-control" required>
                        <div class="text-danger error-text" id="error-penyelenggara"></div>
                    </div>
                    <div class="col-md-6">
                        <label>Kategori</label>
                        <select name="id_kategori" class="form-control">
                            @foreach ($id_kategori as $kategori)
                            <option value="{{ $kategori->id_kategori }}" {{ $kategori->id_kategori == $lomba->id_kategori ? 'selected' : '' }}>
                                {{ $kategori->nama_kategori }}
                            </option>
                            @endforeach
                        </select>
                        <div class="text-danger error-text" id="error-id_kategori"></div>
                    </div>
                    <div class="col-md-6">
                        <label>Tingkat Prestasi</label>
                        <select name="id_tingkat_prestasi" class="form-control">
                            @foreach ($id_tingkat_prestasi as $tingkat)
                            <option value="{{ $tingkat->id_tingkat_prestasi }}" {{ $tingkat->id_tingkat_prestasi == $lomba->id_tingkat_prestasi ? 'selected' : '' }}>
                                {{ $tingkat->nama_tingkat_prestasi }}
                            </option>
                            @endforeach
                        </select>
                        <div class="text-danger error-text" id="error-id_tingkat_prestasi"></div>
                    </div>
                    <div class="col-md-12">
                        <label>Deskripsi</label>
                        <textarea name="deskripsi" rows="3" class="form-control">{{ $lomba->deskripsi }}</textarea>
                        <div class="text-danger error-text" id="error-deskripsi"></div>
                    </div>
                    <div class="col-md-6">
                        <label>Periode</label>
                        <select name="periode" class="form-control">
                            @foreach ($id_periode as $periode)
                            <option value="{{ $periode->id_periode }}" {{ $periode->id_periode == $lomba->periode ? 'selected' : '' }}>
                                {{ $periode->semester }} - {{ $periode->tahun_ajaran }}
                            </option>
                            @endforeach
                        </select>
                        <div class="text-danger error-text" id="error-periode"></div>
                    </div>
                    <div class="col-md-6">
                        <label>Link Pendaftaran</label>
                        <input type="url" name="link_pendaftaran" value="{{ $lomba->link_pendaftaran }}" class="form-control">
                        <div class="text-danger error-text" id="error-link_pendaftaran"></div>
                    </div>
                    <div class="col-md-4">
                        <label>Biaya Pendaftaran</label>
                        <select name="biaya_pendaftaran" class="form-control">
                            <option value="1" {{ $lomba->biaya_pendaftaran ? 'selected' : '' }}>Berbayar</option>
                            <option value="0" {{ !$lomba->biaya_pendaftaran ? 'selected' : '' }}>Gratis</option>
                        </select>
                        <div class="text-danger error-text" id="error-biaya_pendaftaran"></div>
                    </div>
                    <div class="col-md-4">
                        <label>Berhadiah</label>
                        <select name="berhadiah" class="form-control">
                            <option value="1" {{ $lomba->berhadiah ? 'selected' : '' }}>Berhadiah</option>
                            <option value="0" {{ !$lomba->berhadiah ? 'selected' : '' }}>Tidak Berhadiah</option>
                        </select>
                        <div class="text-danger error-text" id="error-berhadiah"></div>
                    </div>
                    <div class="col-md-4">
                        <label>Tanggal Mulai</label>
                        <input type="date" name="tanggal_mulai" value="{{ $lomba->tanggal_mulai }}" class="form-control">
                        <div class="text-danger error-text" id="error-tanggal_mulai"></div>
                    </div>
                    <div class="col-md-4">
                        <label>Tanggal Selesai</label>
                        <input type="date" name="tanggal_selesai" value="{{ $lomba->tanggal_selesai }}" class="form-control">
                        <div class="text-danger error-text" id="error-tanggal_selesai"></div>
                    </div>
                    <div class="col-md-4">
                        <label>Deadline Pendaftaran</label>
                        <input type="date" name="deadline_pendaftaran" value="{{ $lomba->deadline_pendaftaran }}" class="form-control">
                        <div class="text-danger error-text" id="error-deadline_pendaftaran"></div>
                    </div>
                    <div class="col-md-4">
                        <label>Foto</label>
                        <input type="file" name="foto" class="form-control">
                        <div class="text-danger error-text" id="error-foto"></div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kembali</button>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </div>
        @endif
    </div>
</form>
<script>
    $(document).ready(function() {
        $(document).on('submit', '#form-edit', function(e) {
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
                        $('#myModal').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: response.message
                        });
                        setTimeout(() => {
                            location.reload();
                        }, 1000);
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