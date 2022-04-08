<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use stdClass;
use App\Models\questoe;
use App\Qlib\Qlib;
use App\Models\User;
use App\Models\_upload;
use Illuminate\Support\Facades\Auth;

class QuestoesController extends Controller
{
    protected $user;
    public $routa;
    public $label;
    public $view;
    public function __construct(User $user)
    {
        $this->middleware('auth');
        $this->user = $user;
        $this->routa = 'questoes';
        $this->label = 'Questoe';
        $this->view = 'admin.padrao';
    }
    public function queryQuestoe($get=false,$config=false)
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

        $questoe =  Questoe::where('excluido','=','n')->where('deletado','=','n')->orderBy('id',$config['order']);
        //$questoe =  DB::table('questoes')->where('excluido','=','n')->where('deletado','=','n')->orderBy('id',$config['order']);

        $questoe_totais = new stdClass;
        $campos = isset($_SESSION['campos_questoes_exibe']) ? $_SESSION['campos_questoes_exibe'] : $this->campos();
        $tituloTabela = 'Lista de todos cadastros';
        $arr_titulo = false;
        if(isset($get['filter'])){
                $titulo_tab = false;
                $i = 0;
                foreach ($get['filter'] as $key => $value) {
                    if(!empty($value)){
                        if($key=='id'){
                            $questoe->where($key,'LIKE', $value);
                            $titulo_tab .= 'Todos com *'. $campos[$key]['label'] .'% = '.$value.'& ';
                            $arr_titulo[$campos[$key]['label']] = $value;
                        }else{
                            $questoe->where($key,'LIKE','%'. $value. '%');
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
                $fm = $questoe;
                if($config['limit']=='todos'){
                    $questoe = $questoe->get();
                }else{
                    $questoe = $questoe->paginate($config['limit']);
                }
        }else{
            $fm = $questoe;
            if($config['limit']=='todos'){
                $questoe = $questoe->get();
            }else{
                $questoe = $questoe->paginate($config['limit']);
            }
        }
        $questoe_totais->todos = $fm->count();
        $questoe_totais->esteMes = $fm->whereYear('created_at', '=', $ano)->whereMonth('created_at','=',$mes)->get()->count();
        $questoe_totais->ativos = $fm->where('ativo','=','s')->get()->count();
        $questoe_totais->inativos = $fm->where('ativo','=','n')->get()->count();

        $ret['questoe'] = $questoe;
        $ret['questoe_totais'] = $questoe_totais;
        $ret['arr_titulo'] = $arr_titulo;
        $ret['campos'] = $campos;
        $ret['config'] = $config;
        $ret['tituloTabela'] = $tituloTabela;
        $ret['config']['resumo'] = [
            'todos_registro'=>['label'=>'Todos cadastros','value'=>$questoe_totais->todos,'icon'=>'fas fa-calendar'],
            'todos_mes'=>['label'=>'Cadastros recentes','value'=>$questoe_totais->esteMes,'icon'=>'fas fa-calendar-times'],
            'todos_ativos'=>['label'=>'Cadastros ativos','value'=>$questoe_totais->ativos,'icon'=>'fas fa-check'],
            'todos_inativos'=>['label'=>'Cadastros inativos','value'=>$questoe_totais->inativos,'icon'=>'fas fa-archive'],
        ];
        return $ret;
    }
    public function campos(){

        return [
            'id'=>['label'=>'Id','active'=>true,'type'=>'hidden','exibe_busca'=>'d-block','event'=>'','tam'=>'2'],
            'token'=>['label'=>'token','active'=>false,'type'=>'hidden','exibe_busca'=>'d-block','event'=>'','tam'=>'2'],
            'nome'=>['label'=>'Nome da Questão','active'=>true,'placeholder'=>'Ex.: Cadastrado','type'=>'text','exibe_busca'=>'d-block','event'=>'','tam'=>'12'],
            'ativo'=>['label'=>'Ativar','active'=>true,'type'=>'chave_checkbox','value'=>'s','valor_padrao'=>'s','exibe_busca'=>'d-block','event'=>'','tam'=>'3','arr_opc'=>['s'=>'Sim','n'=>'Não']],
            'obs'=>['label'=>'Observação','active'=>false,'type'=>'textarea','exibe_busca'=>'d-block','event'=>'','tam'=>'12'],
        ];
    }
    public function index(User $user)
    {
        $this->authorize('ler', $this->routa);
        $title = 'Questoes Cadastradas';
        $titulo = $title;
        $queryQuestoe = $this->queryQuestoe($_GET);
        $queryQuestoe['config']['exibe'] = 'html';
        $routa = $this->routa;
        return view($this->view.'.index',[
            'dados'=>$queryQuestoe['questoe'],
            'title'=>$title,
            'titulo'=>$titulo,
            'campos_tabela'=>$queryQuestoe['campos'],
            'questoe_totais'=>$queryQuestoe['questoe_totais'],
            'titulo_tabela'=>$queryQuestoe['tituloTabela'],
            'arr_titulo'=>$queryQuestoe['arr_titulo'],
            'config'=>$queryQuestoe['config'],
            'routa'=>$routa,
            'view'=>$this->view,
            'i'=>0,
        ]);
    }
    public function create(User $user)
    {
        $this->authorize('create', $this->routa);
        $title = 'Cadastrar questão';
        $titulo = $title;
        $config = [
            'ac'=>'cad',
            'frm_id'=>'frm-questoes',
            'route'=>$this->routa,
        ];
        $value = [
            'token'=>uniqid(),
        ];
        $campos = $this->campos();
        return view($this->view.'.createedit',[
            'config'=>$config,
            'title'=>$title,
            'titulo'=>$titulo,
            'campos'=>$campos,
            'value'=>$value,
        ]);
    }
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nome' => ['required','string','unique:questoes'],
        ]);
        $dados = $request->all();
        $ajax = isset($dados['ajax'])?$dados['ajax']:'n';
        $dados['ativo'] = isset($dados['ativo'])?$dados['ativo']:'n';

