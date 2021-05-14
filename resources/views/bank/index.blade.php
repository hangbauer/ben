@extends('layout')

@section('plugins')
  <!-- DataTables -->
  <link rel="stylesheet" href="{{ asset('bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
@endsection

@section('content')
	<a href="{{ url('bank/create') }}" class="btn btn-primary"><i class="fa fa-fw fa-plus"></i> Tambah</a>

	<div class="box">
		<div class="box-header">
			<h3 class="box-title">Bank</h3>
		</div>
		<!-- /.box-header -->
		<div class="box-body table-responsive">
			<table id="example1" class="table table-bordered table-striped">
				<thead>
					<tr>
						<th>Nama</th>
						<th>Atas Nama</th>
						<th>Nomor Rekening</th>
						<th>Keterangan</th>
						<th>Status</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					@foreach($dataTableBank as $table)
						<tr>
							<td>{{ $table->name }}</td>
							<td>{{ $table->accountname }}</td>
							<td>{{ $table->accountno }}</td>
							<td>{{ $table->note }}</td>
							<td>{{ $table->status == 0 ? 'Aktif' : 'Tidak Aktif' }}</td>
							<td><a href="{{ url('bank/'.$table->id.'/edit') }}" class="btn btn-xs btn-warning"><i class="fa fa-edit"></i> Ubah</a></td>
						</tr>
					@endforeach
				</tbody>
				<tfoot>
					<tr>
						<th>Nama</th>
						<th>Atas Nama</th>
						<th>Nomor Rekening</th>
						<th>Keterangan</th>
						<th>Status</th>
						<th></th>
					</tr>
				</tfoot>
			</table>
		</div>
		<!-- /.box-body -->
	</div>
	<!-- /.box -->
@endsection

@section('scripts')
	<!-- DataTables -->
	<script src="{{ asset('bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
	<script src="{{ asset('bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>

	<script>
	    $(function() {
			init();

	      
	    });

	    function init(){
	    	$('#example1').DataTable()
	    }
	</script>

	
@endsection