@extends('layouts.template', ['class' => 'g-sidenav-show bg-light', 'page' => 'prestasi-mahasiswa'])

@section('content')
    @include('layouts.navbar', ['title' => 'Dashboard'])
    <div class="container-fluid py-4 h-100 flex-grow-1">
        <div class="card p-3">
            <div class="table-responsive">
                <table id="prestasiTable" class="table table-hover">
                    <thead class="table-light">
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
                        <tr>
                            <td>Nopal</td>
                            <td>Juara 3 Gemastik</td>
                        </tr>
                        <tr>
                            <td>Nopal</td>
                            <td>Juara 3 Gemastik</td>
                        </tr>
                        <tr>
                            <td>Nopal</td>
                            <td>Juara 3 Gemastik</td>
                        </tr>
                        <tr>
                            <td>Nopal</td>
                            <td>Juara 3 Gemastik</td>
                        </tr>
                        <tr>
                            <td>Nopal</td>
                            <td>Juara 3 Gemastik</td>
                        </tr>
                        <tr>
                            <td>Nopal</td>
                            <td>Juara 3 Gemastik</td>
                        </tr>
                        <tr>
                            <td>Nopal</td>
                            <td>Juara 3 Gemastik</td>
                        </tr>
                        <tr>
                            <td>Nopal</td>
                            <td>Juara 3 Gemastik</td>
                        </tr>
                        <tr>
                            <td>Nopal</td>
                            <td>Juara 3 Gemastik</td>
                        </tr>
                        <tr>
                            <td>Nopal</td>
                            <td>Juara 3 Gemastik</td>
                        </tr>
                        <tr>
                            <td>Nopal</td>
                            <td>Juara 3 Gemastik</td>
                        </tr>
                        <tr>
                            <td>Nopal</td>
                            <td>Juara 3 Gemastik</td>
                        </tr>
                        <tr>
                            <td>Nopal</td>
                            <td>Juara 3 Gemastik</td>
                        </tr>
                        <tr>
                            <td>Nopal</td>
                            <td>Juara 3 Gemastik</td>
                        </tr>
                    </tbody>
                </table>


            </div>
        </div>
        <button onclick="showSuccess()" class="btn btn-success mt-3">Tes SweetAlert</button>
    </div>
    @include('layouts.footer')
@endsection

{{-- contoh implementasi data tables dan sweet alert --}}
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
