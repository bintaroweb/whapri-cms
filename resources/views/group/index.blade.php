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
        <h3 class="h3 mb-3">Daftar Group</h3>
    </div>
    <div class="col-md-2 d-md-flex justify-content-md-end">
        <a href="{{ url('/groups/create') }}" class="btn btn-primary btn-md mb-3" role="button"><i class="fas fa-plus"></i> Tambah</a>
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
                        <th>Nama Group</th>
                        <th>Jumlah Kontak</th>
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
      Apakah kamu ingin menghapus group ini?
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
            bPaginate: true,
            bInfo: false,
            ajax: "{{ url('groups/datatable') }}",
            columns: [
                { data: "name", className: "name"},
                { data: "total", className: "total"},
                { data: "label", className: "label"},
                { "mRender": function ( data, type, row ) {
                    return '<div class="d-md-flex justify-content-md-end"><a href="{{url("groups")}}/'+row.uuid+'/edit" class="btn btn-secondary btn-sm btn-edit mx-2" title="Edit"><i class="fa-solid fa-pen-to-square"></i></a> <a href="#" class="btn btn-danger btn-sm delete" data-uuid="'+row.uuid+'"><i class="fa-solid fa-trash"></i></a></div>';}
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
        $('#delete-form').attr('action', '{{ env("APP_URL") }}/groups/'+uuid+'');
        $('#modal-delete').modal('show');
    })

    $('#confirm-delete').click(function(){
        $('#delete-form').submit();
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