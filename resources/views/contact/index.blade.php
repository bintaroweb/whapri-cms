@extends('layouts.app')

@section('header_styles')

<!-- Custom styles for this page -->
<link href="{{ asset('vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">

@endsection

@section('content')

<div class="row">
    <div class="col-md-8">
        <!-- Page Heading -->
        <h3 class="h3 mb-3 text-gray-800">Daftar Kontak</h3>
    </div>
    <div class="col-md-4 d-md-flex justify-content-md-end">
        <a href="{{ url('/contacts/create') }}" class="btn btn-primary btn-md mb-3 me-2" role="button"><i class="fas fa-plus"></i> Tambah</a>
        <a href="{{ url('/contacts/import') }}" class="btn btn-primary btn-md mb-3" role="button" id="import"><i class="fa-solid fa-file-import"></i> Import</a>
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
                        <th>Nomor HP</th>
                        <th>Nama</th>
                        <th>Label</th>
                        <th></th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<!-- Modal Delete -->
<div class="modal fade" id="modal-delete" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Konfirmasi</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      Apakah kamu ingin menghapus kontak ini?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-danger" id="confirm-delete">Hapus</button>
      </div>
    </div>
  </div>
</div>

<form id="delete-form" action="" method="POST" class="d-none">
    @method('delete')    
    @csrf
</form>

<!-- Modal Import -->
<div class="modal fade" id="modal-import" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Import Kontak</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="import-form" method="post" action="{{ url('contacts/import') }}" enctype="multipart/form-data">
          @csrf
          <div class="form-group">
              <!-- <label for="name" class="required">Pilih File</label> -->
              <input type="file" class="form-control form-control-sm " id="upload" name="upload" accept=".csv" required>
              <!-- <small class="form-text text-muted">Bisa input nama usaha/perusahaan jika memang tidak diketahui Contact Personnya</small> -->
          </div>
          <div>
              <p>Download contoh file csv <a href="{{ asset('files/Format-Import-Kontak.csv') }}">disini </a>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-primary" id="confirm-import">Proses</button>
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
    // dataTables plugin
    $(document).ready(function() {
        $('#dataTable').DataTable( {
            processing: true,
            serverSide: true,
            ordering: false,
            bPaginate: false,
            bInfo: false,
            ajax: "{{ url('contacts/datatable') }}",
            columns: [
                { data: "phone", className: "phone"},
                { data: "name", className: "name"},
                { data: "label", className: "label"},
                { "mRender": function ( data, type, row ) {
                    return '<div class="d-md-flex justify-content-md-end"><a href="{{url("contacts")}}/'+row.uuid+'/edit" class="btn btn-secondary btn-sm btn-edit mx-2"><i class="fa-solid fa-pen-to-square"></i></a> <a href="#" class="btn btn-danger btn-sm delete" data-uuid="'+row.uuid+'"><i class="fa-solid fa-trash"></i></a></div>';}
                }
            ], 
            language: {
                processing: "Mohon tunggu ..."
            }
        });
    });

    $( document ).on("click", ".delete", function(e){
        e.preventDefault();
        var uuid = $(this).data('uuid');
        $('#delete-form').attr('action', '{{ env("APP_URL") }}/contacts/'+uuid+'');
        $('#modal-delete').modal('show');
    })

    $('#confirm-delete').click(function(){
        // document.getElementById('logout-form').submit();
        $('#delete-form').submit();
    })

    $('#import').click(function(e){
           e.preventDefault();
           $('#modal-import').modal('show');
        })

    $('#confirm-import').click(function(){
        $('#import-form').submit();
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