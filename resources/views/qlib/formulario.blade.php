@php
    $config = $conf['config'];
    $campos = $conf['campos'];
    $value = $conf['value'];
@endphp

<form id="{{$config['frm_id']}}" class="" action="@if($config['ac']=='cad'){{ route($config['route'].'.store') }}@elseif($config['ac']=='alt'){{ route($config['route'].'.update',['id'=>$config['id']]) }}@endif" method="post">
    @if($config['ac']=='alt')
    @method('PUT')
    @endif
    <div class="row">
        <div class="col-md-12 text-right">
            @if (isset($value['id']))
                <label for="">Id:</label> {{ $value['id'] }}
            @endif
            @if (isset($value['created_at']))
                <label for="">Cadastro:</label> {{ Carbon\Carbon::parse($value['created_at'])->format('d/m/Y') }}
            @endif

        </div>
        @if (isset($campos) && is_array($campos))
            @foreach ($campos as $k=>$v)
            {{App\Qlib\Qlib::qForm([
                    'type'=>@$v['type'],
                    'campo'=>$k,
                    'label'=>$v['label'],
                    'placeholder'=>@$v['placeholder'],
                    'ac'=>$config['ac'],
                    'value'=>isset($v['value'])?$v['value']: @$value[$k],
                    'tam'=>@$v['tam'],
                    'event'=>@$v['event'],
                    'checked'=>@$value[$k],
                    'selected'=>@$v['selected'],
                    'arr_opc'=>@$v['arr_opc'],
                    'option_select'=>@$v['option_select'],
                    'class'=>@$v['class'],
                    'class_div'=>@$v['class_div'],
                    'rows'=>@$v['rows'],
                    'cols'=>@$v['cols'],
                    'data_selector'=>@$v['data_selector'],
            ])}}
            @endforeach
        @endif
<div class="col-md-12 div-salvar">
    <div class="form-group">
            <a href=" {{route($config['route'].'.index')}} " class="btn btn-light"><i class="fa fa-chevron-left"></i> Voltar</a>

            <button type="submit" class="btn btn-primary">Salvar <i class="fa fa-chevron-right"></i></button>
            </div>
        </div>
        @csrf
    </div>
</form>