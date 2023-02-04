@extends('layouts.app')

@section('header_styles')
<!-- Custom styles for this page -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
@endsection

@section('content')

<div class="container">
    <div class="row">
        <!-- <div class="col-md-6 step-one">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Tambah Perangkat</h5>
                </div>
                <div class="card-body">
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
                </div>
            </div>
        </div> -->
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mt-2">Pilih Paket</h5>
                </div>
                <div class="row mx-2 my-2 text-center">
                    <div class="col-md-3">
                        <div class="card mb-4 rounded-3 shadow-sm">
                            <div class="card-header py-3">
                                <h4 class="my-0 fw-normal">Trial</h4>
                            </div>
                            <div class="card-body">
                                <h5 class="card-title pricing-card-title"><span class="h3">Gratis</span><small class="text-muted fw-light">/7 hari</small></h5>
                                <ul class="list-unstyled mt-3 mb-4">
                                    <li><i class="fa-solid fa-check text-success"></i> Text Message</li>
                                    <li><i class="fa-solid fa-check text-success"></i> Broadcast Message</li>
                                    <li><i class="fa-solid fa-check text-success"></i> Unlimited Contact</li>
                                    <li><i class="fa-solid fa-xmark text-danger"></i> Image Message</li>
                                    <li><i class="fa-solid fa-xmark text-danger"></i> Document Message</li>
                                    <li><i class="fa-solid fa-xmark text-danger"></i> Link Message</li>
                                    <li><i class="fa-solid fa-check text-success"></i> Templates</li>
                                    <li><i class="fa-solid fa-check text-success"></i> Report Message</li>
                                </ul>
                                @if($trial == 0)
                                    <button type="button" class="w-100 btn btn-sm btn-outline-primary select-package" data-name="trial" data-price="0">Pilih</button>
                                @else 
                                    <button type="button" class="w-100 btn btn-sm btn-outline-secondary" disabled>Tambah</button>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card mb-4 rounded-3 shadow-sm">
                            <div class="card-header py-3">
                                <h4 class="my-0 fw-normal">Silver</h4>
                            </div>
                            <div class="card-body">
                                <h5 class="card-title pricing-card-title"><span class="h3">50rb</span><small class="text-muted fw-light">/bln</small></h5>
                                <ul class="list-unstyled mt-3 mb-4">
                                    <li><i class="fa-solid fa-check text-success"></i> Text Message</li>
                                    <li><i class="fa-solid fa-check text-success"></i> Broadcast Message</li>
                                    <li><i class="fa-solid fa-check text-success"></i> Unlimited Contact</li>
                                    <li><i class="fa-solid fa-xmark text-danger"></i> Image Message</li>
                                    <li><i class="fa-solid fa-xmark text-danger"></i> Document Message</li>
                                    <li><i class="fa-solid fa-xmark text-danger"></i> Link Message</li>
                                    <li><i class="fa-solid fa-xmark text-danger"></i> Templates</li>
                                    <li><i class="fa-solid fa-xmark text-danger"></i> Report Message</li>
                                </ul>
                                <button type="button" class="w-100 btn btn-sm btn-outline-primary select-package" data-name="silver" data-price="50000">Tambah</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card mb-4 rounded-3 shadow-smd">
                            <div class="card-header py-3">
                                <h4 class="my-0 fw-normal">Gold</h4>
                            </div>
                            <div class="card-body">
                                <h5 class="card-title pricing-card-title"><span class="h3">100rb</span><small class="text-muted fw-light">/bln</small></h5>
                                <ul class="list-unstyled mt-3 mb-4">
                                    <li><i class="fa-solid fa-check text-success"></i> Text Message</li>
                                    <li><i class="fa-solid fa-check text-success"></i> Broadcast Message</li>
                                    <li><i class="fa-solid fa-check text-success"></i> Unlimited Contact</li>
                                    <li><i class="fa-solid fa-check text-success"></i> Image Message</li>
                                    <li><i class="fa-solid fa-check text-success"></i> Document Message</li>
                                    <li><i class="fa-solid fa-xmark text-danger"></i> Link Message</li>
                                    <li><i class="fa-solid fa-xmark text-danger"></i> Templates</li>
                                    <li><i class="fa-solid fa-xmark text-danger"></i> Report Message</li>
                                    
                                </ul>
                                <button type="button" class="w-100 btn btn-sm btn-outline-primary select-package" data-name="gold" data-price="100000">Tambah</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card mb-4 rounded-3 shadow-sm border-primary">
                            <div class="card-header py-3">
                                <h4 class="my-0 fw-normal">Platinum</h4>
                            </div>
                            <div class="card-body">
                                <h5 class="card-title pricing-card-title"><span class="h3">150rb</span><small class="text-muted fw-light">/bln</small></h5>
                                <ul class="list-unstyled mt-3 mb-4">
                                    <li><i class="fa-solid fa-check text-success"></i> Text Message</li>
                                    <li><i class="fa-solid fa-check text-success"></i> Broadcast Message</li>
                                    <li><i class="fa-solid fa-check text-success"></i> Unlimited Contact</li>
                                    <li><i class="fa-solid fa-check text-success"></i> Image Message</li>
                                    <li><i class="fa-solid fa-check text-success"></i> Document Message</li>
                                    <li><i class="fa-solid fa-check text-success"></i> Link Message</li>
                                    <li><i class="fa-solid fa-check text-success"></i> Templates</li>
                                    <li><i class="fa-solid fa-check text-success"></i> Report Message</li>
                                </ul>
                                <button type="button" class="w-100 btn btn-sm btn-outline-primary select-package" data-name="platinum" data-price="150000">Tambah</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- <div class="row">
        <div class="col-md-12">
            <div class="form-group mt-3">
                <a class="btn btn-outline-primary mr-2" href="{{ url('/devices') }}">Batal</a>
                <button class="btn btn-primary ms-2" id="next">Selanjutnya</button>
            </div>
        </div>
    </div> -->
