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
                @include('familias.frm')
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
                    'token_produto'=>$familia['token'],
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
    <script src="{{url('/')}}/js/jquery.inputmask.bundle.min.js"></script>
    <script src=" {{url('/')}}/js/lib.js"></script>
    <script type="text/javascript">
          $(function(){
            $('a.print-card').on('click',function(e){
                openPageLink(e,$(this).attr('href'),"{{date('Y')}}");
            });
            $('#cpf,#cpf_conjuge').inputmask('999.999.999-99');
          });

    </script>
@stop
