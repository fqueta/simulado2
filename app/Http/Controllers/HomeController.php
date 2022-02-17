<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\relatorio;
use App\Models\Assistencia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;
use App\Qlib\Qlib;
//use Spatie\Permission\Models\Role;
//use Spatie\Permission\Models\Permission;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    protected $user;
    public function __construct(User $user)
    {
        $this->middleware('auth');
        $this->user = $user;
    }

    public function teste(){
      //$dados = $request->all();
      //var_dump($dados);
      return view('teste');
    }
    public function upload(Request $request){
      $dados = $request->all();
      var_dump($dados);
    }


    public function index()
    {
        /*
        $ano_servico = date('Y');
        $ano_atual = $ano_servico;
        $mes_atual =  isset($_GET['mes'])?$_GET['mes']:date('m');
        if($mes_atual > 8){
          $ano_servico++;
        }
        $ano = isset($_GET['ano'])?$_GET['ano']:$ano_servico;
        if($ano < $ano_servico){
          $ano_servico = $ano;
        }else{
          if($mes_atual > 8){
            $ano = $ano+1;
          }
        }

        $mes = $mes_atual;
        if($mes == '01'){
          $mes = '12';
          $ano = date('Y') - 1;
        }else{
          $mes--;

        }
        $controllerRelatorio = new GerenciarRelatorios($this->user);
        $estatisticas = $controllerRelatorio->estatisticas($mes,$ano);

        $compleSql = " WHERE mes='$mes' AND ano='$ano' AND hora > '0'";
        //$relatorios = relatorio::where('mes','=',$mes)->orWhere('ano','=',$ano)->get();
        //$complePub = " AND pri";
        //$relatorios = DB::select("SELECT DISTINCT mes,ano,id_publicador,privilegio,obs FROM relatorios $compleSql");
        $relatorios = DB::select("SELECT * FROM relatorios $compleSql");
        //echo '<pre>';
        //print_r($relatorio);
        //echo '</pre>';
        $arr_resumo = [
          'pr'=>['relatorios'=>0,'publicacao'=>0,'video'=>0,'hora'=>0,'revisita'=>0,'estudo'=>0],
          'pa'=>['relatorios'=>0,'publicacao'=>0,'video'=>0,'hora'=>0,'revisita'=>0,'estudo'=>0],
          'p'=>['relatorios'=>0,'publicacao'=>0,'video'=>0,'hora'=>0,'revisita'=>0,'estudo'=>0],
          'tg'=>['relatorios'=>0,'publicacao'=>0,'video'=>0,'hora'=>0,'revisita'=>0,'estudo'=>0]
        ];
        $mesExt = Qlib::Meses();
        $config_table = [
          'tabelas'=>[
            'pr'=>['label'=>'Prioneiros Relegulares'],
            'pa'=>['label'=>'Prioneiros Auxiliares'],
            'p'=>['label'=>'Publicadores'],
            'tg'=>['label'=>'Totais'],
          ],
          'titulos'=>[
            'publicacao'=>'Publicação','video'=>'Vídeos mostrados','hora'=>'Horas','revisita'=>'Revisitas','estudo'=>'Estudos bíblicos'
          ],
          'data'=>[
            'titulo'=>'RESUMO DOS RELATORIOS DE '.$mesExt[Qlib::zerofill($mes,2)].' DE '.$ano,
            'mes'=>$mes,
            'ano'=>$ano,
          ]
        ];
        if($relatorios){
          //dd($arr_resumo['tg']);
          foreach ($relatorios as $key => $value) {
            //if(is_array($value)){
              foreach ($arr_resumo['p'] as $k => $v) {
                  if($value->privilegio=='pa'){
                      if(isset($value->$k)){
                          $arr_resumo[$value->privilegio][$k] += $value->$k;
                          if(isset($arr_resumo['tg'][$k])){
                            $arr_resumo['tg'][$k] += $value->$k;
                          }
                      }
                  }elseif($value->privilegio=='pr'){
                      if(isset($value->$k)){
                          $arr_resumo[$value->privilegio][$k] += $value->$k;
                          if(isset($arr_resumo['tg'][$k])){
                            $arr_resumo['tg'][$k] += $value->$k;
                          }
                      }
                  }else{
                      if(isset($value->$k)){
                        $arr_resumo['p'][$k] += $value->$k;
                        if(isset($arr_resumo['tg'][$k])){
                          $arr_resumo['tg'][$k] += $value->$k;
                        }
                      }
                  }
              }
              if($value->privilegio=='pr'){
                  $arr_resumo[$value->privilegio]['relatorios'] ++;
              }elseif($value->privilegio=='pa'){
                  $arr_resumo[$value->privilegio]['relatorios'] ++;
              }else{
                  $arr_resumo['p']['relatorios'] ++;
              }
              $arr_resumo['tg']['relatorios'] ++;
          }
        }
        //dd($arr_resumo);
        $publicadores_ativos = Qlib::totalReg('usuarios',"WHERE inativo='n'");
        $publicadores_inativos = Qlib::totalReg('usuarios',"WHERE inativo='s'");
        $publicadores_todos = Qlib::totalReg('usuarios',"");
        $publicadores['relatorios'] = $relatorios;
        $publicadores['total_relatorios']['geral'] = count($relatorios);
        $publicadores['total_resumo'] = $arr_resumo;
        $publicadores['config_table'] = $config_table;
        $asistenciaFimSemana = Assistencia::where('num_reuniao','=',2)->where('mes','=',$mes)->where('ano','=',$ano)->sum('qtd');
        $t_reuniaoFimSemana = Assistencia::where('num_reuniao','=',2)->where('mes','=',$mes)->where('ano','=',$ano)->count();
        if($asistenciaFimSemana && $t_reuniaoFimSemana){
          $mediaFimSemana = round($asistenciaFimSemana/$t_reuniaoFimSemana);
          $colorAss = 'success';
        }else{
          $mediaFimSemana = 0;
          $colorAss = 'danger';
        }
        //$mediaAssitencia['fim_semana'] = Assistencia:: ->where('num_reuniao','=',2)->where('mes','=',$mes)->where('ano','=',$ano)->count();
        $publicadores['total_cards'] = [
            ['valor'=>$publicadores_todos,'url'=>'todos','label'=>'Publicadores','color'=>'info','link'=>route('usuarios.index')],
            ['valor'=>$publicadores_ativos,'url'=>'ativos','label'=>'Publicadores Ativos','color'=>'success','link'=>route('usuarios.index').'?fil[inativo]=n'],
            ['valor'=>$publicadores_inativos,'url'=>'inativos','label'=>'Publicadores Inativos','color'=>'danger','link'=>route('usuarios.index').'?fil[inativo]=s'],
            ['valor'=>$mediaFimSemana,'url'=>'media-fim-semana','label'=>'Assistência fim de semana','color'=>$colorAss,'link'=>route('assistencias.index').'/'.$mes.'_'.$ano.'/edit'],
        ];
        $totalPubMes = '';
        $totalVid = '';
        //$resumo = $publicadores;
        */
        return view('home');
    }
    public function resumo(){

    }
}
