<form action="{{ url('/mahasiswa/import_ajax') }}" method="POST" id="form-import" enctype="multipart/form-data">
    @csrf
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Import Data Mahasiswa</h5>
                {{-- <button type="button" class="close" data-dismiss="modal" aria- label="Close"><span
                        aria-hidden="true">&times;</span></button> --}}
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Download Template</label>
                    <div class="input-group">
                        <a href="{{ asset('template_mahasiswa.xlsx') }}" class="btn btn-info btn-sm" download>
                            <i class="fa fa-file-excel"></i> Download Template
                        </a>
                    </div>
                    <small class="form-text text-muted">⚠️ Perhatian:
                    File hanya berformat (.xlsx) dan harus mengikuti struktur kolom dari template yang disediakan.</small>
                </div>
                <div class="form-group">
                    <label>Pilih File</label>
                    <input type="file" name="file_mahasiswa" id="file_mahasiswa" class="form-control"
                        accept=".xls,.xlsx,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
                        required>
                    <p class="text-muted text-small mt-2">Format: xlsx</p>
                    <small id="error-file_mahasiswa" class="error-text form-text text-danger"></small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Kembali</button>
                <button type="submit" class="btn btn-primary">Upload</button>
            </div>
        </div>
    </div>
</form>
<script>
    $(document).ready(function() {
        $("#form-import").validate({
            // rules: {
            //     file_mahasiswa: {
            //         required: true,
            //         extension: "xlsx|xls",
            //         filesize: 1024 // 1MB
            //     }
            // },
            // messages: {
            //     file_mahasiswa: {
            //         required: "File wajib diupload",
            //         extension: "Hanya file Excel (.xlsx, .xls) yang diperbolehkan",
            //         filesize: "Ukuran file maksimal 1MB"
            //     }
            // },
            submitHandler: function(form) {
                var formData = new FormData(form);
                var submitBtn = $(form).find('button[type="submit"]');

                // Tampilkan loading
                submitBtn.prop('disabled', true).html(
                    '<i class="fa fa-spinner fa-spin"></i> Processing...');

                $.ajax({
                    url: form.action,
                    type: form.method,
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        // Tutup modal yang benar
                        $('#myModal').modal('hide');

                        if (response.status) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message
                            });
                            tableMahasiswa.ajax.reload();
                        } else {
                            showValidationErrors(response);
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            showValidationErrors({
                                msgField: errors
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error ' + xhr.status,
                                text: xhr.responseJSON?.message ||
                                    'Terjadi kesalahan server'
                            });
                        }
                    },
                    complete: function() {
                        submitBtn.prop('disabled', false).html('Upload');
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

        function showValidationErrors(response) {
            $('.error-text').text('');
            if (response.msgField) {
                $.each(response.msgField, function(prefix, val) {
                    $('#error-' + prefix).text(val[0]);
                });
            }
            Swal.fire({
                icon: 'error',
                title: 'Validasi Gagal',
                text: response.message || 'Terdapat kesalahan pada data yang diinput'
            });
        }
    });
</script>
