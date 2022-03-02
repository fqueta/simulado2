<?php

namespace App\Http\Controllers;

use App\Models\Familia;
use App\Models\User;
use App\Models\_upload;
use App\Rules\FullName;
use App\Rules\RightCpf;

use stdClass;
use App\Qlib\Qlib;
use App\Http\Requests\StoreFamilyRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Exports\UsersExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\FamiliasExport;
use App\Exports\FamiliasExportView;


use DataTables;
use Illuminate\Support\Facades\Auth;

class FamiliaController extends Controller
{
    protected $user;
    public function __construct(User $user)
    {
        $this->middleware('auth');
        $this->user = $user;
    }
    public function queryFamilias($get=false,$config=false)
    {
        $ret = false;
        $get = isset($_GET) ? $_GET:[];
        $ano = date('Y');
        $mes = date('m');
        //$todasFamilias = Familia::where('excluido','=','n')->where('deletado','=','n');
        $config = [
            'limit'=>isset($get['limit']) ? $get['limit']: 50,
            'order'=>isset($get['order']) ? $get['order']: 'desc',
        ];

        $familia =  Familia::where('excluido','=','n')->where('deletado','=','n')->orderBy('id',$config['order']);
        //$familia =  DB::table('familias')->where('excluido','=','n')->where('deletado','=','n')->orderBy('id',$config['order']);

        $familia_totais = new stdClass;
        $campos = isset($_SESSION['campos_familias_exibe']) ? $_SESSION['campos_familias_exibe'] : $this->campos();
        $tituloTabela = 'Lista de todos cadastros';
        $arr_titulo = false;
        if(isset($get['filter'])){
                $titulo_tab = false;
                $i = 0;
                foreach ($get['filter'] as $key => $value) {
                    if(!empty($value)){
                        if($key=='id'){
                            $familia->where($key,'LIKE', $value);
                            $titulo_tab .= 'Todos com *'. $campos[$key]['label'] .'% = '.$value.'& ';
                            $arr_titulo[$campos[$key]['label']] = $value;
                        }else{
                            $familia->where($key,'LIKE','%'. $value. '%');
                            if($campos[$key]['type']=='select'){
                                $value = $campos[$key]['arr_opc'][$value];
                            }
                            $arr_titulo[$campos[$key]['label']] = $value;
                            $titulo_tab .= 'Todos com *'. $campos[$key]['label'] .'% = '.$value.'& ';
                        }
                        $i++;
                    }
                }
                if($titulo_tab){
                    $tituloTabela = 'Lista de: &'.$titulo_tab;
                                //$arr_titulo = explode('&',$tituloTabela);
                }
                $fm = $familia;
                if($config['limit']=='todos'){
                    $familia = $familia->get();
                }else{
                    $familia = $familia->paginate($config['limit']);
                }
                $familia_totais->todos = $fm->count();
                $familia_totais->idoso = $fm->where('idoso','=','s')->count();
                $familia_totais->criancas = $fm->where('crianca_adolescente','=','s')->count();
                $familia_totais->esteMes = $fm->whereYear('created_at', '=', $ano)->whereMonth('created_at','=',$mes)->count();

        }else{
            $fm = $familia;
            if($config['limit']=='todos'){
                $familia = $familia->get();
            }else{
                $familia = $familia->paginate($config['limit']);
            }
            $familia_totais->todos = $fm->count();
            $familia_totais->idoso = $fm->where('idoso','=','s')->count();
            $familia_totais->criancas = $fm->where('crianca_adolescente','=','s')->count();
            $familia_totais->esteMes = $fm->whereYear('created_at', '=', $ano)->whereMonth('created_at','=',$mes)->count();
        }
        $ret['familia'] = $familia;
        $ret['familia_totais'] = $familia_totais;
        $ret['arr_titulo'] = $arr_titulo;
        $ret['campos'] = $campos;
        $ret['config'] = $config;
        $ret['tituloTabela'] = $tituloTabela;
        //dd($ret);
        return $ret;
    }
    public function index(User $user)
    {
        $this->authorize('is_admin', $user);
        $title = 'Famílias Cadastradas';
        $titulo = $title;
        $queryFamilias = $this->queryFamilias($_GET);
        $queryFamilias['config']['exibe'] = 'html';

        return view('familias.index',[
            'familias'=>$queryFamilias['familia'],
            'title'=>$title,
            'titulo'=>$titulo,
            'campos_tabela'=>$queryFamilias['campos'],
            'familia_totais'=>$queryFamilias['familia_totais'],
            'titulo_tabela'=>$queryFamilias['tituloTabela'],
            'arr_titulo'=>$queryFamilias['arr_titulo'],
            'config'=>$queryFamilias['config'],
            'i'=>0,
        ]);
    }
    public function exportAll(User $user)
    {
        $this->authorize('is_admin', $user);
        return Excel::download(new FamiliasExport, 'Familias_'.date('d_m_Y').'.xlsx');
    }
    public function exportFilter(User $user)
    {
        $this->authorize('is_admin', $user);
        $dados = new FamiliasExportView;
        //return $dados->view();
        return Excel::download(new FamiliasExportView, 'Familias_'.date('d_m_Y').'.xlsx');
    }
    public function campos(){
        return [
            'id'=>['label'=>'Id','active'=>true,'type'=>'number','exibe_busca'=>'d-block','event'=>''],
            'area_alvo'=>['label'=>'Área Alvo','active'=>true,'type'=>'text','exibe_busca'=>'d-block','event'=>''],
            'loteamento'=>['label'=>'Loteamanto','active'=>true,'type'=>'text','exibe_busca'=>'d-block','event'=>''],
            'matricula'=>['label'=>'Matricula','active'=>true,'type'=>'text','exibe_busca'=>'d-block','event'=>''],
            'quadra'=>['label'=>'Quadra','active'=>true,'type'=>'text','exibe_busca'=>'d-block','event'=>''],
            'lote'=>['label'=>'Lote','active'=>true,'type'=>'text','exibe_busca'=>'d-block','event'=>''],
            'nome_completo'=>['label'=>'Proprietário','active'=>true,'type'=>'text','exibe_busca'=>'d-block','event'=>''],
            'cpf'=>['label'=>'CPF proprietário','active'=>true,'type'=>'text','exibe_busca'=>'d-block','event'=>''],
            'nome_conjuge'=>['label'=>'Nome do Cônjuge','active'=>true,'type'=>'text','exibe_busca'=>'d-block','event'=>''],
            'cpf_conjuge'=>['label'=>'CPF do Cônjuge','active'=>true,'type'=>'text','exibe_busca'=>'d-block','event'=>''],
            'telefone'=>['label'=>'Telefone','active'=>true,'type'=>'text','exibe_busca'=>'d-block','event'=>'onblur=mask(this,clientes_mascaraTelefone); onkeypress=mask(this,clientes_mascaraTelefone);'],
            'escolaridade'=>['label'=>'Escolaridade','active'=>true,'type'=>'select','arr_opc'=>Qlib::sql_array("SELECT id,nome FROM escolaridades",'nome','id'),'exibe_busca'=>'d-block','event'=>''],
            'estado_civil'=>['label'=>'Estado Civil','active'=>true,'type'=>'select','arr_opc'=>Qlib::sql_array("SELECT id,nome FROM estadocivils",'nome','id'),'exibe_busca'=>'d-block','event'=>''],
            'situacao_proficional'=>['label'=>'Situação Proficional','active'=>true,'exibe_busca'=>'d-block','event'=>''],
            'qtd_membros'=>['label'=>'Qtd. Membros','active'=>true,'type'=>'text','exibe_busca'=>'d-block','event'=>''],
            'idoso'=>['label'=>'Idoso','active'=>true,'type'=>'text','exibe_busca'=>'d-block','event'=>''],
            'crianca_adolescente'=>['label'=>'Criança e Adolescente','active'=>true,'exibe_busca'=>'d-block','event'=>''],
            'bcp_bolsa_familia'=>['label'=>'BPC ou Bolsa Família','active'=>true,'type'=>'text','exibe_busca'=>'d-block','event'=>''],
            'renda_familiar'=>['label'=>'Renda Familias','active'=>true,'type'=>'text','exibe_busca'=>'d-block','event'=>''],
            'doc_imovel'=>['label'=>'Doc Imóvel','active'=>true,'type'=>'text','exibe_busca'=>'d-block','event'=>''],
            'obs'=>['label'=>'Observação','active'=>true,'type'=>'text','exibe_busca'=>'d-block','event'=>''],
        ];
    }
    public function create(User $user)
    {
        $this->authorize('is_admin', $user);
        $title = 'Cadastrar família';
        $titulo = $title;
        //$Users = Users::all();
        $arr_user = ['ac'=>'cad'];
        //$roles = DB::select("SELECT * FROM roles ORDER BY id ASC");
        $familia = ['ac'=>'cad','token'=>uniqid()];
        $arr_escolaridade = Qlib::sql_array("SELECT id,nome FROM escolaridades ORDER BY nome ", 'nome', 'id');
        $arr_estadocivil = Qlib::sql_array("SELECT id,nome FROM estadocivils ORDER BY nome ", 'nome', 'id');
        return view('familias.createedit',[
            'familia'=>$familia,
            'title'=>$title,
            'titulo'=>$titulo,
            'arr_escolaridade'=>$arr_escolaridade,
            'arr_estadocivil'=>$arr_estadocivil,
        ]);
    }

