<table class="table table-bordered table-striped">
	<thead>
		<tr>
			<th>Nomor Kasir Masuk</th>
			<th>Tanggal</th>
			<th>Nama Bank</th>
			<th>Nama Pengirim</th>
			<th>Nama Penerima</th>
			<th>Keterangan</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		@foreach($listCashierin as $table)
			<tr>
				<td>{{ $table->cashierinno }}</td>
				<td>{{ Date('d-M-Y', strtotime($table->cashierindate)) }}</td>
				<td>{{ $table->bankname }}</td>
				<td>{{ $table->sendername }}</td>
				<td>{{ $table->recipientname }}</td>
				<td>{{ $table->note }}</td>
				<td>
					<a href="{{ url('cashierin/'.$table->id.'/edit') }}" class="btn btn-xs btn-warning"><i class="fa fa-edit"></i> Ubah</a>
					<button type="button" class="btn btn-xs btn-danger btnDel" value="{{$table->id}}" >Hapus</button>
				</td>
			</tr>
		@endforeach
	</tbody>
	<tfoot>
		<tr>
			<th>Nomor Kasir Masuk</th>
			<th>Tanggal</th>
			<th>Nama Bank</th>
			<th>Nama Pengirim</th>
			<th>Nama Penerima</th>
			<th>Keterangan</th>
			<th></th>
		</tr>
	</tfoot>
</table>