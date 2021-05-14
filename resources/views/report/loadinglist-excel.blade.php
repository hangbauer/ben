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
    @foreach($data as $header)
        @if($tempHeader != $header->shipname.$header->departdate.$header->termname)
            <?php $tempHeader = $header->shipname.$header->departdate.$header->termname; ?>
            <p><h3> LOADING LIST (PT. Bina Era Nusantara) </h3></p>
            <p><h4> {{ $header->shipname }} - {{ $header->voyage }} </h4></p>
            <p><h4> {{ Date('d F Y', strtotime($header->departdate)) . ', ' . $header->depart . ' - ' . $header->destination }} </h4></p>
            <p><h4> {{ $header->termname }} </h4></p>

            <table>
            <thead>
                <tr>
                <th>#</th>
                <th>No. Kontainer</th>
                <th>Type</th>
                <th>No. Seal</th>
                <th>Jumlah</th>
                <th>Nama Barang</th>
                <th>Penerima</th>
                </tr>
            </thead>
            <tbody>
                <?php $tempName = ""; $show = true; $no = 0;?>
                @foreach($data as $dtl)
                    @if($tempHeader == $dtl->shipname.$dtl->departdate.$dtl->termname)
                        
                        @if($tempName == $dtl->containername)
                            <?php $show = false; ?>
                        @else
                            <?php $no++; ?>
                        @endif
                        <tr>
                            <td>{{ $show ? $no : '' }}</td>
                            <td>{{ $show ? $dtl->containername : '' }}</td>
                            <td>{{ $show ? $dtl->contypename : '' }}</td>
                            <td>{{ $show ? $dtl->seal : '' }}</td>
                            <td>{{ number_format($dtl->itemorder) }} {{ $dtl->itemunit }}</td>
                            <td>{{ $dtl->itemname }}</td>
                            <td>{{ $show ? $dtl->recname : '' }}</td>
                        </tr>
                        <?php $tempName = $dtl->containername; $show = true; ?>
                    @endif
                @endforeach
            </tbody>
            </table>
        @endif
    @endforeach
  </body>

</html>
