<!-- Modal -->
<div class="modal-dialog modal-dialog-centered" style="max-width: 65%" tingkat_prestasi="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Detail Data</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <table class="table table-bordered">
                <tr>
                    <th>Kode Tingkat Prestasi</th>
                    <td>{{ $data->id_tingkat_prestasi }}</td>
                </tr>
                <tr>
                    <th>Tingkat Prestasi</th>
                    <td>{{ $data->nama_tingkat_prestasi }}</td>
                </tr>
            </table>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Kembali</button>
        </div>
    </div>
</div>