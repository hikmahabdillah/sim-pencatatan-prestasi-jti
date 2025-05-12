<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('argon/assets/img/apple-icon.png') }}">
        <link rel="icon" type="image/png" href="{{ asset('argon/assets/img/favicon.png') }}">
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
                        <form>
                            <div class="form-group mb-3">
                                <label class="h6">Email address</label>
                                <input type="email" class="form-control form-control-lg" required />
                            </div>
                            <div class="form-group mb-4">
                                <label class="h6">Password</label>
                                <input type="password" class="form-control form-control-lg" required />
                            </div>
                            <div class="d-grid mb-3">
                                <button class="btn btn-primary btn-lg">Login</button>
                            </div>
                            <div class="text-center">
                                {{-- <a href="#" class="small text-muted">Forgot password?</a> --}}
                                <p class="mt-2">Don't have an account? <a href="#">Register here</a></p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
        <script src="{{ asset('argon/assets/js/core/popper.min.js') }}"></script>
        <script src="{{ asset('argon/assets/js/core/bootstrap.min.js') }}"></script>
        <script src="{{ asset('argon/assets/js/plugins/perfect-scrollbar.min.js') }}"></script>
        <script src="{{ asset('argon/assets/js/plugins/smooth-scrollbar.min.js') }}"></script>
    </body>

</html>
