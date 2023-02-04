@extends('layouts.app')

@section('header_styles')
<!-- Custom styles for this page -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
@endsection

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
        <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Tambah Perangkat</h5>
                </div>
                <div class="card-body">
                    <form method="post">
                        @csrf
                        <div class="form-group">
                            <label for="title" class="required">Nama</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name ="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="contact">Keterangan</label>
                            <input type="text" class="form-control @error('contact') is-invalid @enderror" id="note" name ="note" value="{{ old('note') }}">
                            @error('contact')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <a class="btn btn-outline-primary mr-2" href="{{ url('/devices') }}">Batal</a>
                            <button class="btn btn-primary ms-2" id="simpan">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

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
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/4.5.2/socket.io.js" integrity="sha512-VJ6+sp2E5rFQk05caiXXzQd1wBABpjEj1r5kMiLmGAAgwPItw1YpqsCCBtq8Yr1x6C49/mTpRdXtq8O2RcZhlQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    var socket = io.connect("{{ env('SOCKET_URL') }}", {path: '/socket.io', transports: ['websocket', 'polling', 'flashsocket']});
    $('#simpan').click(function(e){
        e.preventDefault();
        var deviceName = $('#name').val();
        var deviceNote = $('#note').val();

        $('#scan').modal('show');

        // Soket IO
        socket.emit('create-session', {
            id: '{{ $uuid }}',
            desc: deviceName,
        })

        socket.on('qr', (data) => {
            // console.log(src);
            $('#qrcode').attr('src', data.src);
        })

        socket.on('message', (msg) => {
            // console.log(msg);
            
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
                type: 'POST', // Metode pengiriman data menggunakan POST
                url: "{{ url('/devices') }}", // File yang akan memproses data
                data: { // Data yang akan dikirim ke file pemroses
                    uuid: '{{ $uuid }}', 
                    name: deviceName, 
                    note: deviceNote,
                    status: 'Connected',
                    _token: '{{csrf_token()}}'
                },
                success: function(result) { // Jika berhasil
                    toastr.options = {
                        "closeButton" : true,
                        "positionClass": "toast-bottom-right",
                    }
                    toastr.success("Perangkat berhasil ditambahkan");

                    window.location.href = "{{ url('/devices') }}";
                }
            })
            // console.log(msg);
        })
    })
    
    
    
    // function onlyNumberKey(evt) {
    //     // Only ASCII character in that range allowed
    //     var ASCIICode = (evt.which) ? evt.which : evt.keyCode
    //     if(ASCIICode > 31 && (ASCIICode < 48 || ASCIICode > 57)){
    //         return false;
    //     }
    //     return true;
    // }
</script>
@endpush