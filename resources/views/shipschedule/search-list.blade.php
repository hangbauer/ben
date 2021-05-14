<table class="table table-bordered table-striped">
	<thead>
		<tr>
			<th>Nama Kapal</th>
			<th>Tanggal Berangkat</th>
			<th>Tujuan</th>
			<th>Voyage</th>
			<th>Keterangan</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		@foreach($listShipSchedule as $table)
			<tr>
				<td>{{ $table->shipname }}</td>
				<td>{{ Date('d-M-Y', strtotime($table->departdate)) }}</td>
				<td>{{ $table->destination }}</td>
				<td>{{ $table->voyage }}</td>
				<td>{{ $table->note }}</td>
				<td><a href="{{ url('shipschedule/'.$table->id.'/edit') }}" class="btn btn-xs btn-warning"><i class="fa fa-edit"></i> Ubah</a></td>
			</tr>
		@endforeach
	</tbody>
	<tfoot>
		<tr>
			<th>Nama Kapal</th>
			<th>Tanggal Berangkat</th>
			<th>Tujuan</th>
			<th>Voyage</th>
			<th>Keterangan</th>
			<th></th>
		</tr>
	</tfoot>
</table>