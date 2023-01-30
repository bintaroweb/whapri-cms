@extends('layouts.app')

@section('header_styles')

<!-- Custom styles for this page -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link  href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet"/>
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">

@endsection

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
        <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Kirim WhatsApp</h5>
                </div>
                <div class="card-body">
                    <form method="post" action="{{ url('/messages') }}" id="send-message">
                        <input type="hidden" id="uuid" />
                        <input type="hidden" name="ack" id="ack" />
                        <input type="hidden" name="message_id" id="messageId" />
                        <input type="hidden" name="timestamp" id="timestamp" />
                        @csrf
                        <div class="form-group">
                            <label for="title" class="required" name="message" required>Isi Pesan</label>
                            <textarea class="form-control @error('message') is-invalid @enderror" name="message" id="message" rows="10">{{ old('message') }}</textarea>
                            <!-- <button class="btn btn-secondary btn-sm my-2" id="selectTemplate"><i class="fa-solid fa-file-import"></i> Salin dari template</button> -->
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" value="" id="selectTemplate">
                            <label class="form-check-label" for="selectTemplate">
                                Salin dari template
                            </label>
                        </div>
                        <div class="form-group d-none" id="formTemplate">
                            <label for="contact">Pilin Template Pesan</label>
                            <select name="template" id="template" class="form-control @error('template') is-invalid @enderror">
                                <option>-- Pilih --</option>
                                @foreach ($templates as $template)
                                    <option value="{{ $template-> uuid }}">{{ $template-> name }}</option>
                                @endforeach
                            </select>
                            @error('contact')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="contact" class="required">Nomor Tujuan</label>
                            <select name="receiver" id="receiver" class="form-control @error('receiver') is-invalid @enderror">
                            </select>
                            <!-- <input type="text" class="form-control @error('receiver') is-invalid @enderror" id="receiver" name ="receiver" value="{{ old('receiver') }}" onkeypress="return onlyNumberKey(event)" required> -->
                            @error('contact')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="contact" class="required">Perangkat</label>
                            <select name="device" id="device" class="form-control @error('device') is-invalid @enderror">
                                @foreach ($devices as $device)
                                    <option value="{{ $device-> uuid }}">{{ $device-> name }}</option>
                                @endforeach
                            </select>
                            @error('contact')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <a class="btn btn-outline-primary mr-2" href="{{ url('/messages') }}">Batal</a>
                            <button class="btn btn-primary ms-2" id="simpan">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="modal-tambah" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog" style="width: 500px">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Tambah Kontak Baru</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form method="post" action="{{ url('settings/salestype/store') }}">
      @csrf
        <div class="modal-body">
            <div class="form-group">
                <label for="name">Nama</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name ="name" value="{{ old('name') }}" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label for="phone">Telpon</label>
                <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name ="phone" value="{{ old('phone') }}" required>
                @error('phone')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="button" class="btn btn-primary" id="kontak">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/4.5.2/socket.io.js" integrity="sha512-VJ6+sp2E5rFQk05caiXXzQd1wBABpjEj1r5kMiLmGAAgwPItw1YpqsCCBtq8Yr1x6C49/mTpRdXtq8O2RcZhlQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    var socket = io.connect("{{ env('SOCKET_URL') }}", {path: '/socket.io', transports: ['websocket', 'polling', 'flashsocket']});
    
    $(document).ready(function(){
        $('#device').change();       
    })

    //Select2
    var path = "{{ url('/messages/autocomplete') }}";
    // $('#receiver').select2({theme: 'bootstrap-5'});
    $('#receiver').select2({
        theme: 'bootstrap-5',
        placeholder: 'Pilih kontak',
        ajax: {
          url: path,
          dataType: 'json',
          delay: 50,
          processResults: function (data) {
            return {
              results:  $.map(data, function (item) {
                    return {
                        text: item.name + ' (' + item.phone + ')',
                        id: item.phone
                    }
                })
            };
          },
          escapeMarkup: function (markup) {
            return markup;
          },
          cache: true
        }
    })
    .on('select2:open', function () {
        var a = $(this).data('select2');
        if (!$('.select2-link').length) {
            a.$results.parents('.select2-results')
                        .append('<div class="select2-link"><a class="link-primary" href="#">Tambah baru</a></div>')
                        .on('click', function (b) {
                            a.trigger('close');
                            $('#modal-tambah').modal('show');
                        });
        }
   });

    $('#device').change(function() {
        // console.log('Device diubah')
        var uuid = $(this).val();
        $('#uuid').val(uuid);
    })

    $('#kontak').click(function(){
        var name = $('#name').val();
        var phone = $('#phone').val();
        $.ajax({
            type: 'POST',
            url: "{{ url('/contacts') }}",
            data: {
                name: name, 
                phone: phone,
                _token: '{{csrf_token()}}'
            },
            success: function(result) { // Jika berhasil
                console.log(result)
                if(result.success == true){
                    var newStateVal = name + ' (' + phone + ')';
                    var newStateId = phone;
                    if ($("#receiver").find("option[value='" + newStateVal + "']").length) {
                        $("#receiver").val(newStateVal).trigger("change");
                    } else { 
                        // Create the DOM option that is pre-selected by default
                        var newState = new Option(newStateVal, newStateId, true, true);
                        // Append it to the select
                        $("#receiver").append(newState).trigger('change');
                    } 

                    $('#modal-tambah').modal('hide');
                    $('#name').val('');
                    $('#phone').val('');
                }
            }
        })
    })

    $('#selectTemplate').click(function(){
        if($(this).prop("checked") == true){
            $('#formTemplate').removeClass('d-none');        }
        else if($(this).prop("checked") == false){
            $('#formTemplate').addClass('d-none'); 
        }
    })

    $('#template').change(function(){
        var uuid = $(this).val();
        $.ajax({
            type: 'GET',
            url: "{{ url('/messages/template') }}",
            data: { 
                uuid: uuid
            },
            success: function(result) { 
                console.log(result)
                $('#message').val(result.template.message)
            }
        })
    })

    $('#simpan').click(function(e){
        e.preventDefault();
        $(this).attr('disabled', 'disabled');

        var message = $('#message').val();
        var receiver = $('#receiver').val();
        var device = $('#uuid').val();

        doAjax();

        async function doAjax(args) {
            let result;
            try {
                result = await $.ajax({
                    type: 'POST',
                    url: "{{ env('SOCKET_URL') }}/send-message",
                    data: { 
                        message: message, 
                        device: device,
                        receiver: receiver
                    },
                    success: function(result) { 
                        console.log(result)
                        if(result.status){
                            $('#messageId').val(result.response.id.id)
                            $('#ack').val(result.response.ack);
                            $('#timestamp').val(result.response.timestamp);
                            $("#send-message").submit();
                        } else {
                            toastr.options = {
                                "closeButton" : true,
                                "positionClass": "toast-bottom-right",
                            }
                            toastr.error("Pesan gagal dikirim");
                        }
                        
                    }
                })
                return result;
            } catch (error) {
                $('#simpan').prop("disabled", false);
                console.error(error);
            }
        }
    })
    
    function onlyNumberKey(evt) {
        // Only ASCII character in that range allowed
        var ASCIICode = (evt.which) ? evt.which : evt.keyCode
        if(ASCIICode > 31 && (ASCIICode < 48 || ASCIICode > 57)){
            return false;
        }
        return true;
    }
</script>
@endpush