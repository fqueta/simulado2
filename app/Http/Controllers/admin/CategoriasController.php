<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use stdClass;
use App\Models\categoria;
use App\Qlib\Qlib;
use App\Models\User;
use App\Models\_upload;
use Illuminate\Support\Facades\Auth;

class CategoriasController extends Controller
{
    protected $user;
    public $routa;
    public $label;
    public $view;
    public function __construct(User $user)
    {
        $this->middleware('auth');
        $this->user = $user;
        $this->routa = 'categorias';
        $this->label = 'Categoria';
        $this->view = 'admin.padrao';
    }
    public function queryCategoria($get=false,$config=false)
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

        $categoria =  Categoria::where('excluido','=','n')->where('deletado','=','n')->orderBy('id',$config['order']);
        //$categoria =  DB::table('categorias')->where('excluido','=','n')->where('deletado','=','n')->orderBy('id',$config['order']);

        $categoria_totais = new stdClass;
        $campos = isset($_SESSION['campos_categorias_exibe']) ? $_SESSION['campos_categorias_exibe'] : $this->campos();
        $tituloTabela = 'Lista de todos cadastros';
        $arr_titulo = false;
        if(isset($get['filter'])){
                $titulo_tab = false;
                $i = 0;
                foreach ($get['filter'] as $key => $value) {
                    if(!empty($value)){
                        if($key=='id'){
                            $categoria->where($key,'LIKE', $value);
                            $titulo_tab .= 'Todos com *'. $campos[$key]['label'] .'% = '.$value.'& ';
                            $arr_titulo[$campos[$key]['label']] = $value;
                        }else{
                            $categoria->where($key,'LIKE','%'. $value. '%');
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
                $fm = $categoria;
                if($config['limit']=='todos'){
                    $categoria = $categoria->get();
                }else{
                    $categoria = $categoria->paginate($config['limit']);
                }
        }else{
            $fm = $categoria;
            if($config['limit']=='todos'){
                $categoria = $categoria->get();
            }else{
                $categoria = $categoria->paginate($config['limit']);
            }
        }
        $categoria_totais->todos = $fm->count();
        $categoria_totais->esteMes = $fm->whereYear('created_at', '=', $ano)->whereMonth('created_at','=',$mes)->get()->count();
        $categoria_totais->ativos = $fm->where('ativo','=','s')->get()->count();
        $categoria_totais->inativos = $fm->where('ativo','=','n')->get()->count();

        $ret['categoria'] = $categoria;
        $ret['categoria_totais'] = $categoria_totais;
        $ret['arr_titulo'] = $arr_titulo;
        $ret['campos'] = $campos;
        $ret['config'] = $config;
        $ret['tituloTabela'] = $tituloTabela;
        $ret['config']['resumo'] = [
            'todos_registro'=>['label'=>'Todos cadastros','value'=>$categoria_totais->todos,'icon'=>'fas fa-calendar'],
            'todos_mes'=>['label'=>'Cadastros recentes','value'=>$categoria_totais->esteMes,'icon'=>'fas fa-calendar-times'],
            'todos_ativos'=>['label'=>'Cadastros ativos','value'=>$categoria_totais->ativos,'icon'=>'fas fa-check'],
            'todos_inativos'=>['label'=>'Cadastros inativos','value'=>$categoria_totais->inativos,'icon'=>'fas fa-archive'],
        ];
        return $ret;
    }
    public function campos(){

        return [
            'id'=>['label'=>'Id','active'=>true,'type'=>'hidden','exibe_busca'=>'d-block','event'=>'','tam'=>'2'],
            'token'=>['label'=>'token','active'=>false,'type'=>'hidden','exibe_busca'=>'d-block','event'=>'','tam'=>'2'],
            'nome'=>['label'=>'Nome da Categoria','active'=>true,'placeholder'=>'Ex.: Cadastrado','type'=>'text','exibe_busca'=>'d-block','event'=>'','tam'=>'12'],
            'ativo'=>['label'=>'Ativar','active'=>true,'type'=>'chave_checkbox','value'=>'s','valor_padrao'=>'s','exibe_busca'=>'d-block','event'=>'','tam'=>'3','arr_opc'=>['s'=>'Sim','n'=>'Não']],
            'obs'=>['label'=>'Observação','active'=>false,'type'=>'textarea','exibe_busca'=>'d-block','event'=>'','tam'=>'12'],
        ];
    }
    public function index(User $user)
    {
        $this->authorize('ler', $this->routa);
        $title = 'Categorias Cadastradas';
        $titulo = $title;
        $queryCategoria = $this->queryCategoria($_GET);
        $queryCategoria['config']['exibe'] = 'html';
        $routa = $this->routa;
        return view($this->view.'.index',[
            'dados'=>$queryCategoria['categoria'],
            'title'=>$title,
            'titulo'=>$titulo,
            'campos_tabela'=>$queryCategoria['campos'],
            'categoria_totais'=>$queryCategoria['categoria_totais'],
            'titulo_tabela'=>$queryCategoria['tituloTabela'],
            'arr_titulo'=>$queryCategoria['arr_titulo'],
            'config'=>$queryCategoria['config'],
            'routa'=>$routa,
            'view'=>$this->view,
            'i'=>0,
        ]);
    }
    public function create(User $user)
    {
        $this->authorize('create', $this->routa);
        $title = 'Cadastrar categoria';
        $titulo = $title;
        $config = [
            'ac'=>'cad',
            'frm_id'=>'frm-categorias',
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
            'nome' => ['required','string','unique:categorias'],
        ]);
        $dados = $request->all();
        $ajax = isset($dados['ajax'])?$dados['ajax']:'n';
        $dados['ativo'] = isset($dados['ativo'])?$dados['ativo']:'n';

        //dd($dados);
        $salvar = Categoria::create($dados);
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

    public function edit($categoria,User $user)
    {
        $id = $categoria;
        $dados = Categoria::where('id',$id)->get();
        $routa = 'categorias';
        $this->authorize('ler', $this->routa);

        if(!empty($dados)){
            $title = 'Editar Cadastro de categorias';
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
                'frm_id'=>'frm-categorias',
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
                }elseif($key == 'renda_familiar') {
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
            $atualizar=Categoria::where('id',$id)->update($data);
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
        $routa = 'categorias';
        if (!$post = Categoria::find($id)){
            if($ajax=='s'){
                $ret = response()->json(['mens'=>'Registro não encontrado!','color'=>'danger','return'=>route($this->view.'.index')]);
            }else{
                $ret = redirect()->route($this->view.'.index',['mens'=>'Registro não encontrado!','color'=>'danger']);
            }
            return $ret;
        }

        Categoria::where('id',$id)->delete();
        if($ajax=='s'){
            $ret = response()->json(['mens'=>__('Registro '.$id.' deletado com sucesso!'),'color'=>'success','return'=>route($this->routa.'.index')]);
        }else{
            $ret = redirect()->route($routa.'.index',['mens'=>'Registro deletado com sucesso!','color'=>'success']);
        }
        return $ret;
    }
}
