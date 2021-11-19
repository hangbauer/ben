@extends('layout')

@section('plugins')
  <link rel="stylesheet" href="{{ asset('bower_components/select2/dist/css/select2.min.css') }}">
  <link rel="stylesheet" href="{{ asset('bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
  <style>
    .select2-container *:focus {
        border-color: #3c8dbc !important;
    }
</style>
@endsection

@section('content')
	<div class="box box-primary">
    <div class="box-header with-border">
    	@if(isset($cashierin))
        <h3 class="box-title">Ubah</h3>
      @else
        <h3 class="box-title">Tambah Baru</h3>
      @endif
    </div>
    <!-- /.box-header -->
    <!-- form start -->
    <form role="form" method="POST" id="form-add" action="{{ isset($cashierin['cashierinMas']) ? url('cashierin/'.$cashierin['cashierinMas'][0]->id) : url('cashierin') }}">
      {!! csrf_field() !!}

      @if(isset($cashierin))
        {{ method_field('PUT') }}
      @endif

      <div class="box-body">
        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              <label for="code">Nomor Pembayaran</label>
              <input type="text" class="form-control" name="cashierinno" value="{{ isset($cashierin['cashierinMas']) ? $cashierin['cashierinMas'][0]->cashierinno : ''}}" readonly>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label>Masuk Ke</label>
              <select class="form-control select2" style="width: 100%;" name="bankid">
                <option value=""></option>
                @foreach($dropDownBank as $bank)
                  <option value="{{ $bank->id }}" {{ $bank->id == (isset($cashierin['cashierinMas']) ? $cashierin['cashierinMas'][0]->bankid : '') ? 'selected' : '' }}>{{ $bank->name }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label for="code">Tanggal Pembayaran</label>
              <div class="input-group date">
                <div class="input-group-addon">
                  <i class="fa fa-calendar"></i>
                </div>
                <input type="text" class="form-control pull-right" id="datepicker" name="cashierindate" value="{{ isset($cashierin['cashierinMas']) ? date('d-M-Y', strtotime($cashierin['cashierinMas'][0]->cashierindate)) : date('d-M-Y') }}">
              </div>
              <!-- /.input group -->
            </div>
            <!-- /.form group -->
          </div>
        </div>
        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              <label for="invoicetypeid">Tipe Invoice</label>
              <select class="form-control select2" style="width: 100%;" name="invoicetypeid">
                <option value="0" {{ 0 == (isset($invoice['invoiceMas']) ? $invoice['invoiceMas'][0]->invoicetypeid : 0) ? 'selected' : '' }}>Pengirim</option>
                <option value="1" {{ 1 == (isset($invoice['invoiceMas']) ? $invoice['invoiceMas'][0]->invoicetypeid : 0) ? 'selected' : '' }}>Penerima</option>
              </select>
            </div>
          </div>
          <div class="col-md-4 div-senderid">
            <div class="form-group">
              <label>Nama Pengirim</label>
              <select class="form-control select2" style="width: 100%;" name="senderid">
                <option value=""></option>
                @foreach($dropDownSender as $sender)
                  <option value="{{ $sender->id }}" {{ $sender->id == (isset($cashierin['cashierinMas']) ? $cashierin['cashierinMas'][0]->senderid : '') ? 'selected' : '' }}>{{ $sender->name }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="col-md-4 div-recipientid">
            <div class="form-group">
              <label>Nama Penerima</label>
              <select class="form-control select2" style="width: 100%;" name="recipientid">
                <option value=""></option>
                @foreach($dropDownRecipient as $recipient)
                  <option value="{{ $recipient->id }}" {{ $recipient->id == (isset($cashierin['cashierinMas']) ? $cashierin['cashierinMas'][0]->recipientid : '') ? 'selected' : '' }}>{{ $recipient->name }}</option>
                @endforeach
              </select>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-8">
            <div class="form-group">
              <label for="code">Keterangan</label>
              <input type="text" class="form-control" name="note" value="{{ isset($cashierin['cashierinMas']) ? $cashierin['cashierinMas'][0]->note : ''}}" placeholder="Masukkan Keterangan">
            </div>
          </div>
        </div>
        <h4>Detail</h4>
        <table class="table" id="table-detail">
          <thead>
            <tr>
              <th>Nomor Invoice</th>
              <th>Tanggal</th>
              <th>keterangan</th>
              <th class="text-right">Jumlah</th>
              <th class="text-right">Jumlah Yang Di Bayar</th>
            </tr>
          </thead>
          <tbody>
            @if(isset($cashierin['cashierinDtl']))
              @foreach($cashierin['cashierinDtl'] as $dtl)
                <tr>
                  <input type="hidden" class="dtlid" name="dtlid[]" value="{{ $dtl->id }}">
                  <input type="hidden" class="invoicemasid" name="invoicemasid[]" value="{{ $dtl->invoicemasid }}">
                  <td>
                    {{ $dtl->invoiceno }}
                  </td>
                  <td>
                    {{ $dtl->invdate }}
                  </td>
                  <td>
                    {{ $dtl->note }}
                  </td>
                  <td class="text-right">
                    {{ number_format($dtl->amount,0) }}
                  </td>
                  <td>
                    <input type="text" class="form-control text-right autonumeric amount" name="amount[]" value="{{ $dtl->amount }}">
                  </td>
                </tr>
              @endforeach
            @endif
          </tbody>
          <tfoot>
            <tr>
              <td>

              </td>
              <td>

              </td>
              <td>

              </td>
              <td class="text-right">
                Grand Total
              </td>
              <td>
                <input type="text" class="form-control text-right autonumeric" name="grandtotal" value="{{ isset($cashierin['cashierinMas']) ? $cashierin['cashierinMas'][0]->amount : '0'}}" readonly>
              </td>

            </tr>
          </tfoot>
        </table>
      </div>
      <!-- /.box-body -->

      <div class="overlay" style="display: none;">
        <i class="fa fa-refresh fa-spin"></i>
      </div>

      <div class="box-footer">
        <button type="button" class="btn btn-primary" id="btnsubmit">Simpan</button>
      </div>
    </form>
  </div>
@endsection

@section('scripts')
  <script src="{{ asset('bower_components/select2/dist/js/select2.full.min.js') }}"></script>
  <script src="{{ asset('bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
  <script src="{{ asset('bower_components/autonumeric/autoNumeric.js') }}"></script>

  <script>
    $(function() {
      init();

      $('select[name=senderid], select[name=recipientid]').on('change', function(){
        getUnpaidList();
      });

      $('#table-detail').on('change', '.amount',function(){
        calculate();
      });

      $('#btnsubmit').on('click',function(){
        $(this).text('Harap Tunggu ...')
          .attr('disabled','disabled');
        $('#form-add').submit();
      });

      $('select[name=invoicetypeid]').on('change', function(){
        switch($(this).val()){
          case '0': //sender
            $('.div-recipientid').hide();
            $('.div-senderid').show();

            break;
          case '1': //recipient
            $('.div-senderid').hide();
            $('.div-recipientid').show();

            break;
          default:

            break;
        }
      });

      $('select[name=invoicetypeid]').trigger('change');
    });

    function init(){
      $('.select2').select2();

      $('#datepicker').datepicker({
        format: "dd-M-yyyy",
        autoclose: true
      });

      $('.autonumeric').autoNumeric({mDec:0});

      $('select[name=bankid]').focus();
    }

    function calculate(){
      var grandtotal = 0;
      $('.amount').each(function(){
          var amount = $(this).closest('tr').find('.amount').autoNumeric('get');
          grandtotal += parseFloat(amount);
      });

      $('input[name=grandtotal]').autoNumeric('set', grandtotal);
    }

    function getUnpaidList(){
      var req = $.ajax({
          type: "POST",
          url: "{{ url('invoice/get-unpaidlist') }}",
          data: {
            _token: $('input[name=_token]').val(),
            invoicetypeid: $('select[name=invoicetypeid]').val(),
            senderid: $('select[name=senderid]').val(),
            recipientid: $('select[name=recipientid]').val(),
          },
          beforeSend: function( xhr ) {
              $('.overlay').show();
          }
      });

      req.done(function(data){
        $('.overlay').hide();
        $('#table-detail').find('tbody').html(data);

        $('.autonumeric').autoNumeric({mDec:0});;
      });

      req.fail(function(jqXHR, textStatus){
          $('.overlay').hide();
          alert( "Error" );
      });
    }

    function setLock(){
      // console.log($('.domasid').length);
      if($('.domasid').length){
        $('select[name=invoicetypeid]').prop('disabled', true);
        $('select[name=senderid]').prop('disabled', true);
        $('select[name=recipientid]').prop('disabled', true);
      }else{
        $('select[name=invoicetypeid]').prop('disabled', false);
        $('select[name=senderid]').prop('disabled', false);
        $('select[name=recipientid]').prop('disabled', false);
      }
    }

  </script>
@endsection
