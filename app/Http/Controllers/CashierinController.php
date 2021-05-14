<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use View;
use Session;
use Auth;

use App\Cashierin;
use App\Sender;
use App\Recipient;
use App\Bank;

class CashierinController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        View::share ( 'menuName', 'Transaksi' );
        View::share ( 'subMenuName', 'Kasir Invoice' );

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
        $dropDownBank = Bank::getDropDownBank();
        
        return view('cashierin.index')
            ->with('dropDownSender', $dropDownSender)
            ->with('dropDownRecipient', $dropDownRecipient)
            ->with('dropDownBank', $dropDownBank)
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

        return view('cashierin.create')
            ->with('dropDownSender', $dropDownSender)
            ->with('dropDownRecipient', $dropDownRecipient)
            ->with('dropDownBank', $dropDownBank)            
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
            'cashierindate' => 'required',
            'bankid' => 'required',
        ]);

        $cashierin = CashierIn::setCashierIn(0, $request, $this->userid, $this->branchid);
        $cashierinMasId = Cashierin::insertCashierIn($cashierin);
        
        Session::flash('message', 'Berhasil! Pembayaran berhasil ditambah'); 
        Session::flash('alert-class', 'alert-success'); 

        return redirect('cashierin');
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {    
        Cashierin::deleteCashierin($id);
        Session::flash('message', 'Berhasil! Kasir berhasil dihapus'); 
        Session::flash('alert-class', 'alert-success'); 

        return redirect('cashierin');
    }

    public function searchTableList(Request $request){                
        $listCashierin = Cashierin::searchTableList($request, $this->userid, $this->branchid);

        return view('cashierin.search-list')
            ->with('listCashierin', $listCashierin)
            ;
    }
}
