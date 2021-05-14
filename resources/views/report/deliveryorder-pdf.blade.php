<html>
  <head>
    <style type="text/css">
        body, table{
            font-size: 12px;
        }
        table {
            /*border-collapse: collapse;*/
            width: 100%;
        }
        div.layout {
            height : 200;
        }
        .table-data {
            overflow: hidden;
            border: 1px solid black;
            height: 180px;
            border-collapse: collapse;
            /*border-bottom: 0;*/
        }
        .table-data th {
            border: 1px solid black;
        }
        .table-data td {
            border-right: 1px solid black;
        }
        
    </style>
  </head>

    <body class="receipt">
        <?php $tempShipId = ''; ?>
        @foreach($data as $dtl)
        @if($tempShipId != $dtl->shipscid . $dtl->recipientid . $dtl->containername)
            <div class="layout">
            <section class="sheet padding-10mm">
                <table>
                    <tr>
                    <td width="60%">PT. BINA ERA NUSANTARA</td>
                    <td width="40%"></td>
                    </tr>
                    <tr>
                    <td>PENERIMA : {{$dtl->recname}}</td>
                    <td>KAPAL : {{$dtl->shipname}}</td>
                    </tr>
                    <tr>
                    <td>ALAMAT : {{$dtl->recaddress}}</td>
                    <td>TANGGAL : {{date('d-m-Y', strtotime($dtl->departdate))}}</td>
                    </tr>
                </table>

                <p style="text-align: center;">** BUKTI PENERIMAAN BARANG **</p>

                <table class="table-data">
                    <thead>
                    <tr>
                        <th colspan="2">TANDA TERIMA</th>
                        <th>JUMLAH</th>
                        <th>NAMA BARANG</th>
                        <th>KETERANGAN</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $tempReceiptNo = ''; $itemCount = 0;?>
                    @foreach($data as $dtl2)
                        @if($dtl->shipscid . $dtl->recipientid . $dtl->containername == $dtl2->shipscid . $dtl2->recipientid . $dtl2->containername)
                        <?php $itemCount++; ?>
                        <tr style="line-height: 15px; height: 15px;">
                        <td width="20%">{{ $dtl2->receiptno }}</td>
                        <td width="10%">{{ $dtl2->dodate }}</td>
                        <td width="10%">{{ number_format($dtl2->itemorder,0) . ' ' . $dtl2->itemunit }}</td>
                        <td>{{ $dtl2->itemname }}</td>
                        <td>{{ $tempReceiptNo == $dtl2->receiptno ? '' : $dtl2->note }}</td>
                        </tr>
                        @endif
                        <?php $tempReceiptNo = $dtl2->receiptno; ?>
                    @endforeach
                    @if($itemCount < 12)
                    
                    <tr>
                        <td height="{{(12 - $itemCount)*16}}"></td>
                        <td height="{{(12 - $itemCount)*16}}"></td>
                        <td height="{{(12 - $itemCount)*16}}"></td>
                        <td height="{{(12 - $itemCount)*16}}"></td>
                        <td height="{{(12 - $itemCount)*16}}"></td>
                    </tr>
                    
                    @endif
                    </tbody>
                </table>

                <table>
                    <tr>
                    <td width="50%">No. CONTAINER : {{$dtl->containername}} / {{$dtl->contypename}}</td>
                    <td width="50%">No. SEAL : {{$dtl->seal}}</td>
                    </tr>
                </table>
                <table>
                    <tr>
                    <td width="70%"></td>
                    <td width="30%">JAKARTA, _________________</td>
                    </tr>
                    <tr>
                    <td class="penerima">PENERIMA, TTD & CAP</td>
                    <td class="penerima">HORMAT KAMI</td>
                    </tr>
                    <tr>
                    <td height="50"></td>
                    <td height="50"></td>
                    </tr>
                    <tr>
                    <td>(...............................)</td>
                    <td>(...............................)</td>
                    </tr>
                </table>
                <p>TANPA KETERANGAN, barang telah diterima dengan baik dan cukup</p>
                <?php $tempShipId = $dtl->shipscid . $dtl->recipientid . $dtl->containername; ?>
                </section>
            </div>
            
        @endif
        @endforeach
    </body>
</html>
