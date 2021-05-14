<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use View;
use Session;
use Auth;

use App\Bank;

class BankController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        View::share ( 'menuName', 'Master' );
        View::share ( 'subMenuName', 'Nama Bank' );

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
        $dataTableBank = Bank::getDataTableBank();

        return view('bank.index')
            ->with('dataTableBank', $dataTableBank)
            ;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('bank.create');
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

        $bank = Bank::setBank(0, $request, $this->userid, $this->branchid);
        Bank::insertBank($bank);
        Session::flash('message', 'Berhasil! Nama Bank berhasil ditambah'); 
        Session::flash('alert-class', 'alert-success'); 

        return redirect('bank');
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
        $bank = Bank::getBankByID($id);

        return view('bank.create')
            ->with('bank', $bank)
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

        $bank = Bank::setBank(1, $request, $this->userid, $this->branchid);

        Bank::updateBank($id, $bank);
        Session::flash('message', 'Berhasil! Nama Bank berhasil diubah'); 
        Session::flash('alert-class', 'alert-success'); 

        return redirect('bank');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(Bank::isUsedBank($id)){
            Session::flash('message', 'Gagal! Nama Bank Sudah digunakan'); 
            Session::flash('alert-class', 'alert-danger'); 

            return back();
        }

        Bank::deleteBank($id);
        Session::flash('message', 'Berhasil! Nama Bank berhasil dihapus'); 
        Session::flash('alert-class', 'alert-success'); 

        return redirect('bank');
    }
}
