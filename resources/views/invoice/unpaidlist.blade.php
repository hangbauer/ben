
@foreach($listInvoice as $inv)
	<tr>
		<input type="hidden" class="dtlid" name="dtlid[]" value="0">
        <input type="hidden" class="invoicemasid" name="invoicemasid[]" value="{{ $inv->id }}">
		<td>{{ $inv->invoiceno }}</td>		
		<td>{{ Date('d-M-Y', strtotime($inv->invoicedate)) }}</td>
		<td>{{ $inv->note }}</td>
		<td class="text-right">{{ number_format($inv->amount,0) }}</td>
		<td><input type="text" class="form-control text-right autonumeric amount" name="amount[]" value="0"></td>		
	</tr>
@endforeach
