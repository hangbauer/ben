@extends('layout')

@section('plugins')
  <!-- DataTables -->
  <link rel="stylesheet" href="{{ asset('bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
@endsection

@section('content')
	<a href="{{ url('sender/create') }}" class="btn btn-primary"><i class="fa fa-fw fa-plus"></i> Tambah</a>

	<div class="box">
		<div class="box-header">
			<h3 class="box-title">Pengirim</h3>
		</div>
		<!-- /.box-header -->
		<div class="box-body table-responsive">
			<table id="example1" class="table table-bordered table-striped">
				<thead>
					<tr>
						<th>Nama</th>
						<th>Alamat</th>
						<th>Telepon</th>
						<th>Kota</th>
						<th>Status</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					@foreach($dataTableSender as $table)
						<tr>
							<td>{{ $table->name }}</td>
							<td>{{ $table->address }}</td>
							<td>{{ $table->phone }}</td>
							<td>{{ $table->city }}</td>
							<td>{{ $table->status == 0 ? 'Aktif' : 'Tidak Aktif' }}</td>
							<td><a href="{{ url('sender/'.$table->id.'/edit') }}" class="btn btn-xs btn-warning"><i class="fa fa-edit"></i> Ubah</a></td>
						</tr>
					@endforeach
				</tbody>
				<tfoot>
					<tr>
						<th>Nama</th>
						<th>Alamat</th>
						<th>Telepon</th>
						<th>Kota</th>
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