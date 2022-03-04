<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Assistencia;
use stdClass;
use App\Qlib\Qlib;
use App\Http\Requests\StorePostRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class AssistenciaController extends Controller
{
    protected $user;
    public function __construct(User $user)
    {
        $this->middleware('auth');
        $this->user = $user;
    }
    public function dadosAssistencia($id=false){
      $ret = [];
      if($id){
          $dados = [];
          $arr = explode('_',$id);
          if(isset($arr[1])){
              $meses = Qlib::Meses();
              $semanas = [1=>'1.ª Semana',2=>'2.ª Semana',3=>'3.ª Semana',4=>'3.ª Semana',5=>'5.ª Semana',6=>'Total',7=>'Media'];
              $arr_reuniao = [
                ['label'=>'Reunão do meio da semana','id'=>1],
                ['label'=>'Reunão do fim da semana','id'=>2],
              ];
              //$dado = Assistencia::where('mes','=',$arr[0])->where('ano','=',$arr[1])->get();
              foreach ($arr_reuniao as $kr => $reuniao) {
                  $dados[$kr]['label'] = $reuniao;
                  $dados[$kr]['mes'] = $arr[0];
                  $dados[$kr]['mes_ext'] = $meses[Qlib::zerofill($arr[0],2)];;
                  $dados[$kr]['ano'] = $arr[1];
                  $total = 0;
                  $media = 0;
                  $total_semanas = 0;
                  foreach ($semanas as $ks => $sem) {
                      $dados[$kr]['semanas'][$ks]['id_semana'] = $ks;
                      $dados[$kr]['semanas'][$ks]['semana'] = $sem;
                      if($ks<=5){
                          $arr_assis = Assistencia::where('num_semana','=',$ks)
                              ->where('num_reuniao','=',$reuniao['id'])
                              ->where('mes','=',$arr[0])
                              ->where('ano','=',$arr[1])
                              ->get();
                          if(isset($arr_assis[0]->qtd)){
                            $dados[$kr]['semanas'][$ks]['qtd'] = $arr_assis[0]->qtd;
                            $dados[$kr]['semanas'][$ks]['id'] = $arr_assis[0]->id;
                            $dados[$kr]['semanas'][$ks]['ac'] = 'alt';
                            $total_semanas ++;
                            $seletor = 'edit_'.$arr_assis[0]->id;
                          }else{
                            $dados[$kr]['semanas'][$ks]['qtd'] = 0;
                            $dados[$kr]['semanas'][$ks]['id'] = 0;
                            $dados[$kr]['semanas'][$ks]['ac'] = 'cad';
                            $dados[$kr]['semanas'][$ks]['dados'] =Qlib::encodeArray( ['mes'=>$arr[0],'ano'=>$arr[1],'num_reuniao'=>$reuniao['id'],'num_semana'=>$ks]);
                            $seletor = 'cad_'.$kr.'_'.$ks;
                          }
                          $total += $dados[$kr]['semanas'][$ks]['qtd'];
                      }
                      if($ks==6){
                          $dados[$kr]['semanas'][$ks]['qtd'] = $total;
                          $seletor = 'total_'.$kr.'_'.$ks;
                      }
                      if($ks==7){
                          if($total_semanas > 0){
                            $media = round($total/$total_semanas);
                          }
                          $dados[$kr]['semanas'][$ks]['qtd'] = $media;
                          $seletor = 'media_'.$kr.'_'.$ks;
                      }
                      $dados[$kr]['semanas'][$ks]['seletor'] = $seletor;
                  }
              }
          }
          $ret = $dados;
        }
        //dd($ret);
        return $ret;
    }
    public function index(User $user)
    {
        $meses = Qlib::Meses();
        $this->authorize('is_admin', $user);
        $title = 'Assistência das reuniões';
        $titulo = $title;
        $m = isset($_GET['m']) ? $_GET['m'] : date('m');
        $y = isset($_GET['y']) ? $_GET['y'] : date('Y');
        //$assistencias = Assistencia::orderBy('id','desc')->paginate(12);
        //$assistencias = DB::table('assistencias_rel')->orderBy('id','desc')->paginate(12);
        //$assistencias = DB::table('assistencias_rel')->distinct()->orderBy('id','desc')->get(['mes','ano']);
        $assistencias = Assistencia::distinct()->orderBy('ano','desc')->get(['mes','ano']);
        //$assistencias = DB::select("SELECT distinct mes,ano FROM assistencias ORDER BY ANO DESC");
        //$dadasAss = [];
        //$ass = $assistencias->;
        if($assistencias){
          foreach ($assistencias as $key => $assistencia){
              if(isset($assistencia->mes) && !empty($assistencia->mes)){
                  $assistencias[$key]->mes_ext = $meses[Qlib::zerofill($assistencia->mes,2)];
              }
          }
        }
        return view('assistencias.index',['assistencias'=>$assistencias,'meses'=>$meses,'m'=>$m,'y'=>$y,'titulo'=>$titulo,'title'=>$title]);
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
    public function store(Request $request,User $user)
    {
        $this->authorize('is_admin', $user);
        $dados = $request->all();
        $data = [];
        foreach ($dados as $key => $value) {
            if($key=='dados'){
              $data = Qlib::decodeArray($value);
            }
        }
        $data['qtd'] = $dados['qtd'];
        //$data['num_reuniao'] = $dados['num_reuniao'];
        $salvar = Assistencia::create($data);
        $ret['exec'] = false;
        if($salvar){
          //$GerenciarUsuarios = new GerenciarUsuarios;
          $ret['exec'] = true;
          $ret['salvar'] = $salvar;
          $ret['mens'] = 'Registro gravado com sucesso!';
          $data['dados'] = $this->dadosAssistencia($data['mes'].'_'.$data['ano']);
          $ret['data']=$data;
        }else{
          $ret['mens'] = 'Erro ao gravar!';
        }
        return json_encode($ret);

    }
    public function show(User $user,$id)
    {
        $this->authorize('is_admin', $user);
        $title = 'RELATÓRIO DE ASSISTÊNCIA ÀS REUNIÕES';
        $titulo = $title;
        $dados = [];
        if(isset($id) && !empty($id)){
            $dados = $this->dadosAssistencia($id);
            return view('assistencias.show',['dados'=>$dados,'titulo'=>$titulo,'title'=>$id,'id'=>$id]);
        }else{
            return redirect()->router('assistencias.index')->with('message', 'Assistência atualizado com sucesso');
        }
    }
    public function edit(User $user,$id)
    {
        $this->authorize('is_admin', $user);
        $dados = [];
        if(isset($id) && !empty($id)){
            $dados = $this->dadosAssistencia($id);
            $title = 'Relatório de Assistência de '.$dados[0]['mes_ext'].' de '.$dados[0]['ano'];
            $titulo = $title;

            return view('assistencias.edit',['dados'=>$dados,'titulo'=>$titulo,'title'=>$id,'id'=>$id]);
        }else{
            return redirect()->router('assistencias.index')->with('message', 'Assistência atualizado com sucesso');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Assistencia  $assistencia
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, user $user,$id=false)
    {
        $dados = $request->all();
        unset($dados['ac'],$dados['dados']);
        $salvar = Assistencia::where('id',$id)->update($dados);
        $ret['exec'] = false;
        if($salvar){
          //$GerenciarUsuarios = new GerenciarUsuarios;
          $ret['exec'] = true;
          $ret['salvar'] = $salvar;
          $ret['mens'] = 'Registro gravado com sucesso!';
          $ret['data']=$dados;
        }else{
          $ret['mens'] = 'Erro ao gravar!';
        }
        return json_encode($ret);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Assistencia  $assistencia
     * @return \Illuminate\Http\Response
     */
    public function destroy(Assistencia $assistencia)
    {
        //
    }
}
