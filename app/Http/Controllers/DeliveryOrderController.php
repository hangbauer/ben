<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use View;
use Session;
use Auth;

use App\DeliveryOrder;
use App\ShipSchedule;
use App\Sender;
use App\Recipient;
use App\Term;
use App\PaymentType;
use App\ContainerType;

class DeliveryOrderController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        View::share ( 'menuName', 'Transaksi' );
        View::share ( 'subMenuName', 'Tanda Terima' );

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

        return view('deliveryorder.index')
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
        $dropDownShipSchedule = ShipSchedule::getDropDownShipSchedule();
        $dropDownSender = Sender::getDropDownSender();
        $dropDownRecipient = Recipient::getDropDownRecipient();
        $dropDownTerm = Term::getDropDownTerm();
        $dropDownPaymentType = PaymentType::getDropDownPaymentType();
        $dropDownContainerType = ContainerType::getDropDownContainerType();

        return view('deliveryorder.create')
            ->with('dropDownShipSchedule', $dropDownShipSchedule)
            ->with('dropDownSender', $dropDownSender)
            ->with('dropDownRecipient', $dropDownRecipient)
            ->with('dropDownTerm', $dropDownTerm)
            ->with('dropDownPaymentType', $dropDownPaymentType)
            ->with('dropDownContainerType', $dropDownContainerType)
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
            'receiptno' => 'required|unique:domas',
        ]);

        $do = DeliveryOrder::setDeliveryOrder(0, $request, $this->userid, $this->branchid);
        $doMasId = DeliveryOrder::insertDeliveryOrder($do);
        Session::flash('message', 'Berhasil! Tanda Terima berhasil ditambah');
        Session::flash('alert-class', 'alert-success');

        return redirect('deliveryorder');
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
        $dropDownShipSchedule = ShipSchedule::getDropDownShipSchedule();
        $dropDownSender = Sender::getDropDownSender();
        $dropDownRecipient = Recipient::getDropDownRecipient();
        $dropDownTerm = Term::getDropDownTerm();
        $dropDownPaymentType = PaymentType::getDropDownPaymentType();
        $dropDownContainerType = ContainerType::getDropDownContainerType();

        $do['doMas'] = DeliveryOrder::getDoMasByID($id);
        $do['doDtl'] = DeliveryOrder::getDoDtlByDoMasID($id);

        return view('deliveryorder.create')
            ->with('dropDownShipSchedule', $dropDownShipSchedule)
            ->with('dropDownSender', $dropDownSender)
            ->with('dropDownRecipient', $dropDownRecipient)
            ->with('dropDownTerm', $dropDownTerm)
            ->with('dropDownPaymentType', $dropDownPaymentType)
            ->with('dropDownContainerType', $dropDownContainerType)
            ->with('do', $do)
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
            'receiptno' => 'required',
        ]);

        $do = DeliveryOrder::setDeliveryOrder(1, $request, $this->userid, $this->branchid);
        DeliveryOrder::updateDeliveryOrder($id, $do);
        Session::flash('message', 'Berhasil! Tanda Terima berhasil diubah');
        Session::flash('alert-class', 'alert-success');

        return redirect('deliveryorder');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(DeliveryOrder::isUsedDeliveryOrder($id)){
            Session::flash('message', 'Gagal! Tanda Terima Sudah digunakan');
            Session::flash('alert-class', 'alert-danger');

            return back();
        }

        DeliveryOrder::deleteDeliveryOrder($id);
        Session::flash('message', 'Berhasil! Tanda Terima berhasil dihapus');
        Session::flash('alert-class', 'alert-success');

        return redirect('deliveryorder');
    }

    public function searchList(Request $request){
        $listDeliveryOrder = DeliveryOrder::searchList($request, $this->userid, $this->branchid);

        return json_encode($listDeliveryOrder)
            ;
    }

    public function searchListInv(Request $request){
        $listDeliveryOrder = DeliveryOrder::searchListInv($request, $this->userid, $this->branchid);

        return json_encode($listDeliveryOrder)
            ;
    }

    public function searchTableList(Request $request){
        $listDeliveryOrder = DeliveryOrder::searchTableList($request, $this->userid, $this->branchid);

        return view('deliveryorder.search-list')
            ->with('listDeliveryOrder', $listDeliveryOrder)
            ;
    }
}
