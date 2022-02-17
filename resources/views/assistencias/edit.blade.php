@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <!--<h1>{{$titulo}}</h1>-->
@stop
@section('content')
<div class="row">
    <div class="col-md-12">

        <div class="card card-primary card-outline">
          <input type="hidden" name="raiz" value="{{url('/')}}">
            <div class="card-header">
              <h4>{{$titulo}}</h4>
            </div>
            <div class="card-body box-profile">
              <div class="mens">
              </div>
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
                                <td class="text-center @if($k2<=5) event-edit @endif" sele="{{$v2['seletor']}}">
                                    <div class="col-md-12 l1">
                                      <span>
                                        {{$v2['qtd']}}
                                      </span>
                                    @if(isset($v2['dados']))<input type="hidden" name="dados" value="{{$v2['dados']}}">@endif
                                    </div>
                                </td>
                            @endforeach
                          </tr>
                        @endforeach
                      </tbody>
                    </table>

              @endif
            </div>
        </div>
    </div>

    <div class="col-md-12 div-salvar d-print-none">
      <div class="row">
        <div class="col-6">
          <a href=" {{route('assistencias.index')}} " class="btn btn-light"><i class="fa fa-chevron-left"></i> Voltar</a>
          <a href="javascript:window.print()" class="btn btn-primary"> <i class="fa fa-print"></i></a>
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
            $('.event-edit').on('dblclick',function(e){
                editarAssistencia($(this));
            });
          });
    </script>
@stop
