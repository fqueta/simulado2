@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>{{$titulo}}</h1>
@stop
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card card-primary card-outline">
            <div class="card-body box-profile">

              @if(isset($dados[0]['semanas']))
                <table class="table table-bordered">
                  <thead>
                    <th>{{$dados[0]['ano']}}</th>
                    @foreach ($dados[0]['semanas'] as $key => $v)
                        <th class="text-center">{{$v['semana']}}</th>

                    @endforeach
                  </thead>
                  <tbody>
                    @foreach ($dados as $k1 => $v1)
                      <tr>
                        <td>{{ $v1['label']['label'] }}</td>
                        @foreach ($v1['semanas'] as $k2 => $v2)
                            <td class="text-center">{{$v2['qtd']}}</td>
                        @endforeach
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              @endif
            </div>
        </div>
    </div>

    <div class="col-md-12 div-salvar">
      <div class="row">
        <div class="col-6">
          <a href=" {{route('assistencias.index')}} " class="btn btn-light"><i class="fa fa-chevron-left"></i> Voltar</a>
          <a href=" {{route('assistencias.edit',['id'=>$id])}} " class="btn btn-primary">Editar <i class="fa fa-chevron-right"></i></a>
          <span style="position: absolute;top: 0px;left: 171px;">

          </span>
        </div>
        <div class="col-6 text-right">


        </div>
      </div>
    </div>
</div>
@stop

@section('css')
    <link rel="stylesheet" href=" {{url('/')}}/css/lib.css">
@stop

@section('js')
    <script src=" {{url('/')}}/js/lib.js"></script>
    <script type="text/javascript">
          $(function(){
            $('a.print-card').on('click',function(e){
                openPageLink(e,$(this).attr('href'),"{{date('Y')}}");
            });
          });
    </script>
@stop
