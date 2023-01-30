@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
        <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Edit Karyawan</h5>
                </div>
                <div class="card-body">
                    <form method="post" action="{{ url('/users/'. $user->uuid . '') }}">
                        @csrf
                        @method('put')
                        <input type="hidden" name="user" value="{{ $user->id }}">
                        <div class="form-group">
                            <label for="role_id" class="required">Hak Akses Karyawan</label>
                            <select class="form-control" name="role_id" required>
                                <option value="">-- Pilih --</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->id }}" {{ $user->role_id === $role->id ? "selected" : "" }}>{{ $role->name }}</option>
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
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name ="name" value="{{ $user->name }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="email" class="required">Email</label>
                            <input type="text" class="form-control @error('email') is-invalid @enderror" id="email" name ="email" value="{{ $user->email }}" disabled>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="text" class="form-control @error('password') is-invalid @enderror" id="password" name ="password">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group" id="outlet">
                            <label for="outlet" class="required">Outlet</label>
                            @foreach ($outlets as $outlet)
                                <div class="form-check">
                                    <input class="form-check-input @error('outlet') is-invalid @enderror" type="checkbox" name="outlet[]" value="{{ $outlet->id }}" {{ in_array($outlet->id, $user->outlet) ? 'checked' : '' }}>
                                    <label class="form-check-label">
                                        {{ $outlet->name }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        <div class="form-group">
                            <a class="btn btn-outline-primary mr-2" href="{{ url('/users') }}">Batal</a>
                            <button type="submit" class="btn btn-primary ms-2">Update</button>
                        </div>
                    </form>
                    <div class="col text-center">
                        <a class="btn-link" id="delete" href="#">
                            {{ __('Hapus Karyawan') }}
                        </a>
                    </div>
                    <form id="delete-form" action="{{ route('users.destroy', $user->uuid) }}" method="POST" class="d-none">
                        @method('delete')    
                        @csrf
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Delete -->
<div class="modal fade" id="modal-delete" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Hapus Karyawan</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      Anda yakin akan menghapus karyawan <strong>{{ $user->name }}</strong>?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-danger" id="confirm-delete">Hapus</button>
      </div>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $(document).ready(function() {
        $('#delete').click(function(e){
           e.preventDefault();
           $('#modal-delete').modal('show');
        })

        $('#confirm-delete').click(function(){
            // document.getElementById('logout-form').submit();
            $('#delete-form').submit();
        })
    })
    })
</script>
@endpush