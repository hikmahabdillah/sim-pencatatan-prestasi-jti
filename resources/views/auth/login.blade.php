<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('argon/assets/img/apple-icon.png') }}">
        <link rel="icon" type="image/png" href="{{ asset('image/Logo talenti bg.jpg') }}">
        <link rel="icon" type="image/png" href="{{ asset('argon/assets/img/favicon.png') }}">
        <link rel="icon" type="image/png" href="{{ asset('image/Logo talenti bg.jpg') }}">
        <title>Talenti | Talenta JTI</title>

        <!-- Fonts and icons -->
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />

        <!-- Nucleo Icons -->
        <link href="{{ asset('argon/assets/css/nucleo-icons.css') }}" rel="stylesheet" />
        <link href="{{ asset('argon/assets/css/nucleo-svg.css') }}" rel="stylesheet" />

        <!-- Font Awesome Icons -->
        <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>

        <!-- Argon CSS -->
        <link id="pagestyle" href="{{ asset('argon/assets/css/argon-dashboard.css') }}" rel="stylesheet" />

        <link rel="stylesheet" href="https://cdn.datatables.net/2.3.0/css/dataTables.dataTables.css" />
    </head>

    <body>
        <section class="min-vh-100 d-flex align-items-center justify-content-center" style="background-color: #f8f9fa;">
            <div class="card shadow-lg mx-3 h-100 w-100" style="max-width: 560px;">
                <div class="col-md-6 p-4 p-md-5
                align-content-center w-100">
                    <div>
                        <div class="mb-6 text-center d-flex align-items-center justify-content-center">
                            <img src="{{ asset('image/LogoTalentiTransparent.png') }}" width="100px" alt="" />
                            <span class="h3 fw-bold" style="margin-left: -10px">Talenti</span>
                        </div>
                        <h5 class="mb-4">Sign into your account</h5>
                        <form action="{{ url('login') }}" method="POST" id="form-login">
                            @csrf
                            <div class="form-group mb-3">
                                <label class="h6">Username</label>
                                <input type="username" name="username" class="form-control form-control-lg" required />
                            </div>
                            <div class="form-group mb-4">
                                <label class="h6">Password</label>
                                <input type="password" name="password" class="form-control form-control-lg" required />
                            </div>
                            <div class="d-grid mb-3">
                                <button class="btn btn-primary btn-lg">Login</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        @vite('resources/js/app.js')
        <script src="{{ asset('argon/assets/js/core/popper.min.js') }}"></script>
        <script src="{{ asset('argon/assets/js/core/bootstrap.min.js') }}"></script>
        <script src="{{ asset('argon/assets/js/plugins/perfect-scrollbar.min.js') }}"></script>
        <script src="{{ asset('argon/assets/js/plugins/smooth-scrollbar.min.js') }}"></script>
        <script src="https://cdn.datatables.net/2.3.0/js/dataTables.js"></script>
        <script src="https://cdn.datatables.net/2.3.0/js/dataTables.bootstrap5.js"></script>

        <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
        <script src="{{ asset('argon/assets/js/argon-dashboard.js') }}"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $(document).ready(function() {
                $("#form-login").validate({
                    rules: {
                        username: {
                            required: true,
                            minlength: 3,
                            maxlength: 20
                        },
                        password: {
                            required: true,
                            minlength: 3,
                            maxlength: 20
                        }
                    },
                    submitHandler: function(form) { // ketika valid, maka bagian yg akan dijalankan
                        $.ajax({
                            url: form.action,
                            type: form.method,
                            data: $(form).serialize(),
                            success: function(response) {
                                if (response.status) { // jika sukses
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Berhasil',
                                        text: response.message,
                                    }).then(function() {
                                        window.location = response.redirect;
                                    });
                                } else { // jika error
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
                        element.closest('.input-group').append(error);
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
    </body>

</html>
