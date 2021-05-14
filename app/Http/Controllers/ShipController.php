<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use View;
use Session;
use Auth;

use App\Ship;

class ShipController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        View::share ( 'menuName', 'Master' );
        View::share ( 'subMenuName', 'Nama Kapal' );

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
        $dataTableShip = Ship::getDataTableShip();

        return view('ship.index')
            ->with('dataTableShip', $dataTableShip)
            ;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('ship.create');
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
            'name' => 'required',
        ]);

        $ship = Ship::setShip(0, $request, $this->userid, $this->branchid);
        Ship::insertShip($ship);
        Session::flash('message', 'Berhasil! Nama Kapal berhasil ditambah'); 
        Session::flash('alert-class', 'alert-success'); 

        return redirect('ship');
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
        $ship = Ship::getShipByID($id);

        return view('ship.create')
            ->with('ship', $ship)
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
            'name' => 'required',
        ]);

        $ship = Ship::setShip(1, $request, $this->userid, $this->branchid);

        Ship::updateShip($id, $ship);
        Session::flash('message', 'Berhasil! Nama Kapal berhasil diubah'); 
        Session::flash('alert-class', 'alert-success'); 

        return redirect('ship');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(Ship::isUsedShip($id)){
            Session::flash('message', 'Gagal! Nama Kapal Sudah digunakan'); 
            Session::flash('alert-class', 'alert-danger'); 

            return back();
        }

        Ship::deleteShip($id);
        Session::flash('message', 'Berhasil! Nama Kapal berhasil dihapus'); 
        Session::flash('alert-class', 'alert-success'); 

        return redirect('ship');
    }
}
