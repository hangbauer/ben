<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use View;
use Session;
use Auth;

use App\ShipSchedule;
use App\Ship;

class ShipScheduleController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        View::share ( 'menuName', 'Master' );
        View::share ( 'subMenuName', 'Jadwal Kapal' );

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
        $dropDownShip = Ship::getDropDownShip();
        
        return view('shipschedule.index')
            ->with('dropDownShip', $dropDownShip)
            ;        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $dropDownShip = Ship::getDropDownShip();

        return view('shipschedule.create')
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
            'shipid' => 'required',
            'destination' => 'required',
        ]);

        $gab = ShipSchedule::setShipSchedule(0, $request, $this->userid, $this->branchid);
        ShipSchedule::insertShipSchedule($gab);
        Session::flash('message', 'Berhasil! Jadwal Kapal berhasil ditambah'); 
        Session::flash('alert-class', 'alert-success'); 

        return redirect('shipschedule');
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
        $shipSchedule = ShipSchedule::getShipScheduleByID($id);

        $dropDownShip = Ship::getDropDownShip();

        $doMas = ShipSchedule::getDoMasByShipScheduleID($id);

        return view('shipschedule.create')
            ->with('shipSchedule', $shipSchedule)
            ->with('dropDownShip', $dropDownShip)
            ->with('doMas', $doMas)
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
            'shipid' => 'required',
            'destination' => 'required',
        ]);

        $gab = ShipSchedule::setShipSchedule(1, $request, $this->userid, $this->branchid);

        ShipSchedule::updateShipSchedule($id, $gab);
        Session::flash('message', 'Berhasil! Jadwal Kapal berhasil diubah'); 
        Session::flash('alert-class', 'alert-success'); 

        return redirect('shipschedule');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(ShipSchedule::isUsedShipSchedule($id)){
            Session::flash('message', 'Gagal! Jadwal Kapal Sudah digunakan'); 
            Session::flash('alert-class', 'alert-danger'); 

            return back();
        }

        ShipSchedule::deleteShipSchedule($id);
        Session::flash('message', 'Berhasil! Jadwal Kapal berhasil dihapus'); 
        Session::flash('alert-class', 'alert-success'); 

        return redirect('shipschedule');
    }

    public function searchTableList(Request $request){                
        $listShipSchedule = ShipSchedule::searchTableList($request, $this->userid, $this->branchid);

        return view('shipschedule.search-list')
            ->with('listShipSchedule', $listShipSchedule)
            ;
    }
}
