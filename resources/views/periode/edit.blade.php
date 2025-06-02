<!-- Modal -->
<form action="{{ url('/periode/' . $data->id_periode . '/update') }}" method="POST" id="form-edit">
    @csrf
    @method('PUT')
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Periode</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="semester" class="form-label">Semester</label>
                    <select id="semester" name="semester" class="form-control" required>
                        <option value="Ganjil" {{ $data->semester == 'Ganjil' ? 'selected' : '' }}>Ganjil</option>
                        <option value="Genap" {{ $data->semester == 'Genap' ? 'selected' : '' }}>Genap</option>
                    </select>
                    <div id="error-semester" class="text-danger error-text"></div>
                </div>
                <div class="form-group">
                    <label for="tahun_ajaran" class="form-label">Tahun Ajaran</label>
                    <input value="{{ $data->tahun_ajaran }}" type="text" id="tahun_ajaran" name="tahun_ajaran"
                        class="form-control" placeholder="Contoh: 2023/2024" required>
                    <div id="error-tahun_ajaran" class="text-danger error-text"></div>
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
        $.validator.addMethod("tahunAjaranPattern", function(value, element) {
            return this.optional(element) || /^[0-9]{4}\/[0-9]{4}$/.test(value);
        }, "Format tahun ajaran harus YYYY/YYYY");

        $("#form-edit").validate({
            rules: {
                semester: {
                    required: true
                },
                tahun_ajaran: {
                    required: true,
                    tahunAjaranPattern: true //custom validation method
                }
            },
            messages: {
                tahun_ajaran: {
                    required: "Tahun ajaran harus diisi",
                    tahunAjaranPattern: "Format harus YYYY/YYYY, contoh: 2023/2024"
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
                            tablecrud.ajax.reload();
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
                        // Handle error response
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
