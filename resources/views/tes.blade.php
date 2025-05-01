@extends('layouts.template', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbar', ['title' => 'Dashboard'])
    <div class="container-fluid py-4">
        <table id="prestasiTable" class="table table-bordered">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Prestasi</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Moch Zawaruddin</td>
                    <td>Juara 1 Gemastik</td>
                </tr>
            </tbody>
        </table>


        <button onclick="showSuccess()" class="btn btn-success mt-3">Tes SweetAlert</button>
    </div>
    @include('layouts.footer')
@endsection

@push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            $('#prestasiTable').DataTable();
        });

        function showSuccess() {
            Swal.fire({
                title: 'Berhasil!',
                text: 'Ini alert dari SweetAlert2.',
                icon: 'success',
                confirmButtonText: 'OK'
            });
        }
    </script>
@endpush
