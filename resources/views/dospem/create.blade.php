<!-- Modal -->
<form action="{{ url('/dospem/store') }}" method="POST" id="form-tambah">
    @csrf
    <div id="modal-master" class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Dosen Pembimbing</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="form-group mb-3">
                        <label for="nip" class="form-label">NIP</label>
                        <input type="text" id="nip" name="nip" class="form-control"
                            placeholder="Masukkan NIP" required>
                        <div id="error-nip" class="text-danger error-text"></div>
                    </div>

                    <div class="form-group mb-3">
                        <label for="nama" class="form-label">Nama Lengkap</label>
                        <input type="text" id="nama" name="nama" class="form-control"
                            placeholder="Masukkan nama lengkap" required>
                        <div id="error-nama" class="text-danger error-text"></div>
                    </div>
                    <div class="form-group mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" id="email" name="email" class="form-control"
                            placeholder="Masukkan email" required>
                        <div id="error-email" class="text-danger error-text"></div>
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
                nip: {
                    required: true,
                    maxlength: 20
                },
                nama: {
                    required: true,
                    maxlength: 200
                },
                email: {
                    required: true,
                    email: true
                },
                id_prodi: {
                    required: true
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
                            tableDospem.ajax.reload();
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
