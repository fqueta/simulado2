<?php

namespace App\Http\Controllers;

use App\Models\Familia;
use App\Qlib\Qlib;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TesteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //Auth::logout();
        //echo __FILE__;
       // $dados = Auth::check();
        //$dados = Qlib::sql_array("SELECT id,nome FROM escolaridades ORDER BY nome ", 'nome', 'id');
        //$dados = DB::select("SELECT * FROM familias WHERE excluido='n' AND deletado='n' ORDER BY id DESC");
        //$dados = Familia::where('excluido','=','n')->where('deletado','=','n')->OrderBy('id','desc')->get();
        //$dados = DB::table('familias')->where('excluido','=','n')->where('deletado','=','n')->OrderBy('id','desc')->get();
        return view('teste');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        //
    }
}
