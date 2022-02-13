<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use View;
use Session;
use Auth;
use PDF;
use Excel;

use App\Report;
use App\Ship;
use App\Recipient;
use App\Sender;
use App\PaymentType;
use App\Invoice;

class ReportController extends Controller
{
    public function __construct()
    {
        // parent::__construct();
        View::share ( 'menuName', 'Laporan' );
        // View::share ( 'subMenuName', 'Rekap Cek' );

        $this->middleware('auth');

        $this->middleware(function ($request, $next) {
            $this->userid = Auth::user()->id;
            $this->branchid = Auth::user()->branchid;

            return $next($request);
        });

        $this->jasperUrl = 'http://localhost:8082/jasperserver/rest_v2/reports/Report_BEN/';
        $this->jasperUserPass = 'jasperadmin:jasperadmin';
    }

    public function loadingList()
    {
        View::share ( 'subMenuName', 'Loading List' );

        $dropDownShip = Ship::getDropDownShip();

        return view('report.loadinglist')
        	->with('dropDownShip', $dropDownShip)
            ;
    }

    public function loadingListExcel(Request $request)
    {
    	$data = Report::getLoadingListData($request, $this->userid, $this->branchid);

    	switch ($request->reporttypeid) {
    		case '1': //html
    			return view('report.loadinglist-excel')
    				->with('data', $data)
    				;
    			break;

    		case '2': //excel
    			Excel::create('New file', function($excel) use($data) {

				    $excel->sheet('New sheet', function($sheet) use($data){

				        $sheet->loadView('report.loadinglist-excel')
                            ->with('data', $data)
                            ->with('excelflag', true)
                            ;

				    });

				})->export('xls');

    			break;

    		case '3': //pdf
    			View::share ( 'data', $data );
    			$pdf = PDF::loadView('report.loadinglist-excel');
		        // download pdf
		        // return $pdf->download('pdfview.pdf');
		        return $pdf->stream('pdfview.pdf');

    			break;

    		default:
    			# code...
    			break;
    	}
    }

    public function deliveryOrder()
    {
        View::share ( 'subMenuName', 'Tanda Terima' );

        $dropDownShip = Ship::getDropDownShip();
        $dropDownRecipient = Recipient::getDropDownRecipient();
        $dropDownPaymentType = PaymentType::getDropDownPaymentType();

        return view('report.deliveryorder')
            ->with('dropDownShip', $dropDownShip)
            ->with('dropDownRecipient', $dropDownRecipient)
            ->with('dropDownPaymentType', $dropDownPaymentType)
            ;
    }

    public function deliveryOrderExcel(Request $request)
    {
        $data = Report::getDeliveryOrderData($request, $this->userid, $this->branchid);
        
        if(count($data) == 0){
            return 'No Data';
        }

        ini_set("pcre.backtrack_limit", "5000000");

        $ve = view('report.deliveryorder-pdf')
        ->with('data', $data);
        // Require composer autoload
        //require_once __DIR__ . '/vendor/autoload.php';
        // Create an instance of the class:
        $mpdf = new \Mpdf\Mpdf([
            'format' => [215.9, 139.7],
            'margin_top' => 5,
            'margin_bottom' => 5,
            ]);

        // Write some HTML code:
        $mpdf->WriteHTML($ve);

        // Output a PDF file directly to the browser
        $mpdf->Output();

        switch ($request->reporttypeid) {
            case '1': //html
                return view('report.deliveryorder-excel')
                    ->with('data', $data)
                    ;
                break;

            case '2': //excel
                Excel::create('New file', function($excel) use($data) {

                    $excel->sheet('New sheet', function($sheet) use($data){

                        $sheet->loadView('report.deliveryorder-excel')
                            ->with('data', $data)
                            ->with('excelflag', true)
                            ;

                    });

                })->export('xls');

                break;

            case '3': //pdf
                View::share ( 'data', $data );
                $pdf = PDF::loadView('report.deliveryorder-excel');
                // download pdf
                // return $pdf->download('pdfview.pdf');
                return $pdf->stream('pdfview.pdf');

                break;

            default:
                # code...
                break;
        }
    }

    public function invoiceExcel(Request $request)
    {
        $data = Report::getInvoiceData($request, $this->userid, $this->branchid);

        switch ($request->reporttypeid) {
            case '1': //html
                return view('report.invoice-excel')
                    ->with('data', $data)
                    ;
                break;

            case '2': //excel
                Excel::create('New file', function($excel) use($data) {

                    $excel->sheet('New sheet', function($sheet) use($data){

                        $sheet->loadView('report.invoice-excel')
                            ->with('data', $data)
                            ->with('excelflag', true)
                            ;

                    });

                })->export('xls');

                break;

            case '3': //pdf
                View::share ( 'data', $data );
                $pdf = PDF::loadView('report.invoice-excel');
                // download pdf
                // return $pdf->download('pdfview.pdf');
                return $pdf->stream('pdfview.pdf');

                break;

            default:
                # code...
                break;
        }
    }

    public function invoiceJasper(Request $request)
    {
        $invoice = Invoice::getInvoiceMasByID($request->invoicemasid);
        $terbilang = terbilang(round($invoice[0]->amount)) . " Rupiah";

        $url = 'http://localhost:8082/jasperserver/rest_v2/reports/Report_BEN/Invoice.pdf?INVOICEMAS_ID='.$invoice[0]->id.'&TERBILANG='.urlencode($terbilang);
        // $headers = array('Content-Type: application/json', 'Accept: application/pdf', 'Connection: Keep-Alive');
        // var_dump($url);die;
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        // curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_USERPWD, "jasperadmin:jasperadmin");

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, 1);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_BINARYTRANSFER, 1);

        $file = curl_exec($curl);

        curl_close($curl);

        $file_array = explode("\n\r", $file, 2);
        $header_array = explode("\n", $file_array[0]);
        foreach($header_array as $header_value) {
            $header_pieces = explode(':', $header_value);
            if(count($header_pieces) == 2) {
                $headers[$header_pieces[0]] = trim($header_pieces[1]);
            }
        }
        header('Content-type: ' . $headers['Content-Type']);
        header('Content-Disposition: ' . $headers['Content-Disposition']);
        // echo substr($file_array[1], 1);

        return substr($file_array[1], 1);
    }

    public function deliveryOrderReport()
    {
        View::share ( 'subMenuName', 'Laporan Tanda Terima' );

        $dropDownShip = Ship::getDropDownShip();
        $dropDownRecipient = Recipient::getDropDownRecipient();
        $dropDownSender = Sender::getDropDownSender();

        return view('report.deliveryorder_report')
            ->with('dropDownShip', $dropDownShip)
            ->with('dropDownRecipient', $dropDownRecipient)
            ->with('dropDownSender', $dropDownSender)
            ;
    }

    public function deliveryOrderReportExcel(Request $request)
    {
        $data = Report::getDeliveryOrderReportData($request, $this->userid, $this->branchid);
        
        switch ($request->reporttypeid) {
            case '1': //html
                return view('report.deliveryorder_report-excel')
                    ->with('data', $data)
                    ;
                break;

            case '2': //excel
                Excel::create('New file', function($excel) use($data) {

                    $excel->sheet('New sheet', function($sheet) use($data){

                        $sheet->loadView('report.deliveryorder_report-excel')
                            ->with('data', $data)
                            ->with('excelflag', true)
                            ;
                    });

                })->export('xls');

                break;

            case '3': //pdf
                View::share ( 'data', $data );
                $pdf = PDF::loadView('report.deliveryorder_report-excel');
                // download pdf
                // return $pdf->download('pdfview.pdf');
                return $pdf->stream('pdfview.pdf');

                break;

            default:
                # code...
                break;
        }
    }

    public function invoiceReport()
    {
        View::share ( 'subMenuName', 'Laporan Invoice' );

        $dropDownRecipient = Recipient::getDropDownRecipient();
        $dropDownSender = Sender::getDropDownSender();

        return view('report.invoice_report')
            ->with('dropDownRecipient', $dropDownRecipient)
            ->with('dropDownSender', $dropDownSender)
            ;
    }

    public function invoiceReportExcel(Request $request)
    {
        $data = Report::getInvoiceReportData($request, $this->userid, $this->branchid);

        switch ($request->reporttypeid) {
            case '1': //html
                return view('report.invoice_report-excel')
                    ->with('data', $data)
                    ;
                break;

            case '2': //excel
                Excel::create('New file', function($excel) use($data) {

                    $excel->sheet('New sheet', function($sheet) use($data){

                        $sheet->loadView('report.invoice_report-excel')
                            ->with('data', $data)
                            ->with('excelflag', true)
                            ;

                    });

                })->export('xls');

                break;

            case '3': //pdf
                View::share ( 'data', $data );
                $pdf = PDF::loadView('report.invoice_report-excel');
                // download pdf
                // return $pdf->download('pdfview.pdf');
                return $pdf->stream('pdfview.pdf');

                break;

            default:
                # code...
                break;
        }
    }

    public function itemReport()
    {
        View::share ( 'subMenuName', 'Laporan Perincian Barang' );

        $dropDownShip = Ship::getDropDownShip();
        $dropDownRecipient = Recipient::getDropDownRecipient();
        $dropDownSender = Sender::getDropDownSender();

        return view('report.item_report')
            ->with('dropDownShip', $dropDownShip)     
            ->with('dropDownRecipient', $dropDownRecipient)
            ->with('dropDownSender', $dropDownSender)       
            ;
    }

    public function itemReportJasper(Request $request)
    {
        $dateFrom = $request->datefrom == NULL ? 'ALL' : Date('Y-m-d', strtotime($request->datefrom));
        $dateTo = $request->dateto == NULL ? 'ALL' : Date('Y-m-d', strtotime($request->dateto));
        $shipId = $request->shipid == NULL ? 0 : $request->shipid;
        $isShip = $request->isship == NULL ? 0 : $request->isship;
        $containerName = $request->containername == NULL ? 'ALL' : $request->containername;
        $recId = $request->recipientid == NULL ? 0 : $request->recipientid;
        $sendId = $request->senderid == NULL ? 0 : $request->senderid;

        $format = 'pdf';
        switch ($request->reporttypeid) {
            case '1': //html
    			$format = 'html';
    			break;

    		case '2': //excel
    			$format = 'xlsx';
    			break;

    		case '3': //pdf
    			$format = 'pdf';

    			break;

    		default:
    			# code...
    			break;
    	}
        $url = $this->jasperUrl . 'ItemReport.'.$format.'?DATE_FROM='.$dateFrom.'&DATE_TO='.$dateTo.'&SHIP_ID='.$shipId.'&IS_SHIP='.$isShip.'&CONTAINER_NAME='.$containerName.'&REC_ID='.$recId.'&SEND_ID='.$sendId;
        
        // $headers = array('Content-Type: application/json', 'Accept: application/pdf', 'Connection: Keep-Alive');
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        // curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_USERPWD, $this->jasperUserPass);

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, 1);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_BINARYTRANSFER, 1);

        $file = curl_exec($curl);

        curl_close($curl);

        $file_array = explode("\n\r", $file, 2);
        $header_array = explode("\n", $file_array[0]);
        foreach($header_array as $header_value) {
            $header_pieces = explode(':', $header_value);
            if(count($header_pieces) == 2) {
                $headers[$header_pieces[0]] = trim($header_pieces[1]);
            }
        }
        // var_dump($headers);die;
        header('Content-type: ' . $headers['Content-Type']);
        if($format == 'pdf'){
            header('Content-Disposition: ' . $headers['Content-Disposition']);
        }
        
        // echo substr($file_array[1], 1);

        return substr($file_array[1], 1);
    }

    public function containerReport()
    {
        View::share ( 'subMenuName', 'Laporan Perincian Kontainer' );

        $dropDownShip = Ship::getDropDownShip();
        $dropDownRecipient = Recipient::getDropDownRecipient();
        $dropDownSender = Sender::getDropDownSender();

        return view('report.container_report')
            ->with('dropDownShip', $dropDownShip)           
            ->with('dropDownRecipient', $dropDownRecipient)
            ->with('dropDownSender', $dropDownSender)        
            ;
    }

    public function ContainerReportExcel(Request $request)
    {
    	$data = Report::getContainerListData($request, $this->userid, $this->branchid);

    	switch ($request->reporttypeid) {
    		case '1': //html
    			return view('report.container_report-excel')
    				->with('data', $data)
    				;
    			break;

    		case '2': //excel
    			Excel::create('New file', function($excel) use($data) {

				    $excel->sheet('New sheet', function($sheet) use($data){

				        $sheet->loadView('report.container_report-excel')
                            ->with('data', $data)
                            ->with('excelflag', true)
                            ;

				    });

				})->export('xls');

    			break;

    		case '3': //pdf
    			View::share ( 'data', $data );
    			$pdf = PDF::loadView('report.container_report-excel');
		        // download pdf
		        // return $pdf->download('pdfview.pdf');
		        return $pdf->stream('pdfview.pdf');

    			break;

    		default:
    			# code...
    			break;
    	}
    }
}
