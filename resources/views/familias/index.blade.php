@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h3>{{$titulo}}</h3>
@stop
@section('content')
  <!--<p>Selecione os publicadores do seu familia para enviar o relatorio para o secretário</p>-->
  <div class="row">
    @include('familias.config_exibe')
    @if(isset($_GET['mens']) && !empty($_GET['mens']))
        <div class="col-md-12 mens">
            {{ App\Qlib\Qlib::formatMensagem( $_GET) }}</p>
        </div>
    @endif
    @can('is_admin')
    <div class="col-md-12">
      <div class="row pl-2 pr-2">
          <div class="col-md-3 info-box mb-3">
              <span class="info-box-icon bg-default elevation-1"><i class="fas fa-users"></i></span>
              <div class="info-box-content">
                  <span class="info-box-text">Total de Famílias</span>
                  <span class="info-box-number">{{ @$familia_totais->todos }}</span>
              </div>
          </div>
          <div class="col-md-3 info-box mb-3">
              <span class="info-box-icon bg-default elevation-1"><i class="fas fa-calendar"></i></i></span>
              <div class="info-box-content">
                  <span class="info-box-text">Cadastros deste Mês</span>
                  <span class="info-box-number">{{ @$familia_totais->esteMes }}</span>
              </div>
          </div>
          <div class="col-md-3 info-box mb-3">
              <span class="info-box-icon bg-default elevation-1"><i class="fas fa-male"></i></span>
              <div class="info-box-content">
                  <span class="info-box-text">Famílias com Idosos</span>
                  <span class="info-box-number">{{ @$familia_totais->idoso }}</span>
              </div>
          </div>
          <div class="col-md-3 info-box mb-3">
              <span class="info-box-icon bg-default elevation-1"><i class="fas fa-child"></i></i></span>
              <div class="info-box-content">
                  <span class="info-box-text">Crianças e adolescentes</span>
                  <span class="info-box-number">{{ @$familia_totais->criancas }}</span>
              </div>
          </div>
      </div>
    </div>
@endcan
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
            <h4 class="card-title">
                @if (!empty($arr_titulo))
                    Lista de:

                    @foreach ($arr_titulo as $k=>$pTitulo)
                        <label for=""> Todo com {{ $k }}</label> = {{ $pTitulo }}, e
                    @endforeach
                @else
                    {{ $titulo_tabela }}
                @endif
            </h4>
            @can('is_admin')
                <div class="card-tools">
                    <a href="{{ route('familias.create') }}" class="btn btn-success">
                        <i class="fa fa-plus" aria-hidden="true"></i> Cadastrar família
                    </a>
                </div>
            @endcan
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover dataTable" style="width: 200%">
                    <thead>
                        <tr>
                            <th class="text-center">...</th>
                        @if (isset($campos_tabela) && is_array($campos_tabela))
                                @foreach ($campos_tabela as $kh=>$vh)
                                    @if (isset($vh['label']) && $vh['active'])
                                        <th style="{{ @$vd['style'] }}">{{$vh['label']}}</th>
                                    @endif
                                @endforeach

                        @else
                            <th>#</th>
                            <th>Nome</th>
                            <th>Area</th>
                            <th>Obs</th>
                        @endif
                        </tr>
                    </thead>
                    <tbody>
                        @if(isset($familias))
                            @foreach($familias as $key => $familia)
                            <tr ondblclick="window.location='{{ route('familias.edit',['id'=>$familia->id]) }}'">
                                <td class="text-right d-flex">
                                    <a href=" {{ route('familias.edit',['id'=>$familia->id]) }} " class="btn btn-light mr-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                        <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                        <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
                                        </svg>
                                    </a>
                                    <form action="{{ route('familias.destroy',['id'=>$familia->id]) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" name="button" title="Excluir" class="btn btn-light">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                                            <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                                            <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                                            </svg>
                                        </button>
                                    </form>
                                </td>
                                @if (isset($campos_tabela) && is_array($campos_tabela))
                                    @foreach ($campos_tabela as $kd=>$vd)
                                        @if (isset($vd['label']) && $vd['active'])
                                            @if (isset($vd['type']) && $vd['type']=='select')
                                                <td>{{$vd['arr_opc'][$familia->$kd]}}</td>
                                            @else
                                                <td>{{$familia->$kd}}</td>
                                            @endif
                                        @endif
                                    @endforeach
                                @else

                                    <td> {{$familia->id}} </td>
                                    <td> {{$familia->nome_completo}} </td>
                                    <td> {{$familia->area_alvo}} </td>
                                    <td> {{$familia->obs}} </td>
                                @endif
                            </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer">
            {{ $familias->appends($_GET)->links() }}
        </div>
      </div>
    </div>
  </div>
  @stop

  @section('css')
      <link rel="stylesheet" href="{{url('/')}}/css/lib.css">
  @stop

  @section('js')
    <script src="{{url('/')}}/js/jquery.inputmask.bundle.min.js"></script>
    <script src=" {{url('/')}}/js/lib.js"></script>

    <script>
        $(function(){
            $('.dataTable').DataTable({
                "paging":   false,
                stateSave: true
            });
            $('[name="filter[cpf]"],[name="filter[cpf_conjuge]"]').inputmask('999.999.999-99');
        });
    </script>
  @stop
