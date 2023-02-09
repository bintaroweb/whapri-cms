@extends('layouts.app')

@section('header_styles')

<!-- Custom styles for this page -->
<link href="{{ asset('vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
@if(env('MIDTRANS_PRODUCTION') == true)
<script type="text/javascript"
      src="https://app.midtrans.com/snap/v1/transactions"
      data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
@else 
<script type="text/javascript"
      src="https://app.sandbox.midtrans.com/snap/snap.js"
      data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
@endif

@endsection

@section('content')

<div class="row">
    <div class="col-md-10">
        <!-- Page Heading -->
        <h3 class="h3 mb-3">Billing</h3>
    </div>
    <div class="col-md-2 d-md-flex justify-content-md-end">
        <a href="#" class="btn btn-primary btn-md mb-3" role="button" id="topup"><i class="fas fa-plus"></i> Tambah Saldo</a>
    </div>
</div>


<div class="row">
    <div class="col-md-12">
        <div class="card shadow mb-4">
            <!-- <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">History Topup</h6>
            </div> -->
            <div class="card-body mx-3 mt-2">
                <h4>Saldo</h4>
                <h3 class="fw-bold mb-3">Rp. {{ number_format($balance, '0', ',', '.') }}</h3>
                <!-- <a href="#" id="topup" class="btn btn-primary">Topup</a> -->
                <a href="{{ url('billings/transaction') }}" class="btn btn-primary btn-sm">List Transaksi</a>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <!-- <h6>History Topup</h6> -->
                    <table class="table billing" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Invoice</th>
                                <th>Nominal</th>
                                <th>Metode Pembayaran</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Topup-->
<div class="modal fade" id="modal-popup" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Topup</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <label for="amount" class="required">Masukan nominal*</label>
        <input type="text" class="form-control" name="amount" id="amount" onkeypress="return onlyNumberKey(event)" />
        <div class="invalid-feedback mb-3">Nominal topup minimal Rp. 10.000</div>
      </div>
      <div class="modal-footer">
        <!-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Bayar</button> -->
        <button type="button" class="btn btn-primary" id="confirm-topup" disabled>Simpan</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal View Detail -->
<div class="modal fade" id="modal-detail" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Detail Pembayaran</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="payment">
            <table>
                <tr>
                    <td>No Invoice</td>
                    <td class="px-2">:</td>
                    <td>#<span id="invoice"></span></td>
                </tr>
                <tr>
                    <td>Waktu Pembayaran</td>
                    <td class="px-2">:</td>
                    <td><span id="settlement_time"></span></td>
                </tr>
                <tr>
                    <td>Metode Pembayaran</td>
                    <td class="px-2">:</td>
                    <td><span id="payment_method" class="text-capitalize"></span></td>
                </tr>
                <tr>
                    <td>Nominal</td>
                    <td class="px-2">:</td>
                    <td><span id="nominal"></span></td>
                </tr>
                <tr>
                    <td>Status</td>
                    <td class="px-2">:</td>
                    <td><span id="status" class="badge text-bg-success text-capitalize"></span></td>
                </tr>
            </table>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Delete -->
<div class="modal fade" id="modal-delete" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Konfirmasi</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      Apakah kamu ingin menghapus data billing ini?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-danger" id="confirm-delete">Hapus</button>
      </div>
    </div>
  </div>
</div>

<form id="delete-form" action="" method="POST" class="d-none">
    @method('delete')    
    @csrf
</form>

@endsection