    public function store(StoreFamilyRequest $request)
    {
        //$validated = $request->validated();
        $dados = $request->all();
        $ajax = isset($dados['ajax'])?$dados['ajax']:'n';
        if (isset($dados['image']) && $dados['image']->isValid()){
            $nameFile = Str::of($dados['name'])->slug('-').'.'.$dados['image']->getClientOriginalExtension();
            $image = $dados['image']->storeAs('users',$nameFile);
            $dados['image'] = $image;
        }
        $userLogadon = Auth::id();
        if(isset($dados['config'])){
            $dados['config'] = Qlib::lib_array_json($dados['config']);
        }
        $dados['idoso'] = isset($dados['idoso'])?$dados['idoso']:'n';
        $dados['crianca_adolescente'] = isset($dados['crianca_adolescente'])?:'n';
        $dados['autor'] = $userLogadon;
        $dados['token'] = uniqid();
        $renda_familiar = str_replace('R$','',$dados['renda_familiar']);
        $dados['renda_familiar'] = Qlib::precoBanco($renda_familiar);

        $salvar = Familia::create($dados);
        $route = 'familias.index';
        $ret = [
            'mens'=>'Salvo com sucesso!',
            'color'=>'success',
            'idCad'=>$salvar->id,
        ];

        if($ajax=='s'){
            $ret['return'] = route($route).'?idCad='.$salvar->id;
            return response()->json($ret);
        }else{
            return redirect()->route($route,$ret);
        }
    }

