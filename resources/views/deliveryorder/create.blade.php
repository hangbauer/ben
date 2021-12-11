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
    	@if(isset($do['doMas']))
        <h3 class="box-title">Ubah</h3>
      @else
        <h3 class="box-title">Tambah Baru</h3>
      @endif
    </div>
    <!-- /.box-header -->
    <!-- form start -->
    <form role="form" method="POST" id="form-add" action="{{ isset($do['doMas']) ? url('deliveryorder/'.$do['doMas'][0]->id) : url('deliveryorder') }}">
      {!! csrf_field() !!}

      @if(isset($do['doMas']))
        {{ method_field('PUT') }}
      @endif

      <div class="box-body">
        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              <label for="receiptno">Nomor Tanda Terima</label>
              <input type="text" class="form-control" name="receiptno" value="{{ isset($do['doMas']) ? $do['doMas'][0]->receiptno : ''}}" placeholder="Masukkan Tanda Terima" autocomplete="off">
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label for="code">Tanggal</label>
              <div class="input-group date">
                <div class="input-group-addon">
                  <i class="fa fa-calendar"></i>
                </div>
                <input type="text" class="form-control pull-right" id="datepicker" name="dodate" value="{{ isset($do['doMas']) ? date('d-M-Y', strtotime($do['doMas'][0]->dodate)) : date('d-M-Y') }}">
              </div>
              <!-- /.input group -->
            </div>
            <!-- /.form group -->
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label for="paymenttypeid">Pembayaran</label>
              <select class="form-control select2" style="width: 100%;" name="paymenttypeid">
                <option value=""></option>
                @foreach($dropDownPaymentType as $paymentType)
                  <option value="{{ $paymentType->id }}" {{ $paymentType->id == (isset($do['doMas']) ? $do['doMas'][0]->paymenttypeid : '') ? 'selected' : '' }}>{{ $paymentType->name }}</option>
                @endforeach
              </select>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-8">
            <div class="form-group">
              <label for="senderid">Nama Pengirim</label>
              <select class="form-control select2" style="width: 100%;" name="senderid">
                <option value=""></option>
                @foreach($dropDownSender as $sender)
                  <option value="{{ $sender->id }}" {{ $sender->id == (isset($do['doMas']) ? $do['doMas'][0]->senderid : '') ? 'selected' : '' }}>{{ $sender->name . ' - ' . $sender->address }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label for="termid">Term</label>
              <select class="form-control select2" style="width: 100%;" name="termid">
                <option value=""></option>
                @foreach($dropDownTerm as $term)
                  <option value="{{ $term->id }}" {{ $term->id == (isset($do['doMas']) ? $do['doMas'][0]->termid : '') ? 'selected' : '' }}>{{ $term->name }}</option>
                @endforeach
              </select>
            </div>
          </div>
        </div>
        <div class="row">
            <div class="col-md-8">
                <div class="form-group">
                  <label for="recipientid">Nama Penerima</label>
                  <select class="form-control select2" style="width: 100%;" name="recipientid">
                    <option value=""></option>
                    @foreach($dropDownRecipient as $recipient)
                      <option value="{{ $recipient->id }}" {{ $recipient->id == (isset($do['doMas']) ? $do['doMas'][0]->recipientid : '') ? 'selected' : '' }}>{{ $recipient->name . ' - ' . $recipient->address }}</option>
                    @endforeach
                  </select>
                </div>
            </div>
        </div>
        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              <label for="containername">Nomor Kontainer</label>
              <div class="input-group">
                    <span class="input-group-addon">
                      <input type="checkbox" name="fullcontainerflag" {{ isset($do['doMas']) ? ($do['doMas'][0]->fullcontainerflag ? 'checked' : '') : 'checked'  }}>
                    </span>
                <input type="text" class="form-control" name="containername" value="{{ isset($do['doMas']) ? $do['doMas'][0]->containername : ''}}" placeholder="Masukkan Nomor Kontainer" autocomplete="off">
              </div>
              <!-- /input-group -->
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label for="containertypeid">Tipe Kontainer</label>
              <select class="form-control select2" style="width: 100%;" name="containertypeid">
                <option value=""></option>
                @foreach($dropDownContainerType as $containerType)
                  <option value="{{ $containerType->id }}" {{ $containerType->id == (isset($do['doMas']) ? $do['doMas'][0]->containertypeid : '') ? 'selected' : '' }}>{{ $containerType->name }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label for="seal">Seal</label>
              <input type="text" class="form-control" name="seal" value="{{ isset($do['doMas']) ? $do['doMas'][0]->seal : ''}}" placeholder="Masukkan Seal" autocomplete="off">
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-8">
            <div class="form-group">
              <label for="note">Keterangan</label>
              <input type="text" class="form-control" name="note" value="{{ isset($do['doMas']) ? $do['doMas'][0]->note : ''}}" placeholder="Masukkan Keterangan" autocomplete="off">
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label for="containertypeid">Kendaraan</label>
              <select class="form-control select2" style="width: 100%;" name="carflag">
                <option value="0" {{ isset($do['doMas']) ? ($do['doMas'][0]->carflag ? '' : 'selected') : 'selected' }}>Tidak</option>
                <option value="1" {{ isset($do['doMas']) ? ($do['doMas'][0]->carflag ? 'selected' : '') : '' }}>Ya</option>
              </select>
            </div>
          </div>
        </div>
        <h4>Detail</h4>
        <table class="table" id="table-detail">
          <thead>
            <tr>
              <th width="50%">Nama Barang</th>
              <th class="text-right">Jumlah</th>
              <th>Satuan</th>
              <th class="text-right">Volume / Berat</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            @if(isset($do['doDtl']))
              @foreach($do['doDtl'] as $dtl)
                <tr>
                  <input type="hidden" class="dtlid" name="dtlid[]" value="{{ $dtl->id }}">
                  <td>
                    <input type="text" class="form-control itemname" name="itemname[]" value="{{ $dtl->itemname }}" placeholder="Masukkan Nama Barang" autocomplete="off">
                  </td>
                  <td>
                    <input type="text" class="form-control text-right autonumeric itemorder" name="itemorder[]" value="{{ $dtl->itemorder }}" required>
                  </td>
                  <td>
                    <input type="text" class="form-control itemunit" name="itemunit[]" value="{{ $dtl->itemunit }}" placeholder="Masukkan Satuan" autocomplete="off">
                  </td>
                  <td>
                    <input type="text" class="form-control volume" name="volume[]" value="{{ $dtl->volume }}" placeholder="Masukkan Volume" autocomplete="off">
                  </td>
                  <td>
                    <button type="button" class="btn btn-block btn-danger btnDelete"><i class="fa fa-fw fa-close"></i></button>
                  </td>
                </tr>
              @endforeach
            @else
              <tr>
                <input type="hidden" class="dtlid" name="dtlid[]" value="0">
                <td>
                  <input type="text" class="form-control itemname" name="itemname[]" value="" placeholder="Masukkan Nama Barang" autocomplete="off">
                </td>
                <td>
                  <input type="text" class="form-control text-right autonumeric itemorder" name="itemorder[]" value="1" required>
                </td>
                <td>
                  <input type="text" class="form-control itemunit" name="itemunit[]" value="" placeholder="Masukkan Satuan" autocomplete="off">
                </td>
                <td>
                  <input type="text" class="form-control volume" name="volume[]" value="" placeholder="Masukkan Volume" autocomplete="off">
                </td>
                <td>
                  <button type="button" class="btn btn-block btn-danger btnDelete"><i class="fa fa-fw fa-close"></i></button>
                </td>
              </tr>
            @endif
          </tbody>
          <tfoot>
            <tr>
              <td>
                <button type="button" class="btn btn-block btn-success" id="btnAdd"><i class="fa fa-fw fa-plus"></i> Tambah Detail</button>
              </td>
              <td>

              </td>
              <td>

              </td>
              <td>

              </td>
              <td>

              </td>
            </tr>
          </tfoot>
        </table>
      </div>
      <!-- /.box-body -->

      <div class="box-footer">
        
        @if(isset($do['doMas']))
          @if(!isset($do['doMas'][0]->shipscheduleid))
            <button type="submit" class="btn btn-primary">Ubah</button>
            <button type="button" class="btn btn-danger" id="btnDel">Hapus</button>
          @endif                    
        @else
          <button type="submit" class="btn btn-primary">Tambah</button>
        @endif
      </div>
    </form>
  </div>

  @if(isset($do['doMas']))
    <form role="form" method="POST" id="form-del" action="{{ url('deliveryorder/'.$do['doMas'][0]->id) }}">
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

      $('#btnAdd').on('click',function(){

        // $('#table-detail').find('tbody').find('tr:first').find('.select2').data('select2').destroy();

        var temp = $('#table-detail').find('tbody').find('tr:first').clone();

        $('#table-detail').find('tbody').append(temp);

        var temp2 = $('#table-detail').find('tbody').find('tr:last');
        temp2.find('.dtlid').val('0');
        temp2.find('.itemname').val('');
        temp2.find('.itemorder').val('0');
        temp2.find('.itemunit').val('');
        temp2.find('.volume').val('');


        // $('.select2').select2();
        $('.autonumeric').autoNumeric({mDec:0});

      });

      $('#table-detail').on('click', '.btnDelete',function(){
        var numItems = $('.btnDelete').length

        if(numItems == 1){
            alert('Tidak bisa menghapus data terakhir, tambah data baru terlebih dahulu atau ubah data yang ada')
        }else{
            if(confirm('Yakin ingin menghapus?')){
                $(this).closest('tr').remove();
            }
        }
      });

      $('#btnsubmit').on('click',function(){
        $(this).text('Harap Tunggu ...')
          .attr('disabled','disabled');
        $('#form-add').submit();
      });

      $("#table-detail").find("tbody").on("click", ".autonumeric", function () {
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

      function init(){
        $('.select2').select2();

        $('#datepicker').datepicker({
          format: "dd-M-yyyy",
          autoclose: true
        });

        $('.autonumeric').autoNumeric({mDec:0});

        $('input[name=receiptno]').focus();
      }
    });
  </script>
@endsection
