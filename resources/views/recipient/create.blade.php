@extends('layout')

@section('plugins')
  <link rel="stylesheet" href="{{ asset('bower_components/select2/dist/css/select2.min.css') }}">
  <link rel="stylesheet" href="{{ asset('bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
@endsection

@section('content')
	<div class="box box-primary">
        <div class="box-header with-border">
        	@if(isset($recipient))
            <h3 class="box-title">Ubah</h3>
          @else
            <h3 class="box-title">Tambah Baru</h3>
          @endif
        </div>
        <!-- /.box-header -->
        <!-- form start -->
        <form role="form" method="POST" id="form-add" action="{{ isset($recipient) ? url('recipient/'.$recipient[0]->id) : url('recipient') }}">
          {!! csrf_field() !!}

          @if(isset($recipient))
            {{ method_field('PUT') }}
          @endif

          <div class="box-body">
                <div class="form-group">
                        <label for="title">Title</label>
                        <select class="form-control" name="title">
                        @foreach($dropDownTitle as $title)
                        <option value="{{ $title->name }}"  {{ $title->name == (isset($recipient) ? $recipient[0]->title : '') ? 'selected' : '' }}>{{ $title->name }}</option>
                        @endforeach
                        </select>
                    </div>
            <div class="form-group">
              <label for="name">Nama Penerima</label>
              <input type="text" class="form-control" name="name" value="{{ isset($recipient) ? $recipient[0]->name : ''}}" placeholder="Masukkan Nama Penerima" autocomplete="off">
            </div>
            <div class="form-group">
              <label for="address">Alamat</label>
              <input type="text" class="form-control" name="address" value="{{ isset($recipient) ? $recipient[0]->address : ''}}" placeholder="Masukkan Alamat" autocomplete="off">
            </div>
            <div class="form-group">
              <label for="city">Kota</label>
              <input type="text" class="form-control" name="city" value="{{ isset($recipient) ? $recipient[0]->city : ''}}" placeholder="Masukkan Kota" autocomplete="off">
            </div>
            <div class="form-group">
              <label for="phone">Telepon</label>
              <input type="text" class="form-control" name="phone" value="{{ isset($recipient) ? $recipient[0]->phone : ''}}" placeholder="Masukkan No Telepon" autocomplete="off">
            </div>
            <div class="form-group">
              <label for="email">Email</label>
              <input type="email" class="form-control" name="email" value="{{ isset($recipient) ? $recipient[0]->email : ''}}" placeholder="Masukkan Email" autocomplete="off">
            </div>
            <div class="form-group">
              <label for="note">Keterangan</label>
              <input type="text" class="form-control" name="note" value="{{ isset($recipient) ? $recipient[0]->note : ''}}" placeholder="Masukkan Keterangan" autocomplete="off">
            </div>
            <div class="form-group">
              <label for="status">Status</label>
              <select class="form-control" name="status">
                <option value="0" {{ 0 == (isset($recipient) ? $recipient[0]->status : 0) ? 'selected' : '' }}>Aktif</option>
                <option value="1" {{ 1 == (isset($recipient) ? $recipient[0]->status : 0) ? 'selected' : '' }}>Tidak Aktif</option>
              </select>
            </div>
          </div>
          <!-- /.box-body -->

          <div class="box-footer">
            <button type="submit" class="btn btn-primary">{{ isset($recipient) ? 'Ubah' : 'Tambah'}}</button>
            @if(isset($recipient))
            <button type="button" class="btn btn-danger" id="btnDel">Hapus</button>
            @endif
          </div>
        </form>
      </div>

      @if(isset($recipient))
      <form role="form" method="POST" id="form-del" action="{{ url('recipient/'.$recipient[0]->id) }}">
          {!! csrf_field() !!}

          @if(isset($recipient))
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
