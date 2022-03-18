
<div class="col-md-12 div-salvar bg-light">
        <a href="{{route($config['route'].'.index')}}" redirect="{{@$_GET['redirect']}}" class="btn btn-outline-secondary"><i class="fa fa-chevron-left"></i> Voltar</a>
        @if (isset($config['ac']) && $config['ac']=='alt')
            <a href="{{route($config['route'].'.create')}}" class="btn btn-default"> <i class="fas fa-plus"></i> Novo cadastro</a>
        @endif
        <button type="submit" btn="permanecer" class="btn btn-primary">Salvar e permanecer</button>
        <button type="submit" btn="sair"  class="btn btn-outline-primary">Salvar e Sair <i class="fa fa-chevron-right"></i></button>
        <!--<input type="hidden" name="ac" value="{{@$config['ac']}}"/>-->

</div>
