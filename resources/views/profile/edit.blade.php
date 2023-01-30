@extends('layouts.app')

@section('header_styles')
<!-- Custom styles for this page -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
<!-- <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" /> -->
<!-- <link  href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet"/> -->
@endsection

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
        <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Edit Profile</h5>
                </div>
                <div class="card-body">
                    <form method="post" action="{{ url('/users/'. $user->uuid . '') }}" id="profile">
                        @csrf
                        @method('put')
                        <div class="form-group">
                            <label for="title" class="required">Nama</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name ="name" value="{{ $user->name }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="title" name="password">Password</label>
                            <input type="password" class="form-control @error('name') is-invalid @enderror" id="password" name ="password">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="title" name="password_confirmation">Konfirmasi Password</label>
                            <input type="password" class="form-control @error('name') is-invalid @enderror" id="passwordConfirmation" name ="password_confirmation">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <!-- <a class="btn btn-outline-primary mr-2" href="{{ url('/templates') }}">Batal</a> -->
                            <button class="btn btn-primary ms-2" id="update">Update</button>
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
<!-- <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script> -->
<script>
$(document).ready(function(){
    $('#update').click(function(e){
        e.preventDefault();
        var password = $('#password').val();
        var passwordConfirmation = $('#passwordConfirmation').val();
        if(password != passwordConfirmation){
            alert("Password tidak sama");
        } else {
            $('#profile').submit();
        }
    });       
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