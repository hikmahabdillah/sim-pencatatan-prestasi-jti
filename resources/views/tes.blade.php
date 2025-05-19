@extends('layouts.template', ['class' => 'g-sidenav-show bg-light', 'page' => 'prestasi-mahasiswa'])

@section('content')
    @include('layouts.navbar')
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
            <form action="">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="nama" class="form-label">Nama</label>
                        <input type="text" id="nama" name="nama" class="form-control" placeholder="Masukkan nama"
                            required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="prestasi" class="form-label">Prestasi</label>
                        <input type="text" id="prestasi" name="prestasi" class="form-control"
                            placeholder="Masukkan prestasi" required>
                    </div>
                </div>


                <div class="form-group">
                    <label for="InputEmail">Email</label>

                    <input type="email" name="email" class="form-control" id="InputEmail" placeholder="name@gmail.com" required>

                </div>
                <div class="form-group">
                    <label for="select">Juara</label>
                    <select name="" class="form-control" id="select">
                        <option value=""></option>
                        <option value="">1</option>
                        <option value="">2</option>
                        <option value="">3</option>
                        <option value="">Harapan 1</option>
                        <option value="">Harapan 2</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="multi-select">Juara</label>
                    <select multiple name="" class="form-control" id="multi-select">
                        <option value=""></option>
                        <option value="">1</option>
                        <option value="">2</option>
                        <option value="">3</option>
                        <option value="">Harapan 1</option>
                        <option value="">Harapan 2</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="catatan">Catatan</label>
                    <textarea name="" class="form-control" id="catatan" rows="3"></textarea>
                </div>
                <div class="form-group">
                    <label for="url">Url</label>
                    <input name="" type="url" class="form-control" id="url" placeholder="" required>
                </div>
                <div class="form-group">
                    <label for="nomor hp">Nomor Hp</label>

                    <input type="tel" name="" class="form-control" id="nomor hp" placeholder="081234567890" required>

                </div>
                <div class="form-group">
                    <label for="nomor">Nomor</label>
                    <input type="number" name="" class="form-control" id="nomor" required>
                </div>
                <div class="form-group">
                    <label for="date">Tanggal Lahir</label>
                    <input type="date" name="" class="form-control" value="2010-11-23" id="date" required>
                </div>
                <div class="form-group">
                    <label for="time">Waktu</label>
                    <input type="time" name="" class="form-control" value="10:30:00" id="time" required>
                </div>
                <div class="form-group">
                    <label for="datetime">Jadwal Lomba</label>

                    <input type="datetime-local" name="" class="form-control" value="2025-11-23T10:30:00" id="datetime" required>

                </div>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </form>
        </div>

        <div class="card p-3">
            <form action="">
                @csrf
                <div class="form-group">
                    <label for="">Tingkat Lomba</label>
                    <div class="form-check">

                        <input class="form-check-input" name="" type="checkbox" value="" id="checkbox1" >

                        <label class="custom-control-label" for="checkbox1">kota</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" name="" type="checkbox" value="" id="checkbox2">
                        <label class="custom-control-label" for="checkbox2">Provinsi</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" name="" type="checkbox" value="" id="checkbox3">
                        <label class="custom-control-label" for="checkbox3">Nasional</label>
                    </div>
                </div>
                <div class="form-group">
                    <label for="">Tingkat Lomba</label>
                    <div class="form-check">

                        <input class="form-check-input" name="radioDisabled" type="radio" value="" id="radio1" required>
                        <label class="custom-control-label" for="radio1">kota</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" name="radioDisabled" type="radio" value="" id="radio2">
                        <label class="custom-control-label" for="radio2">Provinsi</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" name="radioDisabled" type="radio" value="" id="radio3">

                        <label class="custom-control-label" for="radio3">Nasional</label>
                    </div>
                </div>
                <div class="form-group">
                    <label for="">Status</label>
                    <div class="form-check form-switch">
                        <label class="me-2 mb-0">Pending</label>

                        <input class="me-0 ms-0 form-check-input" name="" type="checkbox" >

                        <label class="ms-2 mb-0" for="statusSwitch">Done</label>
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