    public function show(Familia $familia)
    {
        //
    }

    public function edit($id,User $user)
    {
        $dados = Familia::where('id',$id)->get();
        //$roles = DB::select("SELECT * FROM roles ORDER BY id ASC");
        //$permissions = DB::select("SELECT * FROM permissions ORDER BY id ASC");
        $this->authorize('is_admin', $user);

        if(!empty($dados)){
            $title = 'Editar Cadastro de família';
            $titulo = $title;
            $dados[0]['ac'] = 'alt';
            if(isset($dados[0]['config'])){
                $dados[0]['config'] = Qlib::lib_json_array($dados[0]['config']);
            }
            $arr_escolaridade = Qlib::sql_array("SELECT id,nome FROM escolaridades ORDER BY nome ", 'nome', 'id');
            $arr_estadocivil = Qlib::sql_array("SELECT id,nome FROM estadocivils ORDER BY nome ", 'nome', 'id');
            $listFiles = false;
            if(isset($dados[0]['token'])){
                $listFiles = _upload::where('token_produto','=',$dados[0]['token'])->get();
            }
            $ret = [
                'familia'=>$dados[0],
                'title'=>$title,
                'titulo'=>$titulo,
                'arr_escolaridade'=>$arr_escolaridade,
                'arr_estadocivil'=>$arr_estadocivil,
                'listFiles'=>$listFiles,
                'exec'=>true,
            ];

            return view('familias.createedit',$ret);
        }else{
            $ret = [
                'exec'=>false,
            ];
            return redirect()->route('familias.index',$ret);
        }
    }
    public function update(StoreFamilyRequest $request, $id)
    {
        $data = [];
        $dados = $request->all();
        $ajax = isset($dados['ajax'])?$dados['ajax']:'n';
        foreach ($dados as $key => $value) {
            if($key!='_method'&&$key!='_token'&&$key!='ac'&&$key!='ajax'){
                if($key=='data_batismo' || $key=='data_nasci'){
                    if($value=='0000-00-00' || $value=='00/00/0000'){
                    }else{
                        $data[$key] = Qlib::dtBanco($value);
                    }
                }elseif($key == 'renda_familiar') {
                    $value = str_replace('R$','',$value);
                    $data[$key] = Qlib::precoBanco($value);
                    //$data[$key] = number_format($value,2,'.','');
                }else{
                    $data[$key] = $value;
                }
            }
        }
        $userLogadon = Auth::id();
        $data['idoso'] = isset($data['idoso'])?$data['idoso']:'n';
        $data['crianca_adolescente'] = isset($data['crianca_adolescente'])?:'n';
        $data['config']['atualizado_por'] = $userLogadon;
        if(isset($dados['config'])){
            $dados['config'] = Qlib::lib_array_json($dados['config']);
        }
        $atualizar=false;
        if(!empty($data)){
            $atualizar=Familia::where('id',$id)->update($data);
            $route = 'familias.index';
            $ret = [
                'exec'=>true,
                'id'=>$id,
                'mens'=>'Salvo com sucesso!',
                'color'=>'success',
                'idCad'=>$id,
                'return'=>$route,
            ];
        }else{
            $route = 'familias.edit';
            $ret = [
                'exec'=>false,
                'id'=>$id,
                'mens'=>'Erro ao receber dados',
                'color'=>'danger',
            ];
        }
        if($ajax=='s'){
            $ret['return'] = route($route).'?idCad='.$id;
            return response()->json($ret);
        }else{
            return redirect()->route($route,$ret);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Familia  $familia
     * @return \Illuminate\Http\Response
     */
    public function destroy($id,Request $request)
    {
        $config = $request->all();
        $ajax =  isset($config['ajax'])?$config['ajax']:'n';
        if (!$post = Familia::find($id)){
            if($ajax=='s'){
                $ret = response()->json(['mens'=>'Registro não encontrado!','color'=>'danger','return'=>route('familias.index')]);
            }else{
                $ret = redirect()->route('familias.index',['mens'=>'Registro não encontrado!','color'=>'danger']);
            }
            return $ret;
        }

        Familia::where('id',$id)->delete();
        if($ajax=='s'){
            $ret = response()->json(['mens'=>__('Registro '.$id.' deletado com sucesso!'),'color'=>'success','return'=>route('familias.index')]);
        }else{
            $ret = redirect()->route('familias.index',['mens'=>'Registro deletado com sucesso!','color'=>'success']);
        }
        return $ret;
    }
}
