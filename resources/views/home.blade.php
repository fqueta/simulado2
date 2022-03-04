@extends('adminlte::page')

@section('title', 'Painel')

@section('content_header')

    <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Painel</h1>
        </div><!-- /.col -->
        <div class="col-sm-6 text-right">
          <div class="btn-group" role="group" aria-label="actions">
            <a href="{{route('familias.create')}}" class="btn btn-primary"><i class="fa fa-plus"></i> Novo cadastro</a>
            <a href="{{route('familias.index')}}" class="btn btn-secondary"><i class="fa fa-list"></i> Ver cadastros</a>
            <a href="#" class="btn btn-dark"><i class="fa fa-chart-bar"></i> Ver relatórios</a>
          </div>
        </div><!-- /.col -->
    </div>
@stop

@section('content')
    <!--<p>Welcome to this beautiful admin panel.</p>-->
    @can('user')
      <h3>Seja bem vindo para ter acesso entre em contato com o suporte</h3>
    @else
    <div class="row">
        <div class="col-lg-4 col-6">
          <!-- small box -->
          <div class="small-box bg-info">
            <div class="inner">
              <h3>{{$config['c_familias']['familia_totais']->todos}}</h3>

              <p>Lotes cadastrados</p>
            </div>
            <div class="icon">
              <i class="fa fa-map-marked-alt"></i>
            </div>
            <a href="{{route('familias.index')}}" class="small-box-footer">Visualizar <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-4 col-6">
          <!-- small box -->
          <div class="small-box bg-success">
            <div class="inner">
              <h3>{{$config['c_familias']['familia_totais']->completos}}<!--<sup style="font-size: 20px">%</sup>--></h3>

              <p>Cadastros completos</p>
            </div>
            <div class="icon">
              <i class="ion ion-stats-bars"></i>
            </div>
            <a href="{{$config['c_familias']['link_completos']}}" class="small-box-footer">Visualizar <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-4 col-6">
          <!-- small box -->
          <div class="small-box bg-warning">
            <div class="inner">
              <h3>{{$config['c_familias']['familia_totais']->idoso}}</h3>

              <p>Familias com idosos</p>
            </div>
            <div class="icon">
              <i class="fa fa-man"></i>
            </div>
            <a href="{{$config['c_familias']['link_idosos']}}" class="small-box-footer">Visualizar <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
    </div>
    <div class="row mb-5">
        @if (isset($config['c_familias']['progresso']))
        <div class="col-md-5">
            <p class="text-center">
                <strong>Progresso dos cadastros</strong>
            </p>
                @foreach ($config['c_familias']['progresso'] as $k=>$v)
                    <div class="progress-group">
                        {{$v['label']}}
                        <span class="float-right"><b>{{$v['total']}}</b>/{{$v['geral']}}</span>
                        <div class="progress progress-sm">
                            <div class="progress-bar {{$v['color']}}" style="width: {{$v['porcento']}}%;"></div>
                        </div>
                    </div>
                @endforeach
        </div>
        @endif
        <div class="col-md-8">
            <!--
            <p class="text-center">
                <strong>Sales: 1 Jan, 2014 - 30 Jul, 2014</strong>
            </p>
            <div class="chart">
                <div class="chartjs-size-monitor">
                    <div class="chartjs-size-monitor-expand"><div class=""></div></div>
                    <div class="chartjs-size-monitor-shrink"><div class=""></div></div>
                </div>

                <canvas id="salesChart" height="180" style="height: 180px; display: block; width: 680px;" width="680" class="chartjs-render-monitor"></canvas>
            </div>-->
        </div>
    </div>

    @endcan


  </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script> console.log('Hi!'); </script>
@stop
