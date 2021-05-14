<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use View;
use Session;
use Auth;

use App\Invoice;
use App\Bank;
use App\DeliveryOrder;
use App\Sender;
use App\Recipient;
use App\Ship;

class InvoiceController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        View::share ( 'menuName', 'Transaksi' );
        View::share ( 'subMenuName', 'Invoice' );

        $this->middleware('auth');

        $this->middleware(function ($request, $next) {
            $this->userid = Auth::user()->id; 
            $this->branchid = Auth::user()->branchid;

            return $next($request);
        });
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $dropDownSender = Sender::getDropDownSender();
        $dropDownRecipient = Recipient::getDropDownRecipient();
        
        return view('invoice.index')
            ->with('dropDownSender', $dropDownSender)
            ->with('dropDownRecipient', $dropDownRecipient)
            ;        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $dropDownSender = Sender::getDropDownSender();
        $dropDownRecipient = Recipient::getDropDownRecipient();
        $dropDownBank = Bank::getDropDownBank();
        $dropDownShip = Ship::getDropDownShip();        

        return view('invoice.create')
            ->with('dropDownSender', $dropDownSender)
            ->with('dropDownRecipient', $dropDownRecipient)
            ->with('dropDownBank', $dropDownBank)
            ->with('dropDownShip', $dropDownShip)
            ;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'bankid' => 'required',
        ]);

        $invoice = Invoice::setInvoice(0, $request, $this->userid, $this->branchid);
        Invoice::insertInvoice($invoice);
        Session::flash('message', 'Berhasil! Invoice berhasil ditambah'); 
        Session::flash('alert-class', 'alert-success'); 

        return redirect('invoice');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $dropDownSender = Sender::getDropDownSender();
        $dropDownRecipient = Recipient::getDropDownRecipient();
        $dropDownBank = Bank::getDropDownBank();
        $dropDownShip = Ship::getDropDownShip();

        $invoice['invoiceMas'] = Invoice::getInvoiceMasByID($id);
        $invoice['invoiceDtl'] = Invoice::getInvoiceDtlByInvoiceMasID($id);

        return view('invoice.create')
            ->with('invoice', $invoice)
            ->with('dropDownSender', $dropDownSender)
            ->with('dropDownRecipient', $dropDownRecipient)
            ->with('dropDownBank', $dropDownBank)
            ->with('dropDownShip', $dropDownShip)
            ;    
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'bankid' => 'required',
        ]);

        $invoice = Invoice::setInvoice(1, $request, $this->userid, $this->branchid);
        Invoice::updateInvoice($id, $invoice);
        Session::flash('message', 'Berhasil! Invoice berhasil diubah'); 
        Session::flash('alert-class', 'alert-success'); 

        return redirect('invoice');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(Invoice::isUsedInvoice($id)){
            Session::flash('message', 'Gagal! Invoice Sudah dibayar'); 
            Session::flash('alert-class', 'alert-danger'); 

            return back();
        }

        Invoice::deleteInvoice($id);
        Session::flash('message', 'Berhasil! Invoice berhasil dihapus'); 
        Session::flash('alert-class', 'alert-success'); 

        return redirect('invoice');
    }
    
    public function getInvoiceNo(Request $request){        
        $invoiceNo = Invoice::getInvoiceNo($request, $this->userid, $this->branchid);

        return json_encode($invoiceNo)
            ;
    }

    public function getUnpaidList(Request $request)
    {
        $listInvoice = Invoice::getUnpaidList($request, $this->userid, $this->branchid);

        return view('invoice.unpaidlist')
            ->with('listInvoice', $listInvoice)
            ;
    }

    public function searchTableList(Request $request){                
        $listInvoice = Invoice::searchTableList($request, $this->userid, $this->branchid);

        return view('invoice.search-list')
            ->with('listInvoice', $listInvoice)
            ;
    }
}
