<form class="" action="@if($familia['ac']=='cad'){{ route('familias.store') }}@elseif($familia['ac']=='alt'){{ route('familias.update',['id'=>$familia['id']]) }}@endif" method="post">
    @if($familia['ac']=='alt')
    @method('PUT')
    @endif
    <div class="row">
      <div class="form-group col-md-8">
          <label for="nome_completo">Nome completo do responsável</label>
          <input type="text" class="form-control @error('nome_completo') is-invalid @enderror" id="nome_completo" name="nome_completo" aria-describedby="nome_completo" placeholder="Nome completo" value="@if(isset($familia['nome_completo'])){{$familia['nome_completo']}}@elseif($familia['ac']=='cad'){{old('nome_completo')}}@endif" />
          @error('nome_completo')
              <div class="alert alert-danger">{{ $message }}</div>
          @enderror
      </div>
      <div class="form-group col-md-4">
          <label for="tel">Celular</label>
          <input type="tel" class="form-control @error('tel') is-invalid @enderror" id="tel" onblur="mask(this, clientes_mascaraTelefone);" onkeypress="mask(this, clientes_mascaraTelefone);" name="tel" aria-describedby="tel" placeholder="informe o celular" value="@if(isset($familia['tel'])){{$familia['tel']}}@elseif($familia['ac']=='cad'){{old('tel')}}@endif">
          @error('tel')
              <div class="alert alert-danger">{{ $message }}</div>
          @enderror
      </div>
      <div class="form-group col-md-4">
          <label for="cpf">CPF</label>
          <input type="cpf" class="form-control @error('cpf') is-invalid @enderror" id="cpf" data-mask="999.999.999-99" name="cpf" aria-describedby="cpf" placeholder="999.999.999-99" value="@if(isset($familia['cpf'])){{$familia['cpf']}}@elseif($familia['ac']=='cad'){{old('cpf')}}@endif">
          @error('cpf')
              <div class="alert alert-danger">{{ $message }}</div>
          @enderror
      </div>

      <div class="col-md-12 mb-5">
        <div class="form-group">
          <label for="obs">Observação</label><br>
          <textarea name="obs" class="form-control" rows="8" cols="80">@if(isset($familia['obs'])){{$familia['obs']}}@elseif($familia['ac']=='cad'){{old('obs')}}@endif</textarea>
        </div>
      </div>
      <div class="col-md-12 div-salvar">
        <div class="form-group">
          <a href=" {{route('familias.index')}} " class="btn btn-light"><i class="fa fa-chevron-left"></i> Voltar</a>
          @if(isset($familia['id']))
          <a href="{{ route('familias.cartao',['id'=>$familia['id']]) }}" title="Cartão do publicador" class="btn btn-light print-card">
              <i class="fa fa-file-pdf"></i> Cartão
           </a>
           @endif
          <button type="submit" class="btn btn-primary">Salvar <i class="fa fa-chevron-right"></i></button>
        </div>
      </div>
      @csrf
    </div>
</form>
