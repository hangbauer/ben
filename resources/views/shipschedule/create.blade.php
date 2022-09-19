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
    	@if(isset($shipSchedule))
        <h3 class="box-title">Ubah</h3>
      @else
        <h3 class="box-title">Tambah Baru</h3>
      @endif
    </div>
    <!-- /.box-header -->
    <!-- form start -->
    <form role="form" method="POST" id="form-add" action="{{ isset($shipSchedule) ? url('shipschedule/'.$shipSchedule[0]->id) : url('shipschedule') }}">
      {!! csrf_field() !!}

      @if(isset($shipSchedule))
        {{ method_field('PUT') }}
      @endif

      <div class="box-body">
        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              <label for="shipid">Nama Kapal</label>
              <select class="form-control select2" style="width: 100%;" name="shipid">
                <option value=""></option>
                @foreach($dropDownShip as $ship)
                  <option value="{{ $ship->id }}" {{ $ship->id == (isset($shipSchedule) ? $shipSchedule[0]->shipid : '') ? 'selected' : '' }}>{{ $ship->name }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label for="departdate">Tanggal Berangkat</label>
              <div class="input-group date">
                <div class="input-group-addon">
                  <i class="fa fa-calendar"></i>
                </div>
                <input type="text" class="form-control pull-right" id="datepicker" name="departdate" value="{{ isset($shipSchedule) ? date('d-M-Y', strtotime($shipSchedule[0]->departdate)) : date('d-M-Y') }}">
              </div>
              <!-- /.input group -->
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label for="destination">Tujuan</label>
              <input type="text" class="form-control" name="destination" value="{{ isset($shipSchedule) ? $shipSchedule[0]->destination : ''}}" placeholder="Masukkan Tujuan" autocomplete="off">
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              <label for="voyage">Voyage</label>
              <input type="text" class="form-control" name="voyage" value="{{ isset($shipSchedule) ? $shipSchedule[0]->voyage : ''}}" placeholder="Masukkan Voyage" autocomplete="off">
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
                <label for="depart">Kota Keberangkatan</label>
                <input type="text" class="form-control" name="depart" value="{{ isset($shipSchedule) ? $shipSchedule[0]->depart : ''}}" placeholder="Masukkan Kota Keberangkatan" autocomplete="off">
            </div>
            </div>
          <div class="col-md-4">
            <div class="form-group">
              <label for="note">Keterangan</label>
              <input type="text" class="form-control" name="note" value="{{ isset($shipSchedule) ? $shipSchedule[0]->note : ''}}" placeholder="Masukkan Keterangan" autocomplete="off">
            </div>
          </div>
        </div>
        @if(isset($invoiceDtl))
          @if(count($invoiceDtl) == 0)
            <button type="button" class="btn btn-block btn-success" id="btnAdd"><i class="fa fa-fw fa-plus"></i> Tambah Detail</button>
          @endif            
        @endif          
        <h4>Detail</h4>
        <table class="table" id="table-detail">
          <thead>
            <tr>
              <th>Nama Kontainer</th>
              <th>Nomor Seal</th>
              <th>Tanda Terima</th>
              <th>Tanggal</th>
              <th>Nama Pengirim</th>
              <th>Nama Penerima</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            @if(isset($doMas))
              @foreach($doMas as $dtl)
                <tr data-id="{{ $dtl->id }}" data-receiptno="{{ $dtl->receiptno }}" data-seal="{{ $dtl->seal }}" data-sendername="{{ $dtl->sendername }}" data-recname="{{ $dtl->recname }}">
                  <input type="hidden" class="domasid" name="domasid[]" value="{{ $dtl->id }}">
                  <td>{{ $dtl->containername }}</td>
                  <td>{{ $dtl->seal }}</td>
                  <td>{{ $dtl->receiptno }}</td>
                  <td>{{ $dtl->dodate }}</td>
                  <td>{{ $dtl->sendername }}</td>
                  <td>{{ $dtl->recname }}</td>
                  <td>
                    @if(isset($invoiceDtl))
                      @if(count($invoiceDtl) == 0)
                        <button type="button" class="btn btn-block btn-danger btnDelete"><i class="fa fa-fw fa-close"></i></button>
                      @endif            
                    @endif          
                  </td>
                </tr>
              @endforeach
            @endif
          </tbody>
        </table>
      </div>
      <!-- /.box-body -->

      <div class="box-footer">        
        @if(isset($shipSchedule))
          <button type="submit" class="btn btn-primary">Ubah</button>
          @if(isset($invoiceDtl))
            @if(count($invoiceDtl) == 0)              
              <button type="button" class="btn btn-danger" id="btnDel">Hapus</button>
            @endif            
          @endif          
        @else
          <button type="submit" class="btn btn-primary">Tambah</button>
        @endif
      </div>
    </form>
  </div>

  <div class="modal fade" id="modal-default">
    <div class="modal-dialog">
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
                <th>Nomor Seal</th>
                <th>Tanda Terima</th>
                <th>Tanggal</th>
                <th>Nama Pengirim</th>
                <th>Nama Penerima</th>
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

  @if(isset($shipSchedule))
    <form role="form" method="POST" id="form-del" action="{{ url('shipschedule/'.$shipSchedule[0]->id) }}">
        {!! csrf_field() !!}

        {{ method_field('DELETE') }}
    </form>
  @endif

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
        $(this).text('Harap Tunggu ...')
          .attr('disabled','disabled');
        $('#form-add').submit();
      });

      $('#btnSave').on('click', function(){
        $('#modal-default').modal('hide');
        saveToTableDetail();
      });

      $('#table-detail').on('click', '.btnDelete', function(){
        // var numItems = $('.btnDelete').length

        // if(numItems == 1){
        //     alert('Tidak bisa menghapus data terakhir, tambah data baru terlebih dahulu atau ubah data yang ada')
        // }else{
        //     if(confirm('Yakin ingin menghapus?')){
        //         var id = $(this).closest('tr').find('.domasid').val();
        //         $(this).closest('tr').remove();
        //     }
        // }

        if(confirm('Yakin ingin menghapus?')){
          var id = $(this).closest('tr').find('.domasid').val();
          $(this).closest('tr').remove();
        }
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

      function init(){
        $('.select2').select2();

        $('#datepicker').datepicker({
          format: "dd-M-yyyy",
          autoclose: true
        });

        $('.autonumeric').autoNumeric({mDec:0});

        $('select[name=shipid]').focus();
      }
    });

    function searchList(){
      var req = $.ajax({
          type: "POST",
          url: "{{ url('deliveryorder/search-list') }}",
          data: {
            _token: $('input[name=_token]').val(),
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
        setTableModal(data);

        $('#btnsubmit').text('Cari')
          .removeAttr('disabled');

        $('.box-search').boxWidget('collapse');
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

      var html = '';
      for(var i = 0; i < data.length; i++){
        if(jQuery.inArray( data[i].id, arrTempId ) === -1){
          html += '<tr data-id="' + data[i].id + '" data-receiptno="' + data[i].receiptno + '" data-dodate="' + data[i].dodate + '" data-containername="' + data[i].containername + '" data-seal="' + data[i].seal + '" data-sendername="' + data[i].sendername + '" data-recname="' + data[i].recname + '">';
          html += '<td><input type="checkbox" class="modal-checkbox"></td>';
          html += '<td>' + data[i].containername + '</td>';
          html += '<td>' + data[i].seal + '</td>';
          html += '<td>' + data[i].receiptno + '</td>';
          html += '<td>' + data[i].dodate + '</td>';
          html += '<td>' + data[i].sendername + '</td>';
          html += '<td>' + data[i].recname + '</td>';
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
          var dodate = $(this).closest('tr').data('dodate');
          var containername = $(this).closest('tr').data('containername');
          var seal = $(this).closest('tr').data('seal');
          var sendername = $(this).closest('tr').data('sendername');
          var recname = $(this).closest('tr').data('recname');

          var html = '';
          html += '<tr data-id="' + id + '" data-receiptno="' + receiptno + '" data-seal="' + seal + '">';
          html += '<input type="hidden" class="domasid" name="domasid[]" value="' + id + '">';
          html += '<td>' + containername + '</td>';
          html += '<td>' + seal + '</td>';
          html += '<td>' + receiptno + '</td>';
          html += '<td>' + dodate + '</td>';
          html += '<td>' + sendername + '</td>';
          html += '<td>' + recname + '</td>';
          html += '<td><button type="button" class="btn btn-block btn-danger btnDelete"><i class="fa fa-fw fa-close"></i></button></td>';
          html += '</tr>';

          $('#table-detail').find('tbody').append(html);
        }
      });
    }

  </script>
@endsection
