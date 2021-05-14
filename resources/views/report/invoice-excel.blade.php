<html>
  <head>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
  </head>
  <body>
    @if(!isset($excelflag))
    <style type="text/css">
      body, table{
        font-size: 12px;
      }
      table {
        border-collapse: collapse;
        width: 100%;
      }

      .table-data {
        border: 1px solid black;
        height: 220px;
        border-collapse: collapse;
      }
      .table-data th {
        border: 1px solid black;
      }
      .table-data td {
        border: 1px solid black;
        /*border-bottom: 0;*/
      }
      .table-data td.item {
        border: 1px solid black;
        border-bottom: 0;
        border-top: 0;
      }
      .table-data td:empty {
        border-top: 0;
        /*border-bottom: 0;*/
        /*border-left: 0;
        border-right: 0;*/
      }
      .table-data td.noline {
        border-top: 0;
        border-bottom: 0;
        /*border-left: 0;
        border-right: 0;*/
      }
      .table-data tr {
        border-bottom: 0;
        /*border-bottom: 0;*/
      }
      .table-data tr.diff {
        border-top:  1px solid black;
        /*border-bottom: 0;*/
      }

      .penerima{
        height: 70px;
        vertical-align: top;
      }
    </style>
    @endif
      <table>
        @if($data[0]->bankname == 'PANIN')
        <tr>
          <td style="text-align: center; font-size: 20px;"><strong>PT. BINA ERA NUSANTARA</strong></td>
        </tr>
        @endif
        <tr>
          <td style="text-align: center;"><strong>Freight Forwarder</strong></td>
        </tr>
      </table>
      <br>
      <table>
        <tr>
          <td width="70%">Nama Kapal : {{$data[0]->shipname}}</td>
          <td width="30%">Kepada : {{$data[0]->invoicetypeid == 0 ? $data[0]->sendername : $data[0]->recname }}</td>
        </tr>
        <tr>
          <td>Tanggal Berangkat : {{date('d-m-Y', strtotime($data[0]->departdate))}}</td>
          <td>Alamat : {{$data[0]->invoicetypeid == 0 ? $data[0]->recaddress : $data[0]->senderaddress }}</td>
        </tr>
        <tr>
          <td>Asal : {{$data[0]->depart}}</td>
        </tr>
        <tr>
          <td>Tujuan : {{$data[0]->destination}}</td>
        </tr>
      </table>

      <table>
        <tr>
          <td style="text-align: center; font-size: 20px;"><strong>INVOICE</strong></td>
        </tr>
        <tr>
          <td style="text-align: center;"><strong>{{$data[0]->invoiceno}}</strong></td>
        </tr>
      </table>

      <p>Untuk pembayaran ongkos angkutan barang-barang sbb:</p>

      <table class="table-data">
        <thead>
          <tr>
            <th>Jenis Barang</th>
            <th>Jumlah</th>
            <th>Jumlah Rp.</th>
          </tr>
        </thead>
        <tbody>
          <?php $tempDoMasId = 0; $tempContainerName = '';?>
          @foreach($data as $dtl)
            <?php 
              if($tempContainerName == $dtl->containername){
                $tempContainerName = str_repeat('-', strlen($dtl->containername));
              }else{
                $tempContainerName = $dtl->containername;
              }
            ?>
            @if($dtl->domasid == $tempDoMasId)
            <tr class="item" style="line-height: 15px; height: 15px; border-top: 0;">
            @else
            <tr class="item diff" style="line-height: 15px; height: 15px;">
            @endif
              <td class="item" width="60%"> {{ $tempContainerName }} - {{ $dtl->conname }} / {{ $dtl->itemname }} / {{ $dtl->shipname }}</td>
              <td class="item" style="text-align: right;">{{ $dtl->itemorder . ' ' . $dtl->itemunit }}</td>
              <td class="item" style="text-align: right;">{{ $dtl->domasid == $tempDoMasId ? '' : number_format($dtl->dtlamount) }}</td>
            </tr>
            <?php $tempDoMasId = $dtl->domasid; $tempContainerName = $dtl->containername; ?>
          @endforeach
          <tr>
            <td></td>
            <td></td>
            <td></td>
          </tr>
        </tbody>
        <tfoot>
          <tr>
            <td class="noline"></td>
            <td class="total">Total</td>
            <td class="total" style="text-align: right;">{{number_format($data[0]->amount - $data[0]->ppnamount)}}</td>
          </tr>
          <tr>
            <td class="noline"></td>
            <td class="total">PPN {{$data[0]->ppnpercent . '%'}} </td>
            <td class="total" style="text-align: right;">{{number_format($data[0]->ppnamount)}}</td>
          </tr>
          <tr>
            <td class="noline"></td>
            <td class="total">Asuransi </td>
            <td class="total" style="text-align: right;">{{number_format($data[0]->insurance)}}</td>
          </tr>
          <tr>
            <td class="noline"></td>
            <td class="total">Grand Total </td>
            <td class="total" style="text-align: right;">{{number_format($data[0]->amount)}}</td>
          </tr>
        </tfoot>
      </table>

      <br/>

      <table>
        <tr>
          <td width="30%">Terbilang &nbsp &nbsp {{terbilang($data[0]->amount+0)}} Rupiah</td>
        </tr>
      </table>
      <br/>
      <br/>
      <table>
        <tr>
          <td width="70%">Bank Transfer:</td>
          <td width="30%">JAKARTA, {{date('d-m-Y', strtotime($data[0]->invoicedate))}}</td>
        </tr>
        <tr>
          <td>{{$data[0]->bankname}}</td>
          <td></td>
        </tr>
        <tr>
          <td>A/C Nama : {{$data[0]->accountname}}</td>
          <td></td>
        </tr>
        <tr>
          <td>A/C Nomor : {{$data[0]->accountno}}</td>
          <td>Danny Kusuma</td>
        </tr>
      </table>


</html>
