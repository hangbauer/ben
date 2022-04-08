<html>
  <head>
    <style type="text/css">
        @if(!isset($excelflag))
            body, table{
                font-size: 12px;
            }
            table {
                border-collapse: collapse;
                width: 100%;
            }

            .table-data {
                overflow: hidden;
                border: 1px solid black;
                height: 180px;
                border-collapse: collapse;
            }
            .table-data th {
                border: 1px solid black;
            }
            .table-data td {
                border: 1px solid black;
                border-bottom: 0;
            }
            .table-data tr {
                border: 1px solid black;
                border-bottom: 0;
            }
            .table-data td:empty {
                border-top: 0;
                /*border-left: 0;
                border-right: 0;*/
            }

            .penerima{
                height: 70px;
                vertical-align: top;
            }
        @endif

        @page { size: 241.3mm 139.5mm } /* output size */
        /*body.receipt .sheet { width: 241.3mm; height: 139.7mm } /* sheet size */
        .sheet { width: 241.3mm; height: 139.5mm } /* sheet size */
        @media print { .sheet { width: 241.3mm };} /* fix for Chrome */
    </style>
  </head>

    <body class="receipt">
        <?php $tempShipId = ''; ?>
        @foreach($data as $dtl)
        @if($tempShipId != $dtl->shipscid . $dtl->recipientid . $dtl->containername)
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
                <?php $tempReceiptNo = ''; ?>
                @foreach($data as $dtl2)
                    @if($dtl->shipscid . $dtl->recipientid . $dtl->containername == $dtl2->shipscid . $dtl2->recipientid . $dtl2->containername)
                    <tr style="line-height: 15px; height: 15px;">
                    <td>{{ $dtl2->receiptno }}</td>
                    <td>{{ $dtl2->dodate }}</td>
                    <td>{{ $dtl2->itemorder . ' ' . $dtl2->itemunit }}</td>
                    <td>{{ $dtl2->itemname }}</td>
                    <td>{{ $tempReceiptNo == $dtl2->receiptno ? '' : $dtl2->note }}</td>
                    </tr>
                    @endif
                    <?php $tempReceiptNo = $dtl2->receiptno; ?>
                @endforeach
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
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
                <td width="30%">__________________________</td>
                </tr>
                <tr>
                <td class="penerima">PENERIMA, TTD & CAP</td>
                <td class="penerima">HORMAT KAMI</td>
                </tr>
                <tr>
                <td>(...............................)</td>
                <td>(...............................)</td>
                </tr>
            </table>
            <p>TANPA KETERANGAN, barang telah diterima dengan baik dan cukup</p>
            <?php $tempShipId = $dtl->shipscid . $dtl->recipientid . $dtl->containername; ?>
            </section>
        @endif
        @endforeach
    </body>
</html>
