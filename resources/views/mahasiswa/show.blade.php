<!-- Modal -->
<div class="modal-dialog modal-dialog-centered" style="max-width: 55%" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Detail Mahasiswa</h5>
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
                                {{-- src="{{ asset('storage/' . auth()->user()->profile_image) ?? 'image/fotoDefault.jpg' }}" --}}
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-7 ps-0 my-auto">
                        <div class="card-body text-left">
                            <div class="p-md-0 pt-3">
                                <h5 class="font-weight-bolder mb-0 text-uppercase">{{ $data->nama }}</h5>
                                <p class="text-uppercase text-sm font-weight-bold mb-4">{{ $data->prodi->nama_prodi }} -
                                    {{ $data->nim }}</p>
                                <p class="text-dark mb-0 mb-0">
                                    <strong>Minat Bakat: </strong>
                                </p>
                                <div class="my-2">
                                    @foreach ($data->pengguna->minatBakat as $kategori)
                                        <span class="badge bg-primary">{{ $kategori->nama_kategori }}</span>
                                    @endforeach
                                </div>
                                <p class="text-dark mb-0">
                                    <strong>Email: </strong>
                                    {{ $data->email }}
                                </p>
                                <p class="text-dark mb-0">
                                    <strong>No. HP: </strong>
                                    {{ $data->no_hp }}
                                </p>
                                <p class="text-dark mb-0 text-wrap">
                                    <strong>Jenis Kelamin: </strong>
                                    {{ $data->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}
                                </p>
                                <p class="text-dark mb-0 text-wrap">
                                    <strong>Tanggal Lahir: </strong>
                                    {{ \Carbon\Carbon::parse($data->tanggal_lahir)->translatedFormat('d F Y') }}
                                </p>
                                <p class="text-dark mb-0 text-wrap">
                                    <strong>Alamat: </strong>
                                    {{ $data->alamat }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer justify-content-between align-items-center">
            <p class="text-dark">
                <strong>Angkatan: </strong>
                {{ $data->angkatan }} - {{ $data->pengguna->status_aktif ? 'Aktif' : 'Non-Aktif' }}
            </p>
            <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Kembali</button>
        </div>
    </div>
</div>
