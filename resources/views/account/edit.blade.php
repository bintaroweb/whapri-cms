@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
        <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Edit Profile Akun</h4>
                </div>
                <div class="card-body">
                    <form method="post" action="{{ url('/account/update') }}">
                        @csrf
                        <div class="form-group">
                            <label for="title">Nama*</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name ="name" value="{{ Auth::user()->name }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="title">Jenis Kelamin*</label>
                            <select class="form-control @error('gender') is-invalid @enderror" name="gender" required>
                                <option value="">-- Pilih  --</option>
                                <option value="l" {{ Auth::user()->gender == 'l'  ? 'selected' : ''}}>Laki-laki</option>
                                <option value="p" {{ Auth::user()->gender == 'p'  ? 'selected' : ''}}>Perempuan</option>
                            </select>
                            @error('gender')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="phone">Telepon*</label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name ="phone" value="{{ Auth::user()->phone }}" required>
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="email" class="required">Email*</label>
                            <input type="text" class="form-control @error('email') is-invalid @enderror" id="email" name ="email" value="{{ Auth::user()->email }}" disabled>
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
                        <div class="form-group">
                            <a class="btn btn-outline-primary mr-2" href="{{ url('/settings') }}">Kembali</a>
                            <button type="submit" class="btn btn-primary ms-2">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection



