<!-- Modal -->
<div class="modal-dialog modal-dialog-centered" style="max-width: 55%" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Detail Admin</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="card card-profile card-plain">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="position-relative h-100">
                            <div class="blur-shadow-image h-100" style="max-height: 300px;">
                                @php
                                    $foto = $data->pengguna->foto
                                        ? asset('storage/' . $data->pengguna->foto)
                                        : asset('image/fotoDefault.jpg');
                                @endphp
                                <img class="w-100 h-100 rounded-3 shadow-lg" style="object-fit: cover"
                                    src="{{ $foto }}">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-7 ps-0 my-auto">
                        <div class="card-body text-left">
                            <div class="p-md-0 pt-3">
                                <h5 class="font-weight-bolder mb-0 text-uppercase">{{ $data->nama_admin }}</h5>
                                <p class="text-uppercase text-sm font-weight-bold mb-4">{{ $data->username }}</p>
                                
                                <p class="text-dark mb-0">
                                    <strong>Email: </strong>
                                    {{ $data->email }}
                                </p>
                                <p class="text-dark mb-0">
                                    <strong>Role: </strong>
                                    Admin
                                </p>
                                <p class="text-dark mb-0">
                                    <strong>Tanggal Dibuat: </strong>
                                    {{ \Carbon\Carbon::parse($data->created_at)->translatedFormat('d F Y') }}
                                </p>
                                <p class="text-dark mb-0">
                                    <strong>Terakhir Diupdate: </strong>
                                    {{ \Carbon\Carbon::parse($data->updated_at)->translatedFormat('d F Y') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer justify-content-between align-items-center">
            <p class="text-dark">
                <strong>Status: </strong>
                {{ $data->pengguna->status_aktif ? 'Aktif' : 'Non-Aktif' }}
            </p>
            <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Kembali</button>
        </div>
    </div>
</div>