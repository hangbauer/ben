<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use View;
use Session;
use Auth;

use App\Sender;

class SenderController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        View::share ( 'menuName', 'Master' );
        View::share ( 'subMenuName', 'Pengirim' );

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
        $dataTableSender = Sender::getDataTableSender();

        return view('sender.index')
            ->with('dataTableSender', $dataTableSender)
            ;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $dropDownTitle = Sender::getDropDownTitle();

        return view('sender.create')
            ->with('dropDownTitle', $dropDownTitle)
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
            'name' => 'required',
        ]);

        $sender = Sender::setSender(0, $request, $this->userid, $this->branchid);
        Sender::insertSender($sender);
        Session::flash('message', 'Berhasil! Pengirim berhasil ditambah');
        Session::flash('alert-class', 'alert-success');

        return redirect('sender');
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
        $sender = Sender::getSenderByID($id);
        $dropDownTitle = Sender::getDropDownTitle();

        return view('sender.create')
            ->with('sender', $sender)
            ->with('dropDownTitle', $dropDownTitle)
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

        $sender = Sender::setSender(1, $request, $this->userid, $this->branchid);

        Sender::updateSender($id, $sender);
        Session::flash('message', 'Berhasil! Pengirim berhasil diubah');
        Session::flash('alert-class', 'alert-success');

        return redirect('sender');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(Sender::isUsedSender($id)){
            Session::flash('message', 'Gagal! Nama Pengirim Sudah digunakan');
            Session::flash('alert-class', 'alert-danger');

            return back();
        }

        Sender::deleteSender($id);
        Session::flash('message', 'Berhasil! Nama Pengirim berhasil dihapus');
        Session::flash('alert-class', 'alert-success');

        return redirect('sender');
    }
}
