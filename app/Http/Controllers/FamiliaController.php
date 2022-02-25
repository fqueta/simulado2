<?php

namespace App\Http\Controllers;

use App\Models\Familia;
use App\Models\User;
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
                $familia_totais->todos = $familia->get()->count();
                $familia_totais->esteMes = $familia->whereYear('created_at', '=', $ano)->whereMonth('created_at','=',$mes)->count();
                $familia_totais->idoso = $familia->where('idoso','=','s')->count();
                $familia_totais->criancas = $familia->where('crianca_adolescente','=','s')->count();
                if($config['limit']=='todos'){
                    $familia = $familia->get();
                }else{
                    $familia = $familia->paginate($config['limit']);
                }
        }else{
               $familia_totais->todos = $familia->get()->count();
               $familia_totais->esteMes = $familia->whereYear('created_at', '=', $ano)->whereMonth('created_at','=',$mes)->count();
                $familia_totais->idoso = $familia->where('idoso','=','s')->count();
                $familia_totais->criancas = $familia->where('crianca_adolescente','=','s')->count();
                if($config['limit']=='todos'){
                    $familia = $familia->get();
                }else{
                    $familia = $familia->paginate($config['limit']);
                }
        }
        $ret['familia'] = $familia;
        $ret['familia_totais'] = $familia_totais;
        $ret['arr_titulo'] = $arr_titulo;
        $ret['campos'] = $campos;
        $ret['config'] = $config;
        $ret['tituloTabela'] = $tituloTabela;
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
        $familia = ['ac'=>'cad'];
        $arr_escolaridade = $dados = Qlib::sql_array("SELECT id,nome FROM escolaridades ORDER BY nome ", 'nome', 'id');
        $arr_estadocivil = $dados = Qlib::sql_array("SELECT id,nome FROM estadocivils ORDER BY nome ", 'nome', 'id');
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
        //dd($dados);
        $salvar = Familia::create($dados);
        return redirect()->route('familias.index',['mens'=>'Salvo com sucesso!','color'=>'success']);
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
            return view('familias.createedit',[
                'familia'=>$dados[0],
                'title'=>$title,
                'titulo'=>$titulo,
                'arr_escolaridade'=>$arr_escolaridade,
                'arr_estadocivil'=>$arr_estadocivil,
            ]);
        }else{
          return redirect()->route('familias.index');
        }
    }
    public function update(StoreFamilyRequest $request, $id)
    {
        $data = [];
        $dados = $request->all();
        //dd($dados);
        foreach ($dados as $key => $value) {
            if($key!='_method'&&$key!='_token'&&$key!='ac'){
                if($key=='data_batismo' || $key=='data_nasci'){
                    if($value=='0000-00-00' || $value=='00/00/0000'){
                    }else{
                        $data[$key] = Qlib::dtBanco($value);
                    }
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
            return redirect()->route('familias.index',['id'=>$id,'mens'=>'Salvo com sucesso!','color'=>'success']);
        }else{
            return redirect()->route('familias.edit',['id'=>$id,'mens'=>'Erro ao receber dados','color'=>'danger']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Familia  $familia
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!$post = Familia::find($id))
            return redirect()->route('familias.index',['mens'=>'Registro não encontrado!','color'=>'danger']);

        //if (Storage::exists($post->image))
            //Storage::delete($post->image);

        Familia::where('id',$id)->delete();
            return redirect()->route('familias.index',['mens'=>'Registro deletado com sucesso!','color'=>'success']);
    }
}
