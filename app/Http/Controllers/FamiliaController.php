<?php

namespace App\Http\Controllers;

use App\Models\Familia;
use App\Models\User;
use stdClass;
use App\Qlib\Qlib;
use App\Http\Requests\StorePostRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use DataTables;

class FamiliaController extends Controller
{
    protected $user;
    public function __construct(User $user)
    {
        $this->middleware('auth');
        $this->user = $user;
    }

    public function index(User $user)
    {
        $this->authorize('is_admin', $user);
        $title = 'Famílias Cadastradas';
        $titulo = $title;
        $familia = Familia::where('excluido','=','n')->where('deletado','=','n')->paginate(50);
        return view('familias.index',['familias'=>$familia,'title'=>$title,'titulo'=>$titulo]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(User $user)
    {
        $this->authorize('is_admin', $user);
        $title = 'Cadastrar família';
        $titulo = $title;
        //$Users = Users::all();
        $arr_user = ['ac'=>'cad'];
        $roles = DB::select("SELECT * FROM roles ORDER BY id ASC");
        $familia = ['ac'=>'cad'];
        return view('familias.createedit',['familia'=>$familia,'roles'=>$roles,'title'=>$title,'titulo'=>$titulo]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePostRequest $request)
    {
        $validated = $request->validated();
        //$validatedData = $request->validate([
            //'nome_completo' => ['required','string'],
            //'email' => ['required', 'string', 'email', 'max:255', 'unique:users']
        //]);
        $dados = $request->all();
        //$dados['password'] = Hash::make($dados['password']);
        //$permission = isset($dados['permission'])?$dados['permission']:'user';
        if (isset($dados['image']) && $dados['image']->isValid()){
            $nameFile = Str::of($dados['name'])->slug('-').'.'.$dados['image']->getClientOriginalExtension();
            $image = $dados['image']->storeAs('users',$nameFile);
            $dados['image'] = $image;
        }
        $salvar = Familia::create($dados);
        //dd($salvar);
        return redirect()->route('familias.index')->with('message','Cadastro realizado com sucesso');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Familia  $familia
     * @return \Illuminate\Http\Response
     */
    public function show(Familia $familia)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Familia  $familia
     * @return \Illuminate\Http\Response
     */
    public function edit(Familia $familia)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Familia  $familia
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Familia $familia)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Familia  $familia
     * @return \Illuminate\Http\Response
     */
    public function destroy(Familia $familia)
    {
        //
    }
}
