@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>{{$titulo}}</h1>
@stop
@section('content')
  <p>The .table-bordered class adds borders on all sides of the table and the cells:</p>
  <div class="row">
    <div class="col-md-4">
      <select class="form-control" id="mes" name="">
          @if(isset($meses))
            @foreach($meses As $k=>$mes)
            <option value="{{$k}}" @if(isset($m) && $m==$k) selected @endif>{{$mes}}</option>
            @endforeach
          @else
            <option value="">---</option>
          @endif
      </select>
    </div>
    <div class="col-md-4">
      <input type="number" value="@if(isset($y) && !empty(isset($y))){{$y}}@else{{ date('Y') }}@endif" class="form-control" id="ano" name="ano" placeholder="Selecione o ano">
    </div>
    <div class="col-md-4 text-right mb-3">

      <a id="cad-assistencia" href="{{ route('assistencias.index') }}/{m}_{y}/edit" class="btn btn-success"> Registrar assistência</a>
    </div>
    <div class="col-md-12">
      <div class="card">
        <div class="card-body">
          <table class="dataTable table dtr-inline table-hover">
            <thead>
              <tr>
                <th>Ano</th>
                <th>Mes</th>
                <!--<th>Reunião</th>-->
                <th class="text-center">...</th>
              </tr>
            </thead>
            <tbody>
              @foreach($assistencias as $key => $assistencia)
                @if(!empty($assistencia->ano))
                  <tr>
                      <td> {{$assistencia->ano}} </td>
                      <td> {{$assistencia->mes_ext}} </td>
                      <!--<td> {{$assistencia->num_reuniao}} </td>-->
                      <td class="text-center d-flex">
                         <a href=" {{ route('assistencias.edit',['id'=>$assistencia->mes.'_'.$assistencia->ano]) }} " class="btn btn-primary mr-2">
                          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                            <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                            <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
                          </svg>
                        </a>

                    </td>
                  </tr>
                @endif
              @endforeach
            </tbody>
          </table>
        </div>
        <div class="card-footer">
          <div class="d-felx justify-content-center">

          </div>
        </div>
      </div>

    </div>
  </div>
  @stop

  @section('css')
      <link rel="stylesheet" href="/css/admin_custom.css">
  @stop

  @section('js')
      <script>
        $(function(){
          $('.dataTable').DataTable({
            stateSave: true
          });
          $('#cad-assistencia').on('click',function(e){
            e.preventDefault();
            var url = $(this).attr('href');
            var m = $('#mes').val();
            var y = $('[name="ano"]').val();
            url = url.replace('{m}',m);
            url = url.replace('{y}',y);
            window.location = url;
          });
        });
      </script>
  @stop
