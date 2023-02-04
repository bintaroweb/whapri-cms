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
        <h3 class="h3 mb-3 text-gray-800">Perangkat</h3>
    </div>
    <div class="col-md-2 d-md-flex justify-content-md-end">
        <a href="{{ url('/devices/create') }}" class="btn btn-primary btn-md mb-3" role="button"><i class="fas fa-plus"></i> Tambah</a>
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
                        <th>Nama Perangkat</th>
                        <th>Keterangan</th>
                        <th>Status</th>
                        <th>Masa Aktif</th>
                        <th></th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<!-- Modal Scan -->
<div class="modal" id="scan" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Scan QR Code</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
          <div class="row">
              <div class="col-md-6">
                <ol>
                    <li>Tunggu sampai QR Code tampil</li>
                    <li>Buka <strong>WhatsApp</strong> di telepon Anda</li>
                    <li>Ketuk menu <strong>Setelan</strong> lalu pilih <strong>Perangkat tertaut</strong></li>
                    <li>Ketuk <strong>tautkan perangkat</strong></li>
                    <li>Arahkan telepon Anda ke layar ini untuk memindai kode tersebut</li>
                    <li>Tunggu sampai muncul keterangan <strong>WhatsApp is ready</strong></li>
                </ol>
            </div>
              <div class="col-md-6">
                <div class="form-group">
                    <img src="" id="qrcode" width="300px"/>
                    <ul class="log"></ul>
                </div>
              </div>
          </div>
        
       
      </div>
      <!-- <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div> -->
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script src="{{ asset('vendor/datatables/jquery.dataTables.min.js') }} "></script>
<script src="{{ asset('vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/4.5.2/socket.io.js" integrity="sha512-VJ6+sp2E5rFQk05caiXXzQd1wBABpjEj1r5kMiLmGAAgwPItw1YpqsCCBtq8Yr1x6C49/mTpRdXtq8O2RcZhlQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script>
    var socket = io.connect("{{ env('SOCKET_URL') }}", {path: '/socket.io', transports: ['websocket', 'polling', 'flashsocket']});

    $(document).ready(function() {
        $('#dataTable').DataTable( {
            processing: true,
            serverSide: true,
            ordering: false,
            bPaginate: false,
            bInfo: false,
            ajax: "{{ url('devices/datatable') }}",
            columns: [
                { data: "name", className: "name"},
                { data: "note", className: "note"},
                { "mRender": function ( data, type, row ) 
                    {
                        if(row.status == 'disconnected'){
                            return '<span class="badge text-bg-danger text-capitalize">'+ row.status +'</span>';
                        } else if(row.status == 'connected'){
                            return '<span class="badge text-bg-success text-capitalize">'+ row.status +'</span>';
                        } else {
                            return '<span class="badge text-bg-danger text-capitalize">'+ row.status +'</span>';
                        }
                    }
                },
                { data: "active_period", className: "active_period"},
                { "mRender": function ( data, type, row ) 
                    {
                        if(row.status == 'disconnected'){
                            return '<div class="d-md-flex justify-content-md-end"><button class="btn btn-primary btn-sm me-2 scan" data-uuid="'+ row.uuid +'"><i class="fa-sharp fa-solid fa-qrcode"></i> Scan</button><a href="{{url("devices")}}/'+row.uuid+'/edit" class="btn btn-secondary btn-sm btn-edit"><i class="fa-solid fa-pen-to-square"></i></a></div>';
                        } else if(row.status == 'connected'){
                            return '<div class="d-md-flex justify-content-md-end"><a href="{{url("devices")}}/'+row.uuid+'/edit" class="btn btn-secondary btn-sm btn-edit"><i class="fa-solid fa-pen-to-square"></i></a></div>';
                        } else {
                            return '<div class="d-md-flex justify-content-md-end"><button class="btn btn-primary btn-sm me-2"><i class="fa-solid fa-arrows-rotate"></i> Renew</button><a href="{{url("devices")}}/'+row.uuid+'/edit" class="btn btn-secondary btn-sm btn-edit"><i class="fa-solid fa-pen-to-square"></i></a></div>';
                        }
                        
                    }
                }
            ], 
            language: {
                processing: "Mohon tunggu ..."
            }
        });

        $( document ).on("click", ".scan", function(e){
            e.preventDefault();
            var uuid = $(this).attr('data-uuid');
            $.ajax({
                type: 'GET', 
                url: "{{ url('/devices') }}/"+ uuid +"", 
                success: function(result) { 
                    $('#scan').modal('show');

                    // Soket IO
                    socket.emit('create-session', {
                        id: result.device.uuid,
                        desc: result.device.name,
                    })

                    socket.on('qr', (data) => {
                        $('#qrcode').attr('src', data.src);
                    })

                    socket.on('message', (msg) => {                        
                        var qr = $( "ul li:nth-child(1)" ).text().trim();
                        var authenticated = $( "ul li:nth-child(2)" ).text().trim();
                        var ready = $( "ul li:nth-child(3)" ).text().trim();
                        if(qr != msg.text){
                            $('.log').append('<li>' + msg.text +'</li>');
                        }

                        if(!empty(authenticated) && authenticated != msg.text){
                            $('.log').append('<li>' + msg.text +'</li>');
                        }  
                        
                        if(!empty(ready) && ready != msg.text){
                            $('.log').append('<li>' + msg.text +'</li>');
                        }  
                    })

                    socket.on('ready', () => {
                        $.ajax({
                            type: 'POST', 
                            url: "{{ url('/devices/status') }}", 
                            data: { 
                                uuid: result.device.uuid,
                                status: 'connected',
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(result) { 
                                if(result){
                                    toastr.options = {
                                        "closeButton" : true,
                                        "positionClass": "toast-bottom-right",
                                    }
                                    toastr.success("Perangkat berhasil terhubung");
                                    $('#scan').modal('hide');
                                    $('#dataTable').DataTable().clear().draw();
                                } else {
                                    toastr.options = {
                                        "closeButton" : true,
                                        "positionClass": "toast-bottom-right",
                                    }
                                    toastr.error("Perangkat gagal terhubung");
                                }
                            }
                        })
                    })

                }
            })
        })
    });
    
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
    
    @if(Session::has('error'))
        toastr.options =
        {
            "closeButton" : true,
            "positionClass": "toast-bottom-right",
        }
        toastr.error("{{ session('error') }}");
    @endif
</script>

@endpush