<table class="table table-bordered table-striped">
	<thead>
		<tr>
			<th>Nomor Invoice</th>
			<th>Tanggal Invoice</th>
			<th>Tipe Invoice</th>
			<th>Nama Pengirim/Penerima</th>
			<th>Keterangan</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		@foreach($listInvoice as $table)
			<tr>
				<td>{{ $table->invoiceno }}</td>						
				<td>{{ Date('d-M-Y', strtotime($table->invoicedate)) }}</td>
				<td>{{ $table->invoicetypeid == 0 ? 'Pengirim' : 'Penerima' }}</td>
				<td>{{ $table->invoicetypeid == 0 ? $table->sendername : $table->recipientname }}</td>
				<td>{{ $table->note }}</td>						
				<td>
					<a target="_blank" href="{{ url('invoice/'.$table->id.'/edit') }}" class="btn btn-xs btn-warning"><i class="fa fa-edit"></i> Ubah</a>
					<a target="_blank" href="{{ url('report/invoice-jasper?reporttypeid=1&invoicemasid='.$table->id) }}" class="btn btn-xs btn-default"><i class="fa fa-print"></i> Print</a>
				</td>
			</tr>
		@endforeach
	</tbody>
	<tfoot>
		<tr>
			<th>Nama Invoice</th>
			<th>Tanggal Berangkat</th>
			<th>Tujuan</th>
			<th>Keterangan</th>
			<th></th>
		</tr>
	</tfoot>
</table>