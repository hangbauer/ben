@extends('layout')

@section('plugins')
  <link rel="stylesheet" href="{{ asset('bower_components/select2/dist/css/select2.min.css') }}">
  <link rel="stylesheet" href="{{ asset('bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
@endsection

@section('content')
	<div class="box box-primary">
        <div class="box-header with-border">
        	@if(isset($bank))
            <h3 class="box-title">Ubah</h3>
          @else
            <h3 class="box-title">Tambah Baru</h3>
          @endif
        </div>
        <!-- /.box-header -->
        <!-- form start -->
        <form role="form" method="POST" id="form-add" action="{{ isset($bank) ? url('bank/'.$bank[0]->id) : url('bank') }}">
          {!! csrf_field() !!}

          @if(isset($bank))
            {{ method_field('PUT') }}
          @endif

          <div class="box-body">
            <div class="form-group">
              <label for="name">Nama Bank</label>
              <input type="text" class="form-control" name="name" value="{{ isset($bank) ? $bank[0]->name : ''}}" placeholder="Masukkan Nama Bank" autocomplete="off">
            </div>
            <div class="form-group">
              <label for="accountname">Atas Nama</label>
              <input type="text" class="form-control" name="accountname" value="{{ isset($bank) ? $bank[0]->accountname : ''}}" placeholder="Masukkan Atas Nama" autocomplete="off">
            </div>
            <div class="form-group">
              <label for="accountno">Nomor Rekening</label>
              <input type="text" class="form-control" name="accountno" value="{{ isset($bank) ? $bank[0]->accountno : ''}}" placeholder="Masukkan Nomor Rekening" autocomplete="off">
            </div>
            <div class="form-group">
              <label for="note">Keterangan</label>
              <input type="text" class="form-control" name="note" value="{{ isset($bank) ? $bank[0]->note : ''}}" placeholder="Masukkan Keterangan" autocomplete="off">
            </div>
            <div class="form-group">
              <label for="status">Status</label>
              <select class="form-control" name="status">
                <option value="0" {{ 0 == (isset($bank) ? $bank[0]->status : 0) ? 'selected' : '' }}>Aktif</option>
                <option value="1" {{ 1 == (isset($bank) ? $bank[0]->status : 0) ? 'selected' : '' }}>Tidak Aktif</option>
              </select>
            </div>
          </div>
          <!-- /.box-body -->

          <div class="box-footer">
            <button type="submit" class="btn btn-primary">{{ isset($bank) ? 'Ubah' : 'Tambah'}}</button>
            @if(isset($bank))
            <button type="button" class="btn btn-danger" id="btnDel">Hapus</button>
            @endif
          </div>
        </form>
      </div>

      @if(isset($bank))
      <form role="form" method="POST" id="form-del" action="{{ url('bank/'.$bank[0]->id) }}">
          {!! csrf_field() !!}

          @if(isset($bank))
            {{ method_field('DELETE') }}
          @endif
      </form>
      @endif
@endsection

@section('scripts')
  <script src="{{ asset('bower_components/select2/dist/js/select2.full.min.js') }}"></script>
  <script src="{{ asset('bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
  <script src="{{ asset('bower_components/autonumeric/autoNumeric.js') }}"></script>

  <script>
    $(function() {
      $('.autonumeric').autoNumeric({mDec:0});


      $('#btnsubmit').on('click',function(){
        $(this).text('Harap Tunggu ...')
          .attr('disabled','disabled');
        $('#form-add').submit();
      });

      $('#btnDel').on('click',function(){
        if(confirm('Yakin ingin menghapus?')){
          $(this).text('Harap Tunggu ...')
            .attr('disabled','disabled');
          $('#form-del').submit();
        }
      });
    });
  </script>
@endsection
