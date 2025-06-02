 <!-- Modal -->
 <form action="{{ url('/cobacrud/' . $data->id_kategori . '/update') }}" method="POST" id="form-edit">
     @csrf
     @method('PUT')
     <div class="modal-dialog modal-dialog-centered" role="document">
         <div class="modal-content">
             <div class="modal-header">
                 <h5 class="modal-title" id="exampleModalLabel">Edit Data</h5>
                 <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true">&times;</span>
                 </button>
             </div>
             <div class="modal-body">
                 <div class="form-group">
                     <label for="nama_kategori" class="form-label">Nama Kategori</label>
                     <input value="{{ $data->nama_kategori }}" type="text" id="nama_kategori" name="nama_kategori"
                         class="form-control" placeholder="Masukkan nama_kategori" required>
                     <div id="error-nama_kategori" class="text-danger error-text"></div>
                 </div>
                 <div class="form-group">
                     <label for="deskripsi" class="form-label">Deskripsi</label>
                     <textarea id="deskripsi" name="deskripsi" class="form-control" placeholder="Masukkan deskripsi" rows="3">{{ $data->deskripsi }}</textarea>
                     <div id="error-deskripsi" class="text-danger error-text"></div>
                 </div>
             </div>
             <div class="modal-footer">
                 <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Kembali</button>
                 <button type="submit" class="btn bg-gradient-primary">Simpan Perubahan</button>
             </div>
         </div>
     </div>
 </form>
 <script>
     $(document).ready(function() {
         $("#form-edit").validate({
             rules: {
                 nama_kategori: {
                     required: true,
                     maxlength: 100
                 },
                 deskripsi: {
                     required: true,
                 }
             },
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
                     },
                     error: function(xhr) {
                         // Handle error response
                         if (xhr.status === 422) {
                             var errors = xhr.responseJSON.errors;
                             $('.error-text').text('');
                             $.each(errors, function(prefix, val) {
                                 $('#error-' + prefix).text(val[0]);
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
