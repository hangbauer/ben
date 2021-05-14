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

    <p><h3> Laporan Tanda Terima (PT. Bina Era Nusantara) </h3></p>

    <table>
      <thead>
        <tr>
          <th>No. Tanda Terima</th>
          <th>Tanggal Tanda Terima</th>
          <th>Nama Penerima</th>
          <th>Nama Pengirim</th>
          <th>Nama Kontainer</th>
          <th>Seal</th>
          <th>Invoice</th>
          <th>Voyage</th>
          <th>Tujuan</th>
        </tr>
      </thead>
      <tbody>
        @foreach($data as $dtl)
          <tr>
            <td>{{ $dtl->receiptno }}</td>
            <td>{{ date('d-M-Y', strtotime($dtl->dodate)) }}</td>
            <td>{{ $dtl->recname }}</td>
            <td>{{ $dtl->sendname }}</td>
            <td>{{ $dtl->containername }}</td>
            <td>{{ $dtl->seal }}</td>
            <td>{{ $dtl->invoiceno }}</td>
            <td>{{ $dtl->voyage }}</td>
            <td>{{ $dtl->destination }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </body>

</html>
