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

    <?php $tempHeader = "";?>
    <p><h3> Laporan Perincian Kontainer (PT. Bina Era Nusantara) </h3></p>
    <table>
      <thead>
          <tr>
            <th>Nama Kapal</th>
            <th>Tanggal Berangkat</th>            
            <th>No. Kontainer</th>
            <th>No. Seal</th>            
          </tr>
      </thead>
      <tbody>
        @foreach($data as $dtl)
          <tr>              
              <td>{{ $dtl->shipname }}</td>              
              <td>{{ Date('d F Y', strtotime($dtl->departdate)) }}</td>  
              <td>{{ $dtl->containername }}</td>              
              <td>{{ $dtl->seal }}</td>             
          </tr>
        @endforeach    
      </tbody>  
    </table>
  </body>

</html>
