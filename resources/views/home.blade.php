@extends('adminlte::page')

@section('title', 'Painel')

@section('content_header')
    <!--<h1>Painel</h1>-->
@stop

@section('content')
    <!--<p>Welcome to this beautiful admin panel.</p>-->
    @can('user')
      <h3>Seja bem vindo para ter acesso entre em contato com o suporte</h3>
    @else

    @endcan


  </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script> console.log('Hi!'); </script>
@stop
