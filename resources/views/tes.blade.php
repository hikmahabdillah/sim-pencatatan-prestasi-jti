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
        <div class="card p-3">
            <form>
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="nama" class="form-label">Nama</label>
                        <input type="text" id="nama" name="nama" class="form-control" placeholder="Masukkan nama" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="prestasi" class="form-label">Prestasi</label>
                        <input type="text" id="prestasi" name="prestasi" class="form-control"
                            placeholder="Masukkan prestasi" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </form>
        </div>

        @include('layouts.footer')
@endsection

    {{-- contoh implementasi data tables dan sweet alert --}}
    @push('js')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
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