        //dd($dados);
        $salvar = Questoe::create($dados);
        $route = $this->routa.'.index';
        $ret = [
            'mens'=>$this->label.' cadastrada com sucesso!',
            'color'=>'success',
            'idCad'=>$salvar->id,
            'exec'=>true,
            'dados'=>$dados
        ];

        if($ajax=='s'){
            $ret['return'] = route($route).'?idCad='.$salvar->id;
            $ret['redirect'] = route($this->routa.'.edit',['id'=>$salvar->id]);
            return response()->json($ret);
        }else{
            return redirect()->route($route,$ret);
        }
    }

    public function show($id)
    {
        //
    }

    public function edit($questoe,User $user)
    {
        $id = $questoe;
        $dados = Questoe::where('id',$id)->get();
        $routa = 'questoes';
        $this->authorize('ler', $this->routa);

        if(!empty($dados)){
            $title = 'Editar cadastro de questão';
            $titulo = $title;
            $dados[0]['ac'] = 'alt';
            if(isset($dados[0]['config'])){
                $dados[0]['config'] = Qlib::lib_json_array($dados[0]['config']);
            }
            $listFiles = false;
            $campos = $this->campos();
            if(isset($dados[0]['token'])){
                $listFiles = _upload::where('token_produto','=',$dados[0]['token'])->get();
            }
            $config = [
                'ac'=>'alt',
                'frm_id'=>'frm-questoes',
                'route'=>$this->routa,
                'id'=>$id,
            ];

            $ret = [
                'value'=>$dados[0],
                'config'=>$config,
                'title'=>$title,
                'titulo'=>$titulo,
                'listFiles'=>$listFiles,
                'campos'=>$campos,
                'exec'=>true,
            ];

            return view($this->view.'.createedit',$ret);
        }else{
            $ret = [
                'exec'=>false,
            ];
            return redirect()->route($this->view.'.index',$ret);
        }
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'nome' => ['required'],
        ]);
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
                }elseif($key == 'valor') {
                    $value = str_replace('R$','',$value);
                    $data[$key] = Qlib::precoBanco($value);
                }else{
                    $data[$key] = $value;
                }
            }
        }
        $userLogadon = Auth::id();
        $data['ativo'] = isset($data['ativo'])?$data['ativo']:'n';
        $data['autor'] = $userLogadon;
        if(isset($dados['config'])){
            $dados['config'] = Qlib::lib_array_json($dados['config']);
        }
        $atualizar=false;
        if(!empty($data)){
            $atualizar=Questoe::where('id',$id)->update($data);
            $route = $this->routa.'.index';
            $ret = [
                'exec'=>$atualizar,
                'id'=>$id,
                'mens'=>'Salvo com sucesso!',
                'color'=>'success',
                'idCad'=>$id,
                'return'=>$route,
            ];
        }else{
            $route = $this->routa.'.edit';
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

    public function destroy($id,Request $request)
    {
        $this->authorize('delete', $this->routa);
        $config = $request->all();
        $ajax =  isset($config['ajax'])?$config['ajax']:'n';
        $routa = 'questoes';
        if (!$post = Questoe::find($id)){
            if($ajax=='s'){
                $ret = response()->json(['mens'=>'Registro não encontrado!','color'=>'danger','return'=>route($this->view.'.index')]);
            }else{
                $ret = redirect()->route($this->view.'.index',['mens'=>'Registro não encontrado!','color'=>'danger']);
            }
            return $ret;
        }

        Questoe::where('id',$id)->delete();
        if($ajax=='s'){
            $ret = response()->json(['mens'=>__('Registro '.$id.' deletado com sucesso!'),'color'=>'success','return'=>route($this->routa.'.index')]);
        }else{
            $ret = redirect()->route($routa.'.index',['mens'=>'Registro deletado com sucesso!','color'=>'success']);
        }
        return $ret;
    }
}