@push('scripts')
<script src="{{ asset('vendor/datatables/jquery.dataTables.min.js') }} "></script>
<script src="{{ asset('vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
    $(document).ready(function() {
        $('#dataTable').DataTable( {
            processing: true,
            serverSide: true,
            ordering: false,
            bPaginate: false,
            bInfo: false,
            drawCallback: function () {
                $('[data-bs-toggle="tooltip"]').tooltip();
            },
            ajax: "{{ url('billings/datatable') }}",
            columns: [
                { data:"date", className:"date"},
                { "mRender": function ( data, type, row ) 
                    {
                        return '#'+ row.id +'';
                    }
                },
                { data:"amount", className:"amount"},
                { data:"payment_method", className:"payment_method text-capitalize"},
                { "mRender": function ( data, type, row ) 
                    {
                        if(row.status == 'paid'){
                            return '<span class="badge text-bg-success text-capitalize">'+ row.status +'</span>';
                        } else {
                            return '<span class="badge text-bg-warning text-capitalize">'+ row.status +'</span>';
                        }
                        
                    }
                },
                { "mRender": function ( data, type, row ) 
                    {
                        if(row.status == 'paid'){
                            return '<div class="d-md-flex justify-content-md-end"><a href="#" class="btn btn-secondary detail btn-sm" data-uuid="'+row.uuid+'" style="--bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .75rem;"><i class="fa-solid fa-eye"></i></a></div>';
                        } else {
                            return '<div class="d-md-flex justify-content-md-end"><a href="#" class="btn btn-primary btn-sm pay mx-2" data-uuid="'+row.uuid+'" style="--bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .75rem;">Bayar</a> <a href="#" class="btn btn-danger btn-sm delete" data-uuid="'+row.uuid+'" style="--bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .75rem;"><i class="fa-solid fa-trash"></i></a></div>';
                        } 
                    }
                }
            ], 
            language: {
                processing: "Mohon tunggu ..."
            }
        });

        $('#topup').click(function(e){
            e.preventDefault();
            $('#modal-popup').modal('show');
        })

        $(document).on("keyup", "#amount", function(evt){
            if($('#amount').val() < 10000) {
                $(".invalid-feedback").addClass("d-block");
                $("#confirm-topup").attr("disabled", true);
            } else {
                $(".invalid-feedback").removeClass("d-block");
                $("#confirm-topup").attr("disabled", false);
            }
        })

        $('#confirm-topup').click(function(){
            var amount = $('#amount').val();
            $.ajax({
                type: 'POST',
                url: "{{ url('/billings') }}",
                data: {
                    amount: amount, 
                    _token: '{{csrf_token()}}'
                },
                success: function(result) {
                    if(result.success){
                        $('#modal-popup').modal('hide');
                        toastr.options = {
                            "closeButton" : true,
                            "positionClass": "toast-bottom-right",
                        }
                        toastr.success("Invoice topup berhasil dibuat");
                        $('#dataTable').DataTable().clear().draw();
                        $('#amount').val('');
                    }
                }
            })
        })

        $( document ).on("click", ".pay", function(e){
            e.preventDefault();
            var uuid = $(this).data('uuid');
            $.ajax({
                type: 'POST',
                url: "{{ url('/billings/payment') }}",
                data: {
                    uuid: uuid,
                    _token: '{{csrf_token()}}'
                },
                success: function(result) { 
                    if(result.success){
                        window.snap.pay(result.token);
                    }
                }
            })
        })

        $( document ).on("click", ".detail", function(e){
            e.preventDefault();
            var uuid = $(this).data('uuid');
            $.ajax({
                type: 'GET',
                url: "{{ url('/billings/detail') }}",
                data: { 
                    uuid: uuid, 
                },
                success: function(result) { 
                    console.log(result);
                    $('#invoice').text(result.billing.id)
                    $('#payment_method').text(result.billing.payment_method)
                    $('#settlement_time').text(result.billing.settlement_time)
                    $('#status').text(result.billing.status)
                    $('#nominal').text(result.billing.amount)
                    $('#modal-detail').modal('show');
                }
            })
            // $('#modal-detail').modal('show');
        })

        $( document ).on("click", ".delete", function(e){
            e.preventDefault();
            var uuid = $(this).data('uuid');
            $('#delete-form').attr('action', '{{ url("/billings") }}/'+uuid+'');
            $('#modal-delete').modal('show');
        })

        $('#confirm-delete').click(function(){
            $('#delete-form').submit();
        })
    });

    function onlyNumberKey(evt) {
        // Only ASCII character in that range allowed
        var ASCIICode = (evt.which) ? evt.which : evt.keyCode
        if (ASCIICode == 46) {
            return false;
        } else if(ASCIICode > 31 && (ASCIICode < 48 || ASCIICode > 57)){
            return false;
        }
        return true;
    }

    const queryString = window.location.search;
    const urlParams = new URLSearchParams(queryString);
    const payment = urlParams.get('payment');

    if(payment == 'success'){
        toastr.options =
        {
            "closeButton" : true,
            "positionClass": "toast-bottom-right",
        }
        toastr.success("Pembayaran berhasil");
    } else if(payment == 'failed'){
        toastr.options =
        {
            "closeButton" : true,
            "positionClass": "toast-bottom-right",
        }
        toastr.error("Pembayaran gagal");
    }

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