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
					<h3 class="box-title">Laporan Perincian Barang</h3>
					<div class="box-tools pull-right">
						<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
						</button>
					</div>
			    </div>
			    <!-- /.box-header -->
			    <!-- form start -->
			    <form role="form" method="POST" id="form-add" action="{{ url('report/item_report-jasper') }}">
					{!! csrf_field() !!}

					<div class="box-body">
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label for="datefr">Tanggal Berangkat</label>
									<div class="input-group date">
										<div class="input-group-addon">
											<i class="fa fa-calendar"></i>
										</div>
										<input type="text" class="form-control pull-right datepicker" name="departdate" autocomplete="off">
									</div>
								<!-- /.input group -->
								</div>
								<!-- /.form group -->
							</div>
							<div class="col-md-4">
					            <div class="form-group">
									<label for="shipid">Nama Kapal</label>
									<select class="form-control select2" style="width: 100%;" name="shipid">
										<option value=""></option>
										@foreach($dropDownShip as $ship)
											<option value="{{ $ship->id }}" >{{ $ship->name }}</option>
										@endforeach
									</select>
					            </div>					          
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
					            <div class="form-group">
									<label for="senderid">Masuk Ke Kapal</label>
									<select class="form-control select2" style="width: 100%;" name="isship">
										<option value="1">Sudah</option>
										<option value="0">Belum</option>
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

			$('input[name=departdate]').focus();
		}



	</script>
@endsection
