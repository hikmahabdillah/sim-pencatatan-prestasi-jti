<!-- Modal -->
<form action="{{ url('/periode/store') }}" method="POST" id="form-tambah">
    @csrf
    <div id="modal-master" class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Periode</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="semester" class="form-label">Semester</label>
                    <select id="semester" name="semester" class="form-control" required>
                        <option value="">Pilih Semester</option>
                        <option value="Ganjil">Ganjil</option>
                        <option value="Genap">Genap</option>
                    </select>
                    <div id="error-semester" class="text-danger error-text"></div>
                </div>
                <div class="form-group">
                    <label for="tahun_ajaran" class="form-label">Tahun Ajaran</label>
                    <input type="text" id="tahun_ajaran" name="tahun_ajaran" class="form-control"
                        placeholder="Contoh: 2023/2024" required>
                    <div id="error-tahun_ajaran" class="text-danger error-text"></div>
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
    $(document).ready(function () {
        $("#form-tambah").validate({
            rules: {
                semester: {
                    required: true
                },
                tahun_ajaran: {
                    required: true,
                    pattern: /^[0-9]{4}\/[0-9]{4}$/
                }
            },
            messages: {
                tahun_ajaran: {
                    pattern: "Format tahun ajaran harus YYYY/YYYY"
                }
            },
            submitHandler: function (form) {
                $.ajax({
                    url: form.action,
                    type: form.method,
                    data: $(form).serialize(),
                    headers: {
                        if(response.status) {
                    $('#myModal').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: response.message
                    });
                    tablecrud.ajax.reload();
                } else {
                    $('.error-text').text('');
                    $.each(response.msgField, function (prefix, val) {
                        $('#error-' + prefix).text(val[0]);
                    });
                    Swal.fire({
                        icon: 'error',
                        title: 'Terjadi Kesalahan',
                        text: response.message
                    });
                }
        }
                });
    return false;
            },
    errorElement: 'span',
        errorPlacement: function (error, element) {
            error.addClass('invalid-feedback');
            element.closest('.form-group').append(error);
        },
    highlight: function (element, errorClass, validClass) {
        $(element).addClass('is-invalid');
    },
    unhighlight: function (element, errorClass, validClass) {
        $(element).removeClass('is-invalid');
    }
        });
    });
</script>