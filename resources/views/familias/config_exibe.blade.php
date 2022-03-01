<div class="col-md-12 d-print-none">
    <div class="card">
        <form action="" id="frm-consulta" method="GET">
            <div class="row mr-0 ml-0">
                <div class="col-md-5 pt-4 pl-2">
                    <a class="btn @if(isset($_GET['filter'])) btn-link @else btn-primary @endif" data-toggle="collapse" href="#contentId" aria-expanded="false" aria-controls="contentId">
                        <i class="fas fa-search-location    "></i> @if(isset($_GET['filter'])) Mostrar Critérios de pesquisa @else Pesquisar @endif
                    </a>
                </div>
                {{App\Qlib\Qlib::qForm([
                    'type'=>'select',
                    'campo'=>'limit',
                    'placeholder'=>'',
                    'label'=>'Por página',
                    'ac'=>'alt',
                    'value'=>@$config['limit'],
                    'tam'=>'2',
                    'arr_opc'=>['20'=>'20','50'=>'50','100'=>'100','200'=>'200','500'=>'500','todos'=>'Todos'],
                    'event'=>'onchange=$(\'#frm-consulta\').submit();',
                    'option_select'=>false,
                    'class'=>'text-center',
                    'class_div'=>'text-center',
                ])}}
                {{App\Qlib\Qlib::qForm([
                    'type'=>'radio',
                    'campo'=>'order',
                    'placeholder'=>'',
                    'label'=>false,
                    'ac'=>'alt',
                    'value'=>@$config['order'],
                    'tam'=>'5',
                    'arr_opc'=>['asc'=>'Ordem crescente','desc'=>'Ordem decrescente'],
                    'event'=>'order=true',
                    'class'=>'btn btn-light',
                    'option_select'=>false,
                    'class_div'=>'pt-4 text-right',
                ])}}
                <div class="collapse" id="contentId">
                    @include('familias.busca')
                </div>
            </div>
        </form>
    </div>
</div>
