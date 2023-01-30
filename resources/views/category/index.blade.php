@extends('layouts.app')

@section('header_styles')

<!-- Custom styles for this page -->
<link href="{{ asset('vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">

@endsection

@section('content')

<div class="row">
    <div class="col-md-10">
        <!-- Page Heading -->
        <h1 class="h3 mb-3 text-gray-800">Kategori</h1>
    </div>
    <div class="col-md-2 d-md-flex justify-content-md-end">
        <a href="{{ url('/categories/create') }}" class="btn btn-primary mb-3" id="tambah">Tambah</a>
    </div>
</div>


<!-- Data Pelanggan -->
<div class="card shadow mb-4">
    <!-- <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Data Pelanggan</h6>
    </div> -->
    <div class="card-body">
        <div class="table-responsive">
            <table class="table" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Kategori</th>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="modal-tambah" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Tambah Baru</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form method="post" action="{{ url('settings/salestype/store') }}">
      @csrf
        <div class="modal-body">
            <div class="form-group">
                <label for="name">Kategori*</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name ="name" value="{{ old('name') }}" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="button" class="btn btn-primary" id="simpan">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal Edit -->
<div class="modal fade" id="modal-edit" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Edit Tipe Penjualan</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form method="post">
      @csrf
        <div class="modal-body">
            <div class="form-group">
                <label for="nama">Tipe Penjualan*</label>
                <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name ="nama" value="" required>
                <input type="hidden" class="form-control" id="uuid" name ="uuid" value="" required>
                @error('nama')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="button" class="btn btn-primary" id="update">Update</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal Delete -->
<div class="modal fade" id="modal-delete" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Hapus</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      Anda yakin akan menghapus kategori <strong><span id="salestype"></span></strong>?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-danger" id="confirm-delete">Hapus</button>
      </div>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script src="{{ asset('vendor/datatables/jquery.dataTables.min.js') }} "></script>
<script src="{{ asset('vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
    // DataTables plugin
    $(document).ready(function() {
        $('#dataTable').DataTable( {
            processing: true,
            serverSide: true,
            ordering: false,
            paging: false,
            searching: false,
            info: false,
            ajax: "{{ url('categories/datatable') }}",
            columns: [
                { data: "name", className: "name", "width": "85%"},
                {"mRender": function ( data, type, row ) 
                    {
                        return '<a href="#" data-name="'+row.name+'" data-uuid="'+row.uuid+'" class="btn btn-primary btn-sm edit">Edit</a>';
                    }
                }, 
                {"mRender": function ( data, type, row ) 
                    {
                        return '<a href="#" data-name="'+row.name+'" data-uuid="'+row.uuid+'" class="btn btn-danger btn-sm delete">Delete</a>';
                    }
                }
            ], 
        });
    });

    $(document).ready(function() {
        $('#tambah').click(function(e){
           e.preventDefault();
           $('#modal-tambah').modal('show');
        })

        $('#simpan').click(function(){
            var name = $('#name').val();
            $.ajax({
                type: 'POST', // Metode pengiriman data menggunakan POST
                url: "{{ url('/categories') }}", // File yang akan memproses data
                data: {
                    name: name, // Data yang akan dikirim ke file pemroses
                    _token: '{{csrf_token()}}'
                },
                success: function(result) { // Jika berhasil
                    $('#name').val('');
                    $('#modal-tambah').modal('hide');
                    $('#dataTable').DataTable().ajax.reload();;

                    toastr.options = {
                        "closeButton" : true,
                        "positionClass": "toast-bottom-right",
                    }
                    toastr.success(result.success);
                }
            })
        })
    })
    
    $(document).ready(function() {
        $(document).on('click', '.edit', function(e){
            e.preventDefault();
            var name = $(this).data('name');
            var uuid = $(this).data('uuid');
            $('#nama').val(name);
            $('#uuid').val(uuid);
            $('#modal-edit').modal('show');
        })

        $('#update').click(function(){
            var name = $('#nama').val();
            var uuid = $('#uuid').val();
            $.ajax({
                type: 'PUT', // Metode pengiriman data menggunakan PUT
                url: "{{ url('/categories/update') }}", // File yang akan memproses data
                data: {
                    name: name, // Data yang akan dikirim ke file pemroses
                    uuid: uuid, 
                    _token: '{{csrf_token()}}'
                },
                success: function(result) { // Jika berhasil
                    $('#nama').val('');
                    $('#modal-edit').modal('hide');
                    $('#dataTable').DataTable().ajax.reload();;

                    toastr.options = {
                        "closeButton" : true,
                        "positionClass": "toast-bottom-right",
                    }
                    toastr.success(result.success);
                }
            })
        })
    })

    $(document).ready(function() {
        $(document).on('click', '.delete', function(e){
            e.preventDefault();
            var name = $(this).data('name');
            var uuid = $(this).data('uuid');
            $('#salestype').text(name);
            $('#uuid').val(uuid);
            $('#modal-delete').modal('show');
        })

        $('#confirm-delete').click(function(){
            var uuid = $('#uuid').val();
            $.ajax({
                type: 'DELETE', // Metode pengiriman data menggunakan PUT
                url: "{{ url('/categories/destroy') }}", // File yang akan memproses data
                data: {
                    uuid: uuid, 
                    _token: '{{csrf_token()}}'
                },
                success: function(result) { // Jika berhasil
                    $('#nama').val('');
                    $('#modal-delete').modal('hide');
                    $('#dataTable').DataTable().ajax.reload();;

                    toastr.options = {
                        "closeButton" : true,
                        "positionClass": "toast-bottom-right",
                    }
                    toastr.success(result.success);
                }
            })
        })
    })
</script>

<script>
  @if(Session::has('success'))
    toastr.options =
    {
        "closeButton" : true,
        "positionClass": "toast-bottom-right",
    }
  	toastr.success("{{ session('success') }}");
  @endif
</script>

@endpush