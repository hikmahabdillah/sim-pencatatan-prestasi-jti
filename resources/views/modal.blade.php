@extends('layouts.template', ['class' => 'g-sidenav-show bg-light', 'page' => 'prestasi-mahasiswa'])

@section('content')
    @include('layouts.navbar', ['title' => 'Dashboard'])
    <div class="container-fluid py-4 h-100 flex-grow-1">
        <!-- Button trigger modal -->
        <button type="button" class="btn bg-gradient-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
            confirm modal(untuk form edit tambah dan hapus)
        </button>

        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        isinya bisa apa aja
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn bg-gradient-primary">Save changes</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Button trigger modal -->
        <button type="button" class="btn bg-gradient-primary" data-bs-toggle="modal" data-bs-target="#normalModal">
            modal biasa(untuk detail)
        </button>

        <!-- Modal -->
        <div class="modal fade" id="normalModal" tabindex="-1" role="dialog" aria-labelledby="normalModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="normalModalLabel">Modal title</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        isinya bisa apa aja
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <h6>ini buat status ges, mungkin buat status verifikasi atau status yang lain nampilno e pake ini</h6>
        <div class="d-flex flex-column gap-2" style="width: max-content">
            <span class="badge bg-gradient-secondary">Secondary</span>
            <span class="badge bg-gradient-success">Success</span>
            <span class="badge bg-gradient-danger">Danger</span>
        </div>
    </div>
    @include('layouts.footer')
@endsection
