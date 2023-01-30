@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
        <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Edit Kontak</h5>
                </div>
                <div class="card-body">
                    <form method="post" action="{{ url('/contacts/'. $contact->uuid . '') }}">
                        @csrf
                        @method('put')
                        <div class="form-group">
                            <label for="title" class="required">Nama</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name ="name" value="{{ $contact->name }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="contact" class="required">Kontak</label>
                            <input type="text" class="form-control @error('contact') is-invalid @enderror" id="phone" name ="phone" value="{{ $contact->phone }}" required>
                            @error('contact')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="contact">Label</label>
                            <input type="text" class="form-control @error('contact') is-invalid @enderror" id="label" name ="label" value="{{ $contact->label }}">
                            @error('contact')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <a class="btn btn-outline-primary mr-2" href="{{ url('/contacts') }}">Batal</a>
                            <button class="btn btn-primary ms-2" id="simpan">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection