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
}
