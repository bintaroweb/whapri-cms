@extends('layouts.app')

@section('header_styles')
<!-- Custom styles for this page -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link  href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet"/>
@endsection

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
        <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Tambah Group</h5>
                </div>
                <div class="card-body">
                    <form method="post" action="{{ url('/groups') }}">
                        @csrf
                        <div class="form-group">
                            <label for="title" class="required">Nama</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name ="name" value="{{ old('name') }}">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="title" class="required">Pilih Kontak (maksimal 25 nomor)</label>
                            <select id="phones" name="phones[]" multiple="multiple" class="form-control @error('phone') is-invalid @enderror">
                            </select>
                            @error('phones')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="contact">Label</label>
                            <input type="text" class="form-control @error('contact') is-invalid @enderror" id="label" name ="label" value="{{ old('label') }}">
                            @error('label')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <a class="btn btn-outline-primary mr-2" href="{{ url('/groups') }}">Batal</a>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    var path = "{{ url('/groups/autocomplete') }}";
    $('#phones').select2({
        theme: 'bootstrap-5',
        placeholder: 'Pilih kontak',
        closeOnSelect : false,
        maximumSelectionLength: 25,
        ajax: {
          url: path,
          dataType: 'json',
          delay: 50,
          processResults: function (data) {
            return {
              results:  $.map(data, function (item) {
                if(item.name.length === 0){
                    return {
                        text: item.phone,
                        id: item.uuid
                    }
                } else {
                    return {
                        text: item.name + ' (' + item.phone + ')',
                        id: item.uuid
                    }
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
</script>
@endpush