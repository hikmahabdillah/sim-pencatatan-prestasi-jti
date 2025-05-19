<!-- Modal -->
<div class="modal-dialog modal-dialog-centered" style="max-width: 65%" role="document">
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
                    <th>Kode Role</th>
                    <td>{{ $data->role_id }}</td>
                </tr>
                <tr>
                    <th>Nama Role</th>
                    <td>{{ $data->nama_role }}</td>
                </tr>
            </table>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Kembali</button>
        </div>
    </div>
</div>