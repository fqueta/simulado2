@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>{{$titulo}}</h1>
@stop
@section('content')

  <div class="row">
    @can('is_admin')
        <div class="col-md-12">
          <div class="row pl-2 pr-2">

              <div class="col-md-3 info-box mb-3">
                  <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-user-shield"></i></span>
                  <div class="info-box-content">
                      <span class="info-box-text">Administradores</span>
                      <span class="info-box-number">{{ $user_counters->administrator_users }}</span>
                  </div>
              </div>
              <div class="col-md-3 info-box mb-3">
                  <span class="info-box-icon bg-default elevation-1"><i class="fas fa-users"></i></i></span>
                  <div class="info-box-content">
                      <span class="info-box-text">Usuários padrão</span>
                      <span class="info-box-number">{{ $user_counters->user_users }}</span>
                  </div>
              </div>
              <div class="col-md-3 info-box mb-3">
                  <span class="info-box-icon bg-primary elevation-1"><i class="fas fa-male"></i></span>
                  <div class="info-box-content">
                      <span class="info-box-text">Usuários Masculino</span>
                      <span class="info-box-number">{{ $user_counters->male_users }}</span>
                  </div>
              </div>
              <div class="col-md-3 info-box mb-3">
                  <span class="info-box-icon elevation-1" style="background-color: #96509c!important;"><i class="fas fa-female"></i></i></span>
                  <div class="info-box-content">
                      <span class="info-box-text">Usuários Feminino</span>
                      <span class="info-box-number">{{ $user_counters->female_users }}</span>
                  </div>
              </div>
          </div>
        </div>
    @endcan
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header border-0">
                        <h3 class="card-title">Listagem de usuários</h3>
                        @can('is_admin')
                            <div class="card-tools">
                                <a href="{{ route('users.create') }}" class="btn btn-success">
                                    <i class="fas fa-plus"></i> Novo usuário
                                </a>
                            </div>
                        @endcan
                    </div>

                    <div class="card-body table-responsive p-0">
                        <table class="table table-striped table-valign-middle">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>E-mail</th>
                                    <th>Status</th>
                                    <th>Gênero</th>
                                    <th>Perfil</th>
                                    @can('is_admin')
                                        <th>...</th>
                                    @endcan
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $item)
                                    @if(Auth::user()->profile=='dev' && $item->profile=='dev')
                                    <tr>
                                        <td>{{ $item->id }}</td>
                                        <td>{{ $item->name }}</td>
                                        <td>{{ $item->email }}</td>
                                        <td>{{  $item->status }}</td>
                                        <td>{{  $item->gender }}</td>
                                        <td>{{  $item->profile }}</td>
                                        @can('is_admin')
                                            <td class="d-flex">
                                              <a href=" {{ route('users.show',['id'=>$item->id]) }} " class="btn btn-primary mr-2" title="Visualizar">
                                               <i class="fa fa-search"></i>
                                             </a>
                                             <form action="{{ route('users.destroy',['id'=>$item->id]) }}" method="POST">
                                               @csrf
                                               @method('DELETE')
                                               <button type="submit" name="button" title="Excluir" class="btn btn-secondary">
                                                 <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                                                   <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                                                   <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                                                   </svg>
                                               </button>
                                             </form>
                                            </td>
                                        @endcan
                                    </tr>
                                    @elseif($item->profile!='dev')
                                    <tr>
                                        <td>{{ $item->id }}</td>
                                        <td>{{ $item->name }}</td>
                                        <td>{{ $item->email }}</td>
                                        <td>{{  $item->status }}</td>
                                        <td>{{  $item->gender }}</td>
                                        <td>{{  $item->profile }}</td>
                                        @can('is_admin')
                                            <td class="d-flex">
                                              <a href=" {{ route('users.show',['id'=>$item->id]) }} " class="btn btn-primary mr-2" title="Visualizar">
                                               <i class="fa fa-search"></i>
                                             </a>
                                             <form action="{{ route('users.destroy',['id'=>$item->id]) }}" method="POST">
                                               @csrf
                                               @method('DELETE')
                                               <button type="submit" name="button" title="Excluir" class="btn btn-secondary">
                                                 <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                                                   <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                                                   <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                                                   </svg>
                                               </button>
                                             </form>
                                            </td>
                                        @endcan
                                    </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                        <div class="d-felx justify-content-center">
                            {{ $users->links() }}
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
      <script> console.log('Hi!'); </script>
  @stop
