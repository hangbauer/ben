<table class="table table-bordered table-striped">
	<thead>
		<tr>
			<th>Nomor Tanda Terima</th>
			<th>Tanggal</th>
			<th>Nama Pengirim</th>
			<th>Nama Penerima</th>
			<th>Nomor Kontainer</th>
			<th>Keterangan</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		@foreach($listDeliveryOrder as $table)
			<tr>
				<td>{{ $table->receiptno }}</td>
				<td>{{ Date('d-M-Y', strtotime($table->dodate)) }}</td>
				<td>{{ $table->sendername }}</td>
				<td>{{ $table->recipientname }}</td>
				<td>{{ $table->containername }}</td>
				<td>{{ $table->note }}</td>
				<td><a href="{{ url('deliveryorder/'.$table->id.'/edit') }}" class="btn btn-xs btn-warning"><i class="fa fa-edit"></i> Ubah</a></td>
			</tr>
		@endforeach
	</tbody>
	<tfoot>
		<tr>
			<th>Nomor Tanda Terima</th>
			<th>Tanggal</th>
			<th>Nama Pengirim</th>
			<th>Nama Penerima</th>
			<th>Nomor Kontainer</th>
			<th>Keterangan</th>
			<th></th>
		</tr>
	</tfoot>
</table>