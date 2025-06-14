<form action="{{ url('/lomba/' . $lomba->id_lomba . '/update') }}" method="POST" id="form-edit"
    enctype="multipart/form-data">
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
                        <input type="text" name="nama_lomba" value="{{ $lomba->nama_lomba }}" class="form-control">
                        <div class="text-danger error-text" id="error-nama_lomba"></div>
                    </div>

                    <div class="col-md-6">
                        <label>Penyelenggara</label>
                        <input type="text" name="penyelenggara" value="{{ $lomba->penyelenggara }}" class="form-control">
                        <div class="text-danger error-text" id="error-penyelenggara"></div>
                    </div>

                    <div class="form-group col-md-6">
                        <label for="id_kategori" class="form-label">Kategori (Maksimal 3)</label>
                        <select name="id_kategori[]" id="id_kategori" class="form-control select2" multiple required>
                            @foreach ($id_kategori as $k)
                            <option value="{{ $k->id_kategori }}"
                                {{ in_array($k->id_kategori, $lomba->kategoris->pluck('id_kategori')->toArray()) ? 'selected' : '' }}>
                                {{ $k->nama_kategori }}
                            </option>
                            @endforeach
                        </select>
                        <small class="text-muted">Pilih 1-3 kategori lomba</small>
                        <div class="text-danger error-text" id="error-id_kategori"></div>
                    </div>

                    <div class="col-md-6">
                        <label>Tingkat Prestasi</label>
                        <select name="id_tingkat_prestasi" class="form-control">
                            @foreach ($id_tingkat_prestasi as $tingkat)
                            <option value="{{ $tingkat->id_tingkat_prestasi }}"
                                {{ $tingkat->id_tingkat_prestasi == $lomba->id_tingkat_prestasi ? 'selected' : '' }}>
                                {{ $tingkat->nama_tingkat_prestasi }}
                            </option>
                            @endforeach
                        </select>
                        <div class="text-danger error-text" id="error-id_tingkat_prestasi"></div>
                    </div>

                    <div class="col-md-8">
                        <label>Deskripsi</label>
                        <textarea name="deskripsi" rows="3" class="form-control">{{ $lomba->deskripsi }}</textarea>
                        <div class="text-danger error-text" id="error-deskripsi"></div>
                    </div>

                    <div class="col-md-4">
                        <label>Periode</label>
                        <select name="periode" class="form-control">
                            @foreach ($id_periode as $periode)
                            <option value="{{ $periode->id_periode }}"
                                {{ $periode->id_periode == $lomba->periode ? 'selected' : '' }}>
                                {{ $periode->semester }} - {{ $periode->tahun_ajaran }}
                            </option>
                            @endforeach
                        </select>
                        <div class="text-danger error-text" id="error-periode"></div>
                    </div>

                    <div class="col-md-5">
                        <label>Link Pendaftaran (Opsional)</label>
                        <input type="url" name="link_pendaftaran" value="{{ $lomba->link_pendaftaran }}"
                            class="form-control">
                        <div class="text-danger error-text" id="error-link_pendaftaran"></div>
                    </div>

                    <div class="col-md-3">
                        <label>Biaya Pendaftaran</label>
                        <select name="biaya_pendaftaran" class="form-control">
                            <option value="1" {{ $lomba->biaya_pendaftaran == 1 ? 'selected' : '' }}>Berbayar</option>
                            <option value="0" {{ $lomba->biaya_pendaftaran == 0 ? 'selected' : '' }}>Gratis</option>
                        </select>
                        <div class="text-danger error-text" id="error-biaya_pendaftaran"></div>
                    </div>

                    <div class="form-group col-md-4">
                        <label for="tipe_lomba">Tipe Lomba</label>
                        <select name="tipe_lomba" class="form-select">
                            <option value="">-- Pilih Tipe Lomba --</option>
                            <option value="individu" {{ $lomba->tipe_lomba == 'individu' ? 'selected' : '' }}>Individu</option>
                            <option value="tim" {{ $lomba->tipe_lomba == 'tim' ? 'selected' : '' }}>Tim</option>
                        </select>
                        <div class="text-danger error-text" id="error-tipe_lomba"></div>
                    </div>

                    <div class="col-md-4">
                        <label>Benefit Lomba</label>
                        <select name="berhadiah" class="form-control">
                            <option value="1" {{ $lomba->berhadiah == 1 ? 'selected' : '' }}>Berhadiah</option>
                            <option value="0" {{ $lomba->berhadiah == 0 ? 'selected' : '' }}>Tidak Berhadiah</option>
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
                        <input type="date" name="deadline_pendaftaran"
                            value="{{ $lomba->deadline_pendaftaran }}" class="form-control">
                        <div class="text-danger error-text" id="error-deadline_pendaftaran"></div>
                    </div>

                    <div class="col-md-8">
                        <label>Foto</label>
                        <input type="file" name="foto" class="form-control">
                        <small class="form-text text-muted">
                            Format yang diperbolehkan: .jpg, .jpeg, .png. Maksimal ukuran file: 2MB.
                        </small>
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

        $('.select2').select2({
            placeholder: "-- Pilih Kategori --",
            allowClear: true,
            width: '100%'
        });

        $('#id_kategori').on('change', function() {
            const selected = $(this).val();
            if (selected.length > 3) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Maksimal 3 Kategori',
                    text: 'Anda hanya bisa memilih maksimal 3 kategori lomba.'
                });
                // Hapus pilihan terakhir
                $(this).val(selected.slice(0, 3)).trigger('change');
            }
        });

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