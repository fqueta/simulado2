@extends('adminlte::page')

@section('title', 'Painel')

@section('content_header')
    <!--<h1>Painel</h1>-->
@stop

@section('content')
    <!--<p>Welcome to this beautiful admin panel.</p>-->

    <link rel="stylesheet" href="{{url('/')}}/css/dropzone.min.css" type="text/css" />

    <form action="/file-upload"
        class="dropzone"
        id="my-drop">
    </form>



  </div>
@stop
@section('css')
      <link rel="stylesheet" href="{{url('/')}}/css/lib.css">
@stop

@section('js')
    <script src="{{url('/')}}/js/jquery.inputmask.bundle.min.js"></script>
    <script src="{{url('/')}}/js/dropzone.min.js"></script>

    <script src=" {{url('/')}}/js/lib.js"></script>
    <script>
        // The dropzone method is added to jQuery elements and can
    // be invoked with an (optional) configuration object.
    carregaDropZone('#my-drop');

    </script>

@stop
