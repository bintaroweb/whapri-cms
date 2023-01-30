@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
        <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Tambah Karyawan Baru</h5>
                </div>
                <div class="card-body">
                    <form method="post" action="{{ url('/users') }}">
                        @csrf
                        <div class="form-group">
                            <label for="role_id" class="required">Hak Akses Karyawan</label>
                            <select class="form-control" name="role_id" required>
                                <option value="">-- Pilih  --</option>
                                @php
                                    $old_role = old('role_id');
                                @endphp
                            @foreach ($roles as $role)
                            <option value="{{ $role->id }}" @if($role->id == $old_role) selected @endif >{{ $role->name }}</option>
                            @endforeach
                            </select>
                            @error('role_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <!-- <div class="alert alert-primary align-items-center d-none" id="super-admin" role="alert">
                            <div>
                                <i class="fas fa-info-circle"></i>
                                <strong>Super Admin</strong> memiliki hak akses penuh seperti akun utama
                            </div>
                        </div> -->
                        <div class="form-group">
                            <label for="title" class="required">Nama</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name ="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="email" class="required">Email</label>
                            <input type="text" class="form-control @error('email') is-invalid @enderror" id="email" name ="email" value="{{ old('email') }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="password" class="required">Password</label>
                            <input type="text" class="form-control @error('password') is-invalid @enderror" id="password" name ="password" value="{{ old('password') }}">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group" id="outlet">
                            <label for="outlet" class="required">Outlet</label>
                            @foreach ($outlets as $outlet)
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="outlet[]" value="{{ $outlet->id}}">
                                    <label class="form-check-label">
                                        {{ $outlet->name }}
                                    </label>
                                </div>
                            @endforeach
                            @error('outlet')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            <div class="alert alert-primary align-items-center d-none mt-2" id="alert" role="alert">
                                <div>
                                    <i class="fas fa-info-circle"></i>
                                    Kamu harus ceklis minimal satu outlet
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <a class="btn btn-outline-primary mr-2" href="{{ url('/users') }}">Batal</a>
                            <button type="submit" class="btn btn-primary ms-2" id="simpan">Simpan</button>
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
    $(document).ready(function() {
        $('#simpan').click(function() {
            checked = $("input[type=checkbox]:checked").length;

            if(!checked) {
                $('#alert').removeClass("d-none");
                $('#alert').show();
                return false;
            }
        });
        // $('select').on('change', function () {
        //     var optionSelected = $(this).find("option:selected");
        //     var valueSelected  = optionSelected.val();
        //     if(valueSelected === '1'){
        //         $('#super-admin').removeClass("d-none");
        //         $('#super-admin').show();
        //         $('#outlet').hide();
        //     } else {
        //         $('#super-admin').hide();
        //         $('#outlet').show();
        //     }
           
        // })

        // $('#confirm-delete').click(function(){
        //     $('#delete-form').submit();
        // })
    })
</script>
@endpush