</div>

<!-- Modal Add Device -->
<div class="modal fade" id="modal-device" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
        <form method="POST" action="{{ url('/devices') }}">
            @csrf
            <input name="balance" id="balance" type="hidden" value="{{ $balance }}"/>
            <input name="package" id="package" type="hidden"/>
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Device Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="card-body">
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
                </div>
            </div>
            <div class="modal-footer">
                <!-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Bayar</button> -->
                <button type="submit" class="btn btn-primary" id="simpan">Simpan</button>
            </div>
        </form>
    </div>
  </div>
</div>

<!-- Modal Topup-->
<div class="modal fade" id="modal-topup" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Saldo Tidak Cukup</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-center">
        <p>Maaf, sisa saldo anda tidak cukup untuk membeli Paket ini. <br>
            Saldo ada saat ini sebesar Rp. <span class="fw-bold" id="amount"></span></p>
        <p>Silahkan topup terlebih dahulu saldo anda <a href="{{ url('/billings') }}">disini</a></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
        <!-- <button type="button" class="btn btn-primary" id="confirm-topup" disabled>Simpan</button> -->
      </div>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/4.5.2/socket.io.js" integrity="sha512-VJ6+sp2E5rFQk05caiXXzQd1wBABpjEj1r5kMiLmGAAgwPItw1YpqsCCBtq8Yr1x6C49/mTpRdXtq8O2RcZhlQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    $('.select-package').click(function(e){
        e.preventDefault();
        var package = $(this).attr('data-name');
        var price = Number($(this).attr('data-price'));
        var balance = Number($('#balance').val());

        if(price > balance){
            $('#amount').text(new Intl.NumberFormat('id-ID', { maximumSignificantDigits: 3 }).format(balance));
            $('#modal-topup').modal('show');
        } else {
            $('#modal-device').modal('show');
            $('#package').val(package);
            // $('#price').val(price);
        }
    })
    // var socket = io.connect("{{ env('SOCKET_URL') }}", {path: '/socket.io', transports: ['websocket', 'polling', 'flashsocket']});
    // $('#simpan').click(function(e){
    //     e.preventDefault();
    //     var deviceName = $('#name').val();
    //     var deviceNote = $('#note').val();
    //     var package = $('#package').val();

    //     $.ajax({
    //         type: 'POST', 
    //         url: "{{ url('/devices') }}", 
    //         data: { 
    //             uuid: '', 
    //             name: deviceName, 
    //             note: deviceNote,
    //             package: 'package',
    //             status: 'disconnected',
    //             _token: '{{csrf_token()}}'
    //         },
    //         success: function(result) { 
    //             toastr.options = {
    //                 "closeButton" : true,
    //                 "positionClass": "toast-bottom-right",
    //             }
    //             toastr.success("Perangkat berhasil ditambahkan");

    //             window.location.href = "{{ url('/devices') }}";
    //         }
    //     })

    //     // $('#scan').modal('show');

    //     // Soket IO
    //     // socket.emit('create-session', {
    //     //     id: '',
    //     //     desc: deviceName,
    //     // })

    //     // socket.on('qr', (data) => {
    //     //     // console.log(src);
    //     //     $('#qrcode').attr('src', data.src);
    //     // })

    //     // socket.on('message', (msg) => {
    //     //     // console.log(msg);
            
    //     //     var qr = $( "ul li:nth-child(1)" ).text().trim();
    //     //     var authenticated = $( "ul li:nth-child(2)" ).text().trim();
    //     //     var ready = $( "ul li:nth-child(3)" ).text().trim();
    //     //     if(qr != msg.text){
    //     //         $('.log').append('<li>' + msg.text +'</li>');
    //     //     }

    //     //     if(!empty(authenticated) && authenticated != msg.text){
    //     //         $('.log').append('<li>' + msg.text +'</li>');
    //     //     }  
            
    //     //     if(!empty(ready) && ready != msg.text){
    //     //         $('.log').append('<li>' + msg.text +'</li>');
    //     //     }  
    //     // })

    //     // socket.on('ready', () => {
    //     //     $.ajax({
    //     //         type: 'POST', // Metode pengiriman data menggunakan POST
    //     //         url: "{{ url('/devices') }}", // File yang akan memproses data
    //     //         data: { // Data yang akan dikirim ke file pemroses
    //     //             uuid: '', 
    //     //             name: deviceName, 
    //     //             note: deviceNote,
    //     //             status: 'Connected',
    //     //             _token: '{{csrf_token()}}'
    //     //         },
    //     //         success: function(result) { // Jika berhasil
    //     //             toastr.options = {
    //     //                 "closeButton" : true,
    //     //                 "positionClass": "toast-bottom-right",
    //     //             }
    //     //             toastr.success("Perangkat berhasil ditambahkan");

    //     //             window.location.href = "{{ url('/devices') }}";
    //     //         }
    //     //     })
    //     //     // console.log(msg);
    //     // })
    // })

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