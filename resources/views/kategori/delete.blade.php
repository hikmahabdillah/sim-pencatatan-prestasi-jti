 <!-- Modal -->
 <form action="{{ url('/kategori/' . $data->id_kategori . '/delete') }}" method="POST" id="form-delete">
     @csrf
     @method('DELETE')
     <div class="modal-dialog modal-dialog-centered" role="document">
         <div class="modal-content">
             <div class="modal-header">
                 <h5 class="modal-title" id="exampleModalLabel">Konfirmasi Hapus Data</h5>
                 <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true">&times;</span>
                 </button>
             </div>
             <div class="modal-body">
                 <div class="alert alert-danger text-white" role="alert">
                     <strong>Konfirmasi!</strong> Apakah anda yakin ini menghapus data ini?
                 </div>
                 <table class="table table-bordered">
                     <tr>
                         <th>Kode Kategori</th>
                         <td>{{ $data->id_kategori }}</td>
                     </tr>
                     <tr>
                         <th>Nama Kategori</th>
                         <td>{{ $data->nama_kategori }}</td>
                     </tr>
                     <tr>
                         <th>Deskripsi Kategori</th>
                         <td class="text-wrap">{{ $data->deskripsi }}</td>
                     </tr>
                 </table>
             </div>
             <div class="modal-footer">
                 <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Kembali</button>
                 <button type="submit" class="btn bg-gradient-primary">Hapus</button>
             </div>
         </div>
     </div>
 </form>
 <script>
     $(document).ready(function() {
         $("#form-delete").validate({
             rules: {},
             submitHandler: function(form) {
                 $.ajax({
                     url: form.action,
                     type: form.method,
                     data: $(form).serialize(),
                     success: function(response) {
                         if (response.status) {
                             $('#myModal').modal('hide');
                             Swal.fire({
                                 icon: 'success',
                                 title: 'Berhasil',
                                 text: response.message
                             });
                             tablecrud.ajax.reload();
                         } else {
                             $('.error-text').text('');
                             $.each(response.msgField, function(prefix, val) {
                                 $('#error-' + prefix).text(val[0]);
                             });
                             Swal.fire({
                                 icon: 'error',
                                 title: 'Terjadi Kesalahan',
                                 text: response.message
                             });
                         }
                     }
                 });
                 return false;
             },
             errorElement: 'span',
             errorPlacement: function(error, element) {
                 error.addClass('invalid-feedback');
                 element.closest('.form-group').append(error);
             },
             highlight: function(element, errorClass, validClass) {
                 $(element).addClass('is-invalid');
             },
             unhighlight: function(element, errorClass, validClass) {
                 $(element).removeClass('is-invalid');
             }
         });
     });
 </script>