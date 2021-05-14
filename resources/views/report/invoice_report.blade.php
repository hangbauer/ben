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
	<div class="row">
        <div class="col-md-12">
			<div class="box box-success box-search">
				{{-- collapsed-box --}}
			    <div class="box-header with-border">
					<h3 class="box-title">Laporan Invoice</h3>
					<div class="box-tools pull-right">
						<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
						</button>
					</div>
			    </div>
			    <!-- /.box-header -->
			    <!-- form start -->
			    <form role="form" method="POST" id="form-add" action="{{ url('report/invoice_report-excel') }}">
					{!! csrf_field() !!}

					<div class="box-body">
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label for="datefr">Tanggal Awal</label>
									<div class="input-group date">
										<div class="input-group-addon">
											<i class="fa fa-calendar"></i>
										</div>
										<input type="text" class="form-control pull-right datepicker" name="datefr" autocomplete="off">
									</div>
								<!-- /.input group -->
								</div>
								<!-- /.form group -->
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="dateto">Tanggal Akhir</label>
									<div class="input-group date">
										<div class="input-group-addon">
											<i class="fa fa-calendar"></i>
										</div>
										<input type="text" class="form-control pull-right datepicker" name="dateto" autocomplete="off">
									</div>
								<!-- /.input group -->
								</div>
								<!-- /.form group -->
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
					            <div class="form-group">
									<label for="senderid">Nama Pengirim</label>
									<select class="form-control select2" style="width: 100%;" name="senderid">
										<option value=""></option>
										@foreach($dropDownSender as $sender)
											<option value="{{ $sender->id }}" >{{ $sender->name }}</option>
										@endforeach
									</select>
					            </div>
					        </div>
							<div class="col-md-4">
					            <div class="form-group">
									<label for="recipientid">Nama Penerima</label>
									<select class="form-control select2" style="width: 100%;" name="recipientid">
										<option value=""></option>
										@foreach($dropDownRecipient as $recipient)
											<option value="{{ $recipient->id }}" >{{ $recipient->name }}</option>
										@endforeach
									</select>
					            </div>
					        </div>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label>Status</label>
									<select class="form-control select2" style="width: 100%;" name="status">
									<option value="0">Semua</option>
									<option value="1">Belum Lunas</option>
									<option value="2">Sudah Lunas</option>
									</select>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label>Tipe Laporan</label>
									<select class="form-control select2" style="width: 100%;" name="reporttypeid">
									<option value="1">Html</option>
									<option value="2">Excel</option>
									<option value="3">Pdf</option>
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


@endsection

@section('scripts')
	<script src="{{ asset('bower_components/select2/dist/js/select2.full.min.js') }}"></script>
	<script src="{{ asset('bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
	<script src="{{ asset('bower_components/autonumeric/autoNumeric.js') }}"></script>

	<script>
		$(function() {
			init();

			$('#btnsubmit').on('click',function(){
				// $(this).text('Harap Tunggu ...')
				// .attr('disabled','disabled');
				$('#form-add').submit();
			});
		});

		function init(){
			$('.select2').select2();

			$('.datepicker').datepicker({
				format: "dd-M-yyyy",
				autoclose: true
			});
		}



	</script>
@endsection
