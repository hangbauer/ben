<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use View;
use Session;
use Auth;

use App\Recipient;

class RecipientController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        View::share ( 'menuName', 'Master' );
        View::share ( 'subMenuName', 'Penerima' );

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
        $dataTableRecipient = Recipient::getDataTableRecipient();

        return view('recipient.index')
            ->with('dataTableRecipient', $dataTableRecipient)
            ;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $dropDownTitle = Recipient::getDropDownTitle();

        return view('recipient.create')
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

        $recipient = Recipient::setRecipient(0, $request, $this->userid, $this->branchid);
        Recipient::insertRecipient($recipient);
        Session::flash('message', 'Berhasil! Nama Penerima berhasil ditambah');
        Session::flash('alert-class', 'alert-success');

        return redirect('recipient');
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
        $recipient = Recipient::getRecipientByID($id);
        $dropDownTitle = Recipient::getDropDownTitle();

        return view('recipient.create')
            ->with('recipient', $recipient)
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

        $recipient = Recipient::setRecipient(1, $request, $this->userid, $this->branchid);

        Recipient::updateRecipient($id, $recipient);
        Session::flash('message', 'Berhasil! Nama Penerima berhasil diubah');
        Session::flash('alert-class', 'alert-success');

        return redirect('recipient');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(Recipient::isUsedRecipient($id)){
            Session::flash('message', 'Gagal! Nama Penerima Sudah digunakan');
            Session::flash('alert-class', 'alert-danger');

            return back();
        }

        Recipient::deleteRecipient($id);
        Session::flash('message', 'Berhasil! Nama Penerima berhasil dihapus');
        Session::flash('alert-class', 'alert-success');

        return redirect('recipient');
    }
}
