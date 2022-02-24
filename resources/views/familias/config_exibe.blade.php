<div class="col-md-12">
    <div class="card">
        <form action="" method="GET">
            <div class="row mr-0 ml-0">
                <div class="col-md-4 pt-4 pl-2">
                    <a class="btn btn-primary" data-toggle="collapse" href="#contentId" aria-expanded="false" aria-controls="contentId">
                        Pesquisar
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
                    'arr_opc'=>['20'=>'20','50'=>'50','100'=>'100','200'=>'200','500'=>'500'],
                    'event'=>'',
                    'option_select'=>false,
                    'class'=>'text-center',
                ])}}
                <div class="collapse" id="contentId">
                    @include('familias.busca')
                </div>
            </div>
        </form>
    </div>
</div>
