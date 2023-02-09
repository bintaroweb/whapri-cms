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
                    <h5 class="card-title">Tambah Kontak</h5>
                </div>
                <div class="card-body">
                    <form method="post" action="{{ url('/contacts') }}">
                        @csrf
                        <div class="form-group">
                            <label for="title">Nama</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name ="name" value="{{ old('name') }}">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="title" class="required">Nomor</label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name ="phone" value="{{ old('phone') }}" onkeypress="return onlyNumberKey(event)" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="contact">Label</label>
                            <input type="text" class="form-control @error('contact') is-invalid @enderror" id="label" name ="label" value="{{ old('note') }}">
                            @error('contact')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <a class="btn btn-outline-primary mr-2" href="{{ url('/contacts') }}">Batal</a>
                            <button class="btn btn-primary ms-2" id="simpan">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')

<script>
function onlyNumberKey(evt) {
    // Only ASCII character in that range allowed
    var ASCIICode = (evt.which) ? evt.which : evt.keyCode
    if(ASCIICode > 31 && (ASCIICode < 48 || ASCIICode > 57)){
        return false;
    }
    return true;
}

var phone = document.getElementById('phone'),
    cleanPhoneNumber;

cleanPhoneNumber= function(e) {
    e.preventDefault();
    var pastedText = '';

    if (e.clipboardData && e.clipboardData.getData)
    {// Standards Compliant FIRST!
    pastedText = e.clipboardData.getData('text/plain');
    }
    else if (window.clipboardData && window.clipboardData.getData)
    {// IE
    pastedText = window.clipboardData.getData('Text');
    }

    this.value = pastedText.replace(/\D/g, '');
}

phone.onpaste = cleanPhoneNumber;
</script>

@endpush
