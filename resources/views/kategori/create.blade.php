 <!-- Modal -->
 <form action="{{ url('/kategori/store') }}" method="POST" id="form-tambah">
     @csrf
     <div id="modal-master" class="modal-dialog modal-dialog-centered" role="document">
         <div class="modal-content">
             <div class="modal-header">
                 <h5 class="modal-title" id="exampleModalLabel">Tambah Data</h5>
                 <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true">&times;</span>
                 </button>
             </div>
             <div class="modal-body">
                 <div class="form-group">
                     <label for="nama_kategori" class="form-label">Nama Kategori</label>
                     <input type="text" id="nama_kategori" name="nama_kategori" class="form-control"
                         placeholder="Masukkan nama_kategori">
                     <div id="error-nama_kategori" class="text-danger error-text"></div>
                 </div>
                 <div class="form-group">
                     <label for="deskripsi" class="form-label">Deskripsi</label>
                     <textarea id="deskripsi" name="deskripsi" class="form-control" placeholder="Masukkan deskripsi" rows="3"></textarea>
                     <div id="error-deskripsi" class="text-danger error-text"></div>
                 </div>
             </div>
             <div class="modal-footer">
                 <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Kembali</button>
                 <button type="submit" class="btn bg-gradient-primary">Simpan</button>
             </div>
         </div>
     </div>
 </form>
 <script>
     $(document).ready(function() {
         $("#form-tambah").validate({
             rules: {
                 nama_kategori: {
                     required: true,
                     maxlength: 100
                 },
                 deskripsi: {
                     required: true,
                 }
             },
             messages: {
                 nama_kategori: {
                     required: "Nama kategori tidak boleh kosong",
                     maxlength: "Nama kategori maksimal 100 karakter"
                 },
                 deskripsi: {
                     required: "Deskripsi tidak boleh kosong"
                 }
             },
             submitHandler: function(form) {
                 $.ajax({
                     url: form.action,
                     type: form.method,
                     data: $(form).serialize(),
                     success: function(response) {
                         if (response.status) {
                             $('#modal-master').modal('hide');
                             Swal.fire({
                                 icon: 'success',
                                 title: 'Berhasil',
                                 text: response.message
                             });
                             tablecrud.ajax.reload();
                         }
                     },
                     error: function(xhr) {
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