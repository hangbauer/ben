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
	<a href="{{ url('deliveryorder/create') }}" class="btn btn-primary"><i class="fa fa-fw fa-plus"></i> Tambah</a>

	<br>
	<br>
	<div class="row">
        <div class="col-md-12">
			<div class="box box-success box-search">
				{{-- collapsed-box --}}
			    <div class="box-header with-border">
					<h3 class="box-title">Cari Tanda Terima</h3>
					<div class="box-tools pull-right">
						<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
						</button>
					</div>
			    </div>
			    <!-- /.box-header -->
			    <!-- form start -->
			    <form role="form" method="POST" id="form-add">
					{!! csrf_field() !!}

					<div class="box-body">
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label for="receiptno">Nomor Tanda Terima</label>
									<input type="text" class="form-control" name="receiptno" value="" >
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="dodate">Tanggal Tanda Terima</label>
									<div class="input-group date">
										<div class="input-group-addon">
											<i class="fa fa-calendar"></i>
										</div>
										<input type="text" class="form-control pull-right" id="datepicker" name="dodate">
									</div>
								<!-- /.input group -->
								</div>
								<!-- /.form group -->
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="containername">Nomor Kontainer</label>
									<input type="text" class="form-control" name="containername" value="" >
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label>Nama Pengirim</label>
									<select class="form-control select2" style="width: 100%;" name="senderid">
									<option value=""></option>
									@foreach($dropDownSender as $sender)
										<option value="{{ $sender->id }}">{{ $sender->name }}</option>
									@endforeach
									</select>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label>Nama Penerima</label>
									<select class="form-control select2" style="width: 100%;" name="recipientid">
									<option value=""></option>
									@foreach($dropDownRecipient as $recipient)
										<option value="{{ $recipient->id }}">{{ $recipient->name }}</option>
									@endforeach
									</select>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="code">Keterangan</label>
									<input type="text" class="form-control" name="note">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label>Tipe</label>
									<select class="form-control select2" style="width: 100%;" name="tipe">
									<option value=""></option>
									<option value="1">Belum Masuk Kapal</option>									
									</select>
								</div>
							</div>
						</div>
					</div>
					<!-- /.box-body -->

					<div class="box-footer">
						<button type="button" class="btn btn-primary" id="btnsubmit">Cari</button>
					</div>
			    </form>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-md-12">
			<div class="box result">

				<div class="box-body">

				</div>
				<!-- /.box-body -->
				<!-- Loading (remove the following to stop the loading)-->
	            <div class="overlay" style="display: none;">
	            	<i class="fa fa-refresh fa-spin"></i>
	            </div>
	            <!-- end loading -->
			</div>
			<!-- /.box -->
		</div>
	</div>
@endsection

@section('scripts')
	<!-- DataTables -->
	<script src="{{ asset('bower_components/select2/dist/js/select2.full.min.js') }}"></script>
	<script src="{{ asset('bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
	<script src="{{ asset('bower_components/autonumeric/autoNumeric.js') }}"></script>

	<script>
	    $(function() {
			init();

			$('#btnsubmit').on('click',function(){
				$(this).text('Harap Tunggu ...')
				.attr('disabled','disabled');
				searchList();
			});
		});

		function init(){
			$('.select2').select2();

			$('#datepicker').datepicker({
				format: "dd-M-yyyy",
				autoclose: true
			});
		}

		function searchList(){
	        var req = $.ajax({
	            type: "POST",
	            url: "{{ url('deliveryorder/search-table-list') }}",
	            data: {
	            	_token: $('input[name=_token]').val(),
	                receiptno: $('input[name=receiptno]').val(),
	                dodate: $('input[name=dodate]').val(),
	                senderid: $('select[name=senderid]').val(),
	                recipientid: $('select[name=recipientid]').val(),
	                note: $('input[name=note]').val(),
	                containername: $('input[name=containername]').val(),
					tipe: $('select[name=tipe]').val(),					
	            },
	            beforeSend: function( xhr ) {
	                $('.overlay').show();
	            }
	        });

	        req.done(function(data){
	        	$('.overlay').hide();
	            $('.result').find('.box-body').html(data);

	            $('#btnsubmit').text('Cari')
				.removeAttr('disabled');

				$('.box-search').boxWidget('collapse');
	        });

	        req.fail(function(jqXHR, textStatus){
	            $('.overlay').hide();
	            alert( "Error" );
	        });
	    }
	</script>


@endsection
