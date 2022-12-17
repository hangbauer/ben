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
    	@if(isset($invoice['invoiceMas']))
        <h3 class="box-title">Ubah</h3>
      @else
        <h3 class="box-title">Tambah Baru</h3>
      @endif
    </div>
    <!-- /.box-header -->
    <!-- form start -->
    <form role="form" method="POST" id="form-add" action="{{ isset($invoice['invoiceMas']) ? url('invoice/'.$invoice['invoiceMas'][0]->id) : url('invoice') }}">
      {!! csrf_field() !!}

      @if(isset($invoice['invoiceMas']))
        {{ method_field('PUT') }}
      @endif

      <input type="hidden" name="id" value="{{ isset($invoice['invoiceMas']) ? $invoice['invoiceMas'][0]->id : '0'}}">

      <div class="box-body">
        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              <label for="invoiceno">No. Invoice</label>
              <input type="text" class="form-control" name="invoiceno" value="{{ isset($invoice['invoiceMas']) ? $invoice['invoiceMas'][0]->invoiceno : ''}}" placeholder="Masukkan Nomor Invoice" autocomplete="off">
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label for="invoicedate">Tanggal Invoice</label>
              <div class="input-group date">
                <div class="input-group-addon">
                  <i class="fa fa-calendar"></i>
                </div>
                <input type="text" class="form-control pull-right" id="invoicedate" name="invoicedate" value="{{ isset($invoice['invoiceMas']) ? date('d-M-Y', strtotime($invoice['invoiceMas'][0]->invoicedate)) : date('d-M-Y') }}">
              </div>
              <!-- /.input group -->
            </div>
          </div>
          <div class="col-md-4" style="display:none;">
            <div class="form-group">
              <label for="duedate">Tanggal Jatuh Tempo</label>
              <div class="input-group date">
                <div class="input-group-addon">
                  <i class="fa fa-calendar"></i>
                </div>
                <input type="text" class="form-control pull-right" id="duedate" name="duedate" value="{{ isset($invoice['invoiceMas']) ? date('d-M-Y', strtotime($invoice['invoiceMas'][0]->duedate)) : date('d-M-Y') }}">
              </div>
              <!-- /.input group -->
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label for="duedate">Tanggal Berangkat</label>
              <div class="input-group date">
                <div class="input-group-addon">
                  <i class="fa fa-calendar"></i>
                </div>
                <input type="text" class="form-control pull-right" id="departdate" name="departdate" value="{{ isset($invoice['invoiceMas']) ? isset($invoice['invoiceMas'][0]->departdate) ? date('Y-m-d', strtotime($invoice['invoiceMas'][0]->departdate)) : '' : date('Y-m-d') }}">
              </div>
              <!-- /.input group -->
            </div>
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
              <label for="senderid">Nama Pengirim</label>
              <select class="form-control select2" style="width: 100%;" name="senderid">
                <option value=""></option>
                @foreach($dropDownSender as $sender)
                  <option value="{{ $sender->id }}" {{ $sender->id == (isset($invoice['invoiceMas']) ? $invoice['invoiceMas'][0]->senderid : '') ? 'selected' : '' }}>{{ $sender->name }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="col-md-4 div-recipientid">
            <div class="form-group">
              <label for="recipientid">Nama Penerima</label>
              <select class="form-control select2" style="width: 100%;" name="recipientid">
                <option value=""></option>
                @foreach($dropDownRecipient as $recipient)
                  <option value="{{ $recipient->id }}" {{ $recipient->id == (isset($invoice['invoiceMas']) ? $invoice['invoiceMas'][0]->recipientid : '') ? 'selected' : '' }}>{{ $recipient->name }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label for="shipid">Nama Kapal</label>
              <select class="form-control select2" style="width: 100%;" name="shipid">
                <option value=""></option>
                @foreach($dropDownShip as $ship)
                  <option value="{{ $ship->id }}" {{ $ship->id == (isset($invoice['invoiceMas']) ? $invoice['invoiceMas'][0]->shipid : '') ? 'selected' : '' }}>{{ $ship->name }}</option>
                @endforeach
              </select>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              <label for="senderid">Nama Bank</label>
              <select class="form-control select2" style="width: 100%;" name="bankid">
                <option value=""></option>
                @foreach($dropDownBank as $bank)
                  <option value="{{ $bank->id }}" data-ppn="{{ $bank->ppn }}" {{ $bank->id == (isset($invoice['invoiceMas']) ? $invoice['invoiceMas'][0]->bankid : '') ? 'selected' : '' }}>{{ $bank->name }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label for="note">Keterangan</label>
              <input type="text" class="form-control" name="note" value="{{ isset($invoice['invoiceMas']) ? $invoice['invoiceMas'][0]->note : ''}}" placeholder="Masukkan Keterangan" autocomplete="off">
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label for="invoicename">Nama Invoice</label>
              <input type="text" class="form-control" name="invoicename" value="{{ isset($invoice['invoiceMas']) ? $invoice['invoiceMas'][0]->invoicename : ''}}" placeholder="Masukkan Nama Invoice" autocomplete="off">
            </div>
          </div>          
        </div>
        <div class="row">
        <div class="col-md-4">
            <div class="form-group">
              <label for="invoicename">Alamat Invoice</label>
              <input type="text" class="form-control" name="invoiceaddr" value="{{ isset($invoice['invoiceMas']) ? $invoice['invoiceMas'][0]->invoiceaddr : ''}}" placeholder="Masukkan Alamat Invoice" autocomplete="off">
            </div>
          </div>          
        </div>
        <button type="button" class="btn btn-block btn-success" id="btnAdd"><i class="fa fa-fw fa-plus"></i> Tambah Detail</button>
        <h4>Detail</h4>
        <table class="table" id="table-detail">
          <thead>
            <tr>
              <th>Tanda Terima</th>
              <th>Nomor Kontainer</th>
              <th>Tipe</th>
              <th>Nama Penerima</th>
              <th>Harga</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            @if(isset($invoice['invoiceDtl']))
              @foreach($invoice['invoiceDtl'] as $dtl)
                <tr data-id="{{ $dtl->id }}" data-receiptno="{{ $dtl->receiptno }}" data-seal="{{ $dtl->seal }}">
                  <input type="hidden" class="domasid" name="domasid[]" value="{{ $dtl->domasid }}">
                  <input type="hidden" class="invoicedtlid" name="invoicedtlid[]" value="{{ $dtl->id }}">
                  <td>{{ $dtl->receiptno }}</td>
                  <td>{{ $dtl->containername }}</td>
                  <td>{{ $dtl->conname }}</td>
                  <td>{{ $dtl->recname }}</td>
                  <td><input type="text" class="form-control text-right autonumeric amount" name="amount[]" value="{{ $dtl->amount }}"></td>
                  <td>
                    <button type="button" class="btn btn-sm btn-primary btnDtl"><i class="fa fa-fw fa-edit"></i></button>
                    <button type="button" class="btn btn-sm btn-danger btnDelete"><i class="fa fa-fw fa-close"></i></button>
                  </td>
                </tr>
              @endforeach
            @endif
          </tbody>
        </table>
        <h4>Harga</h4>
        <table class="table" id="table-price">
          <thead>
            <tr>
              <th width="10%"></th>
              <th width="10%"></th>
              <th width="20%"></th>
              <th width="60%"></th>
            </tr>
          </thead>
          <tbody>
              <tr>
                <td>TOTAL</td>
                <td></td>                
                <td><input type="text" class="form-control text-right autonumeric total" name="total" value="0" readonly></td>
                <td></td>
              </tr>
              <tr>
                <td>PPN</td>
                <td><input type="text" class="form-control text-right autonumericdec ppnpercent" name="ppnpercent" value="{{ isset($invoice['invoiceMas']) ? $invoice['invoiceMas'][0]->ppnpercent : '0'}}"></td>
                <td><input type="text" class="form-control text-right autonumeric ppn" name="ppn" value="0" readonly></td>
                <td></td>
              </tr>
              <tr>
                <td>ASURANSI</td>
                <td></td>
                <td><input type="text" class="form-control text-right autonumeric insurance" name="insurance" value="{{ isset($invoice['invoiceMas']) ? $invoice['invoiceMas'][0]->insurance : 0 }}"></td>
                <td></td>
              </tr>
              <tr>
                <td>KARANTINA</td>
                <td></td>
                <td><input type="text" class="form-control text-right autonumeric quarantine" name="quarantine" value="{{ isset($invoice['invoiceMas']) ? $invoice['invoiceMas'][0]->quarantine : 0 }}"></td>
                <td></td>
              </tr>
              <tr>
                <td>GRAND TOTAL</td>
                <td></td>
                <td><input type="text" class="form-control text-right autonumeric grandtotal" name="grandtotal" value="0" readonly></td>
                <td></td>
              </tr>
          </tbody>
        </table>
      </div>
      <!-- /.box-body -->

      <div class="box-footer">
        <button type="button" class="btn btn-primary" id="btnsubmit">{{ isset($invoice['invoiceMas']) ? 'Ubah' : 'Tambah'}}</button>
        @if(isset($invoice['invoiceMas']))
          <button type="button" class="btn btn-danger" id="btnDel">Hapus</button>
        @endif
      </div>
    </form>
  </div>

  @if(isset($invoice['invoiceMas']))
    <form role="form" method="POST" id="form-del" action="{{ url('invoice/'.$invoice['invoiceMas'][0]->id) }}">
        {!! csrf_field() !!}

        {{ method_field('DELETE') }}
    </form>
  @endif

  <div class="modal fade" id="modal-default">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Nama Kontainer & Tanda Terima</h4>
        </div>
        <div class="modal-body" style="height: 400px; overflow-y: scroll;">
          <div class="overlay" style="display: none;">
            <i class="fa fa-refresh fa-spin"></i>
          </div>
          <table class="table table-condensed" id="table-modal">
            <thead>
              <tr>
                <th>#</th>
                <th>Nama Kontainer</th>
                <th>Tipe</th>
                <th>Tanda Terima</th>
                <th>Nomor Seal</th>
                <th>Nama Penerima</th>
                <th>Invoice</th>
                <th></th>
              </tr>
            </thead>
            <tbody>

            </tbody>
          </table>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Tutup</button>
          <button type="button" class="btn btn-primary" id="btnSave">Simpan</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->

  <div class="modal fade" id="modal-detail">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Nama & Jumlah Barang</h4>
        </div>
        <div class="modal-body" style="height: 400px; overflow-y: scroll;">
          <div class="overlay" style="display: none;">
            <i class="fa fa-refresh fa-spin"></i>
          </div>
          <form id="form-detail">      
          {!! csrf_field() !!}     
          <table class="table table-condensed" id="table-modal-detail">
            <thead>
              <tr>
                <th>#</th>
                <th>Nama Barang</th>
                <th>Total Barang</th>
                <th>Split</th>
                <th>Catatan</th>
              </tr>
            </thead>                             
              <tbody>
              </tbody>            
          </table>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Tutup</button>
          <button type="button" class="btn btn-primary" id="btnSaveDetail">Simpan</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->

@endsection

@section('scripts')
  <script src="{{ asset('bower_components/select2/dist/js/select2.full.min.js') }}"></script>
  <script src="{{ asset('bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
  <script src="{{ asset('bower_components/autonumeric/autoNumeric.js') }}"></script>

  <script>

    $(function() {
      init();

      $('#btnAdd').on('click', function(){
        $('#modal-default').modal('show');
        searchList();
      });

      $('#btnsubmit').on('click',function(){
        $(':input:not(:button)').prop('disabled', false);
        $(this).text('Harap Tunggu ...')
          .attr('disabled','disabled');
        $('#form-add').submit();
      });

      $('#btnSave').on('click', function(){
        $('#modal-default').modal('hide');
        saveToTableDetail();
      });

      $('.btnDtl').on('click', function(){
        $('#modal-detail').modal('show');
        
        var domasid = $(this).closest('tr').find('.domasid').val();
        console.log(domasid);
        searchDetail(domasid);
      });

      $('#btnSaveDetail').on('click', function(){
        $('#modal-detail').modal('hide');

        var form = $("#form-detail").serialize();
        saveToDODetail(form);
      });

      $('#table-detail').on('click', '.btnDelete', function(){
        var numItems = $('.btnDelete').length

        if(numItems == 1){
            alert('Tidak bisa menghapus data terakhir, tambah data baru terlebih dahulu atau ubah data yang ada')
        }else{
            if(confirm('Yakin ingin menghapus?')){
                var id = $(this).closest('tr').find('.domasid').val();
                $(this).closest('tr').remove();

                calculate();
                setLock();
            }
        }
      })

      $('#table-detail').on('change', '.amount',function(){
        calculate();
        setLock();
      });

      $('input[name=ppnpercent]').on('change', function(){
        calculate();
        setLock();
      });

      $('input[name=insurance]').on('change', function(){
        calculate();
        setLock();
      });

      $('input[name=quarantine]').on('change', function(){
        calculate();
        setLock();
      });

      $('select[name=bankid]').on('change', function(){
        console.log('aaa');
        setLockPPN();
        calculate();
        
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

      $(".autonumeric").on("click", function () {
         $(this).select();
      });

      $('#btnDel').on('click',function(){
        if(confirm('Yakin ingin menghapus?')){
          $(this).text('Harap Tunggu ...')
            .attr('disabled','disabled');
          $('#form-del').submit();
        }
      });

      $(document).on("keypress", ":input:not(textarea)", function(event) {
          if (event.keyCode == 13) {
              event.preventDefault();
          }
      });

      $("#table-detail").on("focus", ".amount", function() { $(this).select(); } );

      $('select[name=invoicetypeid]').trigger('change');


    });

    function init(){
        $('.select2').select2();

        $('#invoicedate').datepicker({
          format: "dd-M-yyyy",
          autoclose: true
        });

        $('#departdate').datepicker({
          format: "yyyy-mm-dd",
          autoclose: true
        });

        $('.autonumeric').autoNumeric({mDec:0});
        $('.autonumericdec').autoNumeric({mDec:2});        

        calculate();
        setLock();

        getInvoiceNo();

        $('input[name=invoiceno]').focus();
      }

    function searchList(){
      var req = $.ajax({
          type: "POST",
          url: "{{ url('deliveryorder/search-list-inv') }}",
          data: {
            _token: $('input[name=_token]').val(),
            invoicetypeid: $('select[name=invoicetypeid]').val(),
            senderid: $('select[name=senderid]').val(),
            recipientid: $('select[name=recipientid]').val(),
            shipid: $('select[name=shipid]').val(),
            departdate: $('input[name=departdate]').val(),
          },
          dataType: "JSON",
          beforeSend: function( xhr ) {
              $('.overlay').show();
          }
      });

      req.done(function(data){
        $('.overlay').hide();
        // $('.result').find('.box-body').html(data);
        // console.log(data);
        setTableModal(data);
      });

      req.fail(function(jqXHR, textStatus){
          $('.overlay').hide();
          alert( "Error" );
      });
    }

    function setTableModal(data){
      arrTempId = [];

      $('.domasid').each(function(){
        arrTempId.push(parseInt($(this).val()));
      });
      // console.log(arrTempId);

      var html = '';
      for(var i = 0; i < data.length; i++){
        // console.log(data[i].id);
        if(jQuery.inArray( data[i].id, arrTempId ) === -1){
          html += '<tr data-id="' + data[i].id + '" data-receiptno="' + data[i].receiptno + '" data-containername="' + data[i].containername + '" data-conname="' + data[i].conname + '" data-seal="' + data[i].seal + '" data-recname="' + data[i].recname + '">';
          html += '<td><input type="checkbox" class="modal-checkbox"></td>';
          html += '<td>' + data[i].containername + '</td>';
          html += '<td>' + data[i].conname + '</td>';
          html += '<td>' + data[i].receiptno + '</td>';
          html += '<td>' + data[i].seal + '</td>';
          html += '<td>' + data[i].recname + '</td>';
          html += '<td>' + data[i].invoiceno + '</td>';
          html += '</tr>';
        }
      }

      $('#table-modal').find('tbody').html(html);
    }

    function saveToTableDetail(){
      $('input:checkbox.modal-checkbox').each(function () {
        if($(this).prop('checked')){
          var id = $(this).closest('tr').data('id');
          var receiptno = $(this).closest('tr').data('receiptno');
          var containername = $(this).closest('tr').data('containername');
          var conname = $(this).closest('tr').data('conname');
          var seal = $(this).closest('tr').data('seal');
          var recname = $(this).closest('tr').data('recname');

          var html = '';
          html += '<tr data-id="' + id + '" data-receiptno="' + receiptno + '" data-seal="' + seal + '">';
          html += '<input type="hidden" class="domasid" name="domasid[]" value="' + id + '">';
          html += '<input type="hidden" class="invoicedtlid" name="invoicedtlid[]" value="0">';
          html += '<td>' + receiptno + '</td>';
          html += '<td>' + containername + '</td>';
          html += '<td>' + conname + '</td>';
          html += '<td>' + recname + '</td>';
          html += '<td>' + '<input type="text" class="form-control text-right autonumeric amount" name="amount[]" value="0">' + '</td>';
          html += '<td><button type="button" class="btn btn-sm btn-primary btnDtl"><i class="fa fa-fw fa-edit"></i></button><button type="button" class="btn btn-sm btn-danger btnDelete"><i class="fa fa-fw fa-close"></i></button></td>';
          html += '</tr>';

          $('#table-detail').find('tbody').append(html);
          $('.autonumeric').autoNumeric({mDec:0});
        }
      });
    }

    function calculate(){
      var grandtotal = 0;
      var total = 0;
      $('.amount').each(function(){
          var amount = $(this).closest('tr').find('.amount').autoNumeric('get');
          total += parseFloat(amount);
      });

      var ppnpercent = $('input[name=ppnpercent]').autoNumeric('get');
      var ppn = parseFloat(ppnpercent) * total / 100;
      var insurance = parseFloat($('input[name=insurance]').autoNumeric('get'));
      var quarantine = parseFloat($('input[name=quarantine]').autoNumeric('get'));
      grandtotal = total + ppn + insurance + quarantine;

      $('input[name=total]').autoNumeric('set', total);
      $('input[name=ppn]').autoNumeric('set', ppn);
      $('input[name=grandtotal]').autoNumeric('set', grandtotal);
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

    function setLockPPN(){
      if($('select[name=bankid]').find(':selected').data('ppn') == '0'){
        $('input[name=ppnpercent').val(0);
        $('input[name=ppn').val(0);
        $('input[name=ppnpercent').attr('readonly', true);
      }else{        
        $('input[name=ppnpercent').attr('readonly', false);
      }      
    }

    function getInvoiceNo(){
      if($('input[name=id]').val() == '0'){
        var req = $.ajax({
            type: "POST",
            url: "{{ url('invoice/getinvoiceno') }}",
            data: {
              _token: $('input[name=_token]').val(),
              invoicetypeid: $('select[name=invoicetypeid]').val(),
              senderid: $('select[name=senderid]').val(),
              recipientid: $('select[name=recipientid]').val(),
            },
            dataType: "JSON",
            beforeSend: function( xhr ) {
                $('.overlay').show();
            }
        });

        req.done(function(data){
          $('.overlay').hide();

          console.log(data);

          $('input[name=invoiceno]').val(data);
        });

        req.fail(function(jqXHR, textStatus){
            $('.overlay').hide();
            alert( "Error" );
        });
      }

    }

    function searchDetail(domasid){
      var req = $.ajax({
          type: "POST",
          url: "{{ url('deliveryorder/search-list-dtl') }}",
          data: {
            _token: $('input[name=_token]').val(),
            domasid: domasid,            
          },
          dataType: "JSON",
          beforeSend: function( xhr ) {
              $('.overlay').show();
          }
      });

      req.done(function(data){
        $('.overlay').hide();
        // $('.result').find('.box-body').html(data);
        console.log(data);
        setTableModalDtl(data);
      });

      req.fail(function(jqXHR, textStatus){
          $('.overlay').hide();
          alert( "Error" );
      });
    }

    function setTableModalDtl(data){
      var invtype = $('select[name=invoicetypeid]').val();

      var html = '';
      
      for(var i = 0; i < data.length; i++){
        // console.log(data[i].id);
        var itemOrderSender = '';
        var itemOrderRecipient = '';
        var note = '';

        if(data[i].note !== null){
          note = data[i].note;
        }
        
        html += '<tr data-id="' + data[i].id + '">';
        html += '<td><input type="hidden" class="subdtlid" name="subdtl-id[]" value="' + data[i].id + '"><input type="hidden" class="invoicetypeid" name="subdtl-invoicetypeid[]" value="' + invtype + '"><input type="hidden" class="subdtl-domasid" name="subdtl-domasid[]" value="' + data[i].domasid + '"></td>';
        html += '<td>' + data[i].itemname + '</td>';
        html += '<td>' + data[i].itemorder + '</td>';
        if(invtype == '0'){
          if(data[i].itemordersender !== null){
            itemOrderSender = data[i].itemordersender
          }
          html += '<td><input type="text" class="itemordersender" name="subdtl-itemordersender[]" value="' + itemOrderSender + '"></td>';
        }else{
          if(data[i].itemorderrecipient !== null){
            itemOrderRecipient = data[i].itemorderrecipient
          }
          html += '<td><input type="text" class="itemorderrecipient" name="subdtl-itemorderrecipient[]" value="' + itemOrderRecipient + '"></td>';
        }        
        html += '<td><input type="text" class="note" name="subdtl-note[]" value="' + note + '"></td>';
        html += '</tr>';
        
      }

      $('#table-modal-detail').find('tbody').html(html);
    }

    function saveToDODetail(form){
      var req = $.ajax({
          type: "POST",
          url: "{{ url('deliveryorder/update-split') }}",
          // data: {
          //   _token: $('input[name=_token]').val(),
          //   domasid: domasid,            
          // },
          data: form,
          dataType: "JSON",
          beforeSend: function( xhr ) {
              $('.overlay').show();
          }
      });

      req.done(function(data){
        $('.overlay').hide();
        // $('.result').find('.box-body').html(data);
        console.log(data);        
      });

      req.fail(function(jqXHR, textStatus){
          $('.overlay').hide();
          alert( "Error" );
      });
    }

  </script>
@endsection
