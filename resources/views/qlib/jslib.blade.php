@include('qlib.partes_html',['config'=>[
    'parte'=>'modal',
    'id'=>'modal-geral',
    'conteudo'=>false,
    'botao'=>false,
    'botao_fechar'=>true,
    'tam'=>'modal-lg',
]])
<script src="{{url('/')}}/js/jquery.maskMoney.min.js"></script>
<script src="{{url('/')}}/js/jquery.inputmask.bundle.min.js"></script>
<script src=" {{url('/')}}/js/lib.js"></script>
<script>
    $(function(){
        $(".moeda").maskMoney({
            prefix: 'R$ ',
            allowNegative: true,
            thousands: '.',
            decimal: ','
        });
        $('[selector-event]').on('change',function(){
            initSelector($(this));
        });
    });
</script>
