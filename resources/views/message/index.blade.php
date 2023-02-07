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
        <h3 class="h3 mb-3 text-gray-800">Daftar Pesan</h3>
    </div>
    <div class="col-md-2 d-md-flex justify-content-md-end">
        <a href="{{ url('/messages/create') }}" class="btn btn-primary btn-md mb-3" role="button"><i class="fas fa-plus"></i> Tambah</a>
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
                        <th width="100">Tanggal</th>
                        <th>Penerima</th>
                        <th>Pesan</th>
                        <th class="text-center">Status</th>
                        <!-- <th>User/CS</th> -->
                        <th>Perangkat</th>
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
      Apakah kamu ingin menghapus pesan ini?
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

<!-- Modal View Detail -->
<div class="modal fade" id="modal-detail" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Detail Pesan</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="pesan"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
        <!-- <button type="button" class="btn btn-danger" id="confirm-delete">Hapus</button> -->
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
            bPaginate: true,
            bInfo: false,
            drawCallback: function () {
                $('[data-bs-toggle="tooltip"]').tooltip();
            },
            ajax: "{{ url('messages/datatable') }}",
            columns: [
                { data: "date", className: "date"},
                { data: "name", className: "name"},
                { data: "message", className: "message"},
                { data: "status", 
                    render: function (data, type, row) {
                        if (row.ack == 0) {
                            return '<div class="d-md-flex justify-content-md-center"><div class="text-center" data-bs-toggle="tooltip" data-bs-title="Pesan dalam antrian pengiriman"><i class="fa-solid fa-spinner"></i></div></div>';
                        } else if (row.ack == 1) {
                            return '<div class="d-md-flex justify-content-md-center"><div class="text-center" data-bs-toggle="tooltip" data-bs-title="Pesan telah diterima"><i class="fa-solid fa-check"></i><!--span class="small">Viewed at <br>'+row.time+'</span--></div></div>';
                        } else if (row.ack == 2) {
                            return '<div class="d-md-flex justify-content-md-center"><div class="text-center" data-bs-toggle="tooltip" data-bs-title="Pesan telah diterima"><i class="fa-solid fa-check-double"></i><!--span class="small">Viewed at <br>'+row.time+'</span--></div>';
                        } else if (row.ack == 3) {
                            return '<div class="d-md-flex justify-content-md-center"><div class="text-center" data-bs-toggle="tooltip" data-bs-title="Pesan telah dibaca"><i class="fa-solid fa-check-double text-success"></i><!--span class="small">Viewed at <br>'+row.time+'</span--></div></div>';
                        } else {
                            return '<div class="d-md-flex justify-content-md-center"><div class="text-center" data-bs-toggle="tooltip" data-bs-title="Pesan tidak terkirim"><i class="fa-solid fa-xmark text-danger"></i><br/><span class="small">Send message failed</span></div></div>';
                        } 
                    }
                },
                // { data: "user", className: "user"},
                { data: "device", className: "device"},
                { "mRender": function ( data, type, row ) {
                    return '<div class="d-md-flex justify-content-md-end"><a href="#" class="btn btn-secondary btn-sm detail mx-2" data-uuid="'+row.uuid+'"><i class="fa-solid fa-eye"></i></a> <a href="#" class="btn btn-danger btn-sm delete" data-uuid="'+row.uuid+'"><i class="fa-solid fa-trash"></i></a></div>';}
                }
            ], 
            language: {
                processing: "Mohon tunggu ..."
            }
        });
    });
    
</script>

<script>
    $( document ).on("click", ".delete", function(e){
        e.preventDefault();
        var uuid = $(this).data('uuid');
        $('#delete-form').attr('action', '{{ env("APP_URL") }}/messages/'+uuid+'');
        $('#modal-delete').modal('show');
    })

    $('#confirm-delete').click(function(){
        // document.getElementById('logout-form').submit();
        $('#delete-form').submit();
    })

    $( document ).on("click", ".detail", function(e){
        e.preventDefault();
        var uuid = $(this).data('uuid');
        $.ajax({
            type: 'GET',
            url: "{{ env('APP_URL') }}/messages/detail",
            data: { 
                uuid: uuid, 
            },
            success: function(result) { 
                console.log(result);
                $('.pesan').text(result.message.message)
                // $('.receiver').text(result.message.date)
                $('#modal-detail').modal('show');
            }
        })
        // $('#modal-detail').modal('show');
    })
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