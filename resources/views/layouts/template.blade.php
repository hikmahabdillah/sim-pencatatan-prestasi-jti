<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('argon/assets/img/apple-icon.png') }}">
        <link rel="icon" type="image/png" href="{{ asset('image/Logo talenti bg.jpg') }}">
        <title>Talenti | {{ $page ?? 'Talenta JTI' }}</title>
        <meta name="csrf-token" content="{{ csrf_token() }}">


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
        <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
        <style>
            .select2-container--default .select2-selection--single {
                height: 38px;
                padding: 5px;
                border: 1px solid #ced4da;
            }

            .select2-container--default .select2-selection--single .select2-selection__arrow {
                height: 36px;
            }

            .select2-container--default .select2-selection--single .select2-selection__rendered {
                line-height: 26px;
            }

            .is-invalid+.select2-container--default .select2-selection--single {
                border-color: #dc3545;
            }

            .select2-container {
                width: 100% !important;
            }
        </style>

        {{-- link awesome --}}
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
        @vite('resources/css/app.css')
        @stack('css')
    </head>

    <body class="g-sidenav-show bg-light">

        @if (Request::is('/welcome'))
            <div class="min-height-300 bg-primary position-absolute w-100"></div>
            <div class="position-absolute w-100 min-height-300 top-0"
                style="background-image: url('https://raw.githubusercontent.com/creativetimofficial/public-assets/master/argon-dashboard-pro/assets/img/profile-layout-header.jpg'); background-position-y: 50%;">
                <span class="mask bg-primary opacity-6"></span>
            </div>
        @endif

        @include('layouts.sidebar', ['activeMenu' => $activeMenu ?? ''])

        <main class="main-content border-radius-lg d-flex flex-column min-vh-100 bg-light">
            @yield('content')
        </main>

        {{-- @include('components.fixed-plugin') --}}

        <!-- Core JS Files -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
        @vite('resources/js/app.js')
        <script src="{{ asset('argon/assets/js/core/popper.min.js') }}"></script>
        <script src="{{ asset('argon/assets/js/core/bootstrap.min.js') }}"></script>
        <script src="{{ asset('argon/assets/js/plugins/perfect-scrollbar.min.js') }}"></script>
        <script src="{{ asset('argon/assets/js/plugins/smooth-scrollbar.min.js') }}"></script>
        <script src="https://cdn.datatables.net/2.3.0/js/dataTables.js"></script>
        <script src="https://cdn.datatables.net/2.3.0/js/dataTables.bootstrap5.js"></script>

        <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>

        <script>
            var win = navigator.platform.indexOf('Win') > -1;
            if (win && document.querySelector('#sidenav-scrollbar')) {
                var options = {
                    damping: '0.5'
                }
                Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
            }
        </script>

        <!-- Github buttons -->
        <script async defer src="https://buttons.github.io/buttons.js"></script>

        <!-- Argon Dashboard JS -->
        <script src="{{ asset('argon/assets/js/argon-dashboard.js') }}"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        </script>
        @stack('js')
    </body>

</html>
