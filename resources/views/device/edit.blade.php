@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
        <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Ubah Teknisi</h5>
                </div>
                <div class="card-body">
                    <form method="post" action="{{ url('/technicians/'. $technician->uuid . '') }}">
                        @csrf
                        @method('put')
                        <div class="form-group">
                            <label for="title" class="required">Nama Teknisi</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name ="name" value="{{ $technician->name }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="contact" class="required">Kontak</label>
                            <input type="text" class="form-control @error('contact') is-invalid @enderror" id="contact" name ="contact" value="{{ $technician->contact }}" required>
                            @error('contact')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <a class="btn btn-outline-primary mr-2" href="{{ url('/technicians') }}">Batal</a>
                            <button class="btn btn-primary ms-2" id="simpan">Update</button>
                        </div>
                    </form>

                    <div class="col text-center">
                        <a class="btn-link" id="delete" href="#">
                            {{ __('Hapus Teknisi') }}
                        </a>
                    </div>
                    <form id="delete-form" action="{{ route('technicians.destroy', $technician->uuid) }}" method="POST" class="d-none">
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
        <h5 class="modal-title" id="exampleModalLabel">Hapus Teknisi</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      Anda yakin akan menghapus teknisi ini?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-danger" id="confirm-delete">Hapus</button>
      </div>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#delete').click(function(e){
           e.preventDefault();
           $('#modal-delete').modal('show');
        })

        $('#confirm-delete').click(function(){
            $('#delete-form').submit();
        })
    })
</script>
@endpush