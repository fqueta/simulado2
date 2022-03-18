@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h3>{{$titulo}}</h3>
@stop
@section('content')
<div class="row">
    <div class="col-md-12 mens">
        {{ App\Qlib\Qlib::formatMensagem( $_GET) }}
    </div>
    <div class="col-md-8">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Informações</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                      <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 matricula {{@$config['display_matricula']}}">
                        <label for="matricula">Matricula:</label> <span>{{@$value['matricula']}}</span>
                    </div>
                </div>
                {{App\Qlib\Qlib::formulario([
                    'campos'=>$campos,
                    'config'=>$config,
                    'value'=>$value,
                ])}}

            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Arquivos</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                      <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                {{App\Qlib\Qlib::gerUploadAquivos([
                    'pasta'=>'familias/'.date('Y').'/'.date('m'),
                    'token_produto'=>$value['token'],
                    'tab'=>'familias',
                    'listFiles'=>@$listFiles,
                ])}}
            </div>
        </div>
    </div>
</div>

@stop

@section('css')
    <link rel="stylesheet" href=" {{url('/')}}/css/lib.css">
@stop

@section('js')
    @include('qlib.jslib')
    <script type="text/javascript">
          $(function(){
            $('a.print-card').on('click',function(e){
                openPageLink(e,$(this).attr('href'),"{{date('Y')}}");
            });
            $('#inp-cpf,#inp-cpf_conjuge').inputmask('999.999.999-99');
          });
          $(function(){
          $('[type="submit"]').on('click',function(e){
                e.preventDefault();
                let btn_press = $(this).attr('btn');
                submitFormulario($('#{{$config['frm_id']}}'),function(res){
                    if(res.exec){
                        lib_formatMensagem('.mens',res.mens,res.color);
                    }
                    if(btn_press=='sair'){
                        if(res.return){
                            window.location = res.return
                        }
                    }else if(btn_press=='permanecer'){
                        if(res.redirect){
                            window.location = res.redirect;
                        }
                    }
                    if(res.errors){
                        alert('erros');
                        console.log(res.errors);
                    }
                });
          });
    });
    function carregaMatricula(val){
        if(val==''|| val=='cad'|| val=='ger')
            return ;
        getAjax({
            url:'/bairros/'+val+'/edit?ajax=s',
        },function(res){
            if(m=res.value.matricula){
                $('[name="matricula"]').val(m);
            }
        });
    }

    </script>
@stop
