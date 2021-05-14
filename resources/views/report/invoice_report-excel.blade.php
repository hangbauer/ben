<html>
  <head></head>
  <body>
    @if(!isset($excelflag))
    <style type="text/css">
      table {
        border-collapse: collapse;
        width: 100%;
      }

      th{
        border-bottom: 1px solid black;
        text-align: left;
      }

      /*table, th, td {
        border: 1px solid black;
      }*/
    </style>
    @endif

    <p><h3> Laporan Invoice (PT. Bina Era Nusantara) </h3></p>

    <table>
      <thead>
        <tr>
          <th>No. Invoice</th>
          <th>Tanggal</th>
          <th>Nama Pengirim</th>
          <th>Nama Penerima</th>
          <th style="text-align: right;">Jumlah</th>
          <th style="text-align: right;">Sudah Dibayar</th>
        </tr>
      </thead>
      <tbody>
        @foreach($data as $dtl)
          <tr>
            <td>{{ $dtl->invoiceno }}</td>
            <td>{{ date('d-M-Y', strtotime($dtl->invoicedate)) }}</td>
            <td>{{ $dtl->sendername }}</td>
            <td>{{ $dtl->recname }}</td>
            <td style="text-align: right;">{{ number_format($dtl->amount,2) }}</td>
            <td style="text-align: right;">{{ number_format($dtl->paidamount,2) }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </body>

</html>
