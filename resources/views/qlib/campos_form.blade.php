@if (isset($config['type']))
    @if ($config['type']=='select')
        @if (isset($config['arr_opc']))
        <div class="form-group col-{{$config['col']}}-{{$config['tam']}} {{$config['class_div']}}">
            <label for="{{$config['campo']}}">{{$config['label']}}</label>
            <select name="{{$config['campo']}}" {{$config['event']}} id="sele-{{$config['campo']}}" class="form-control selectpicker {{$config['class']}}">
                @if ($config['option_select'])
                    <option value=""> {{$config['label_option_select']}} </option>
                @endif
                @foreach ($config['arr_opc'] as $k=>$v)
                    <option value="{{$k}}" @if(isset($config['value']) && $config['value'] == $k) selected @endif>{{$v}}</option>
                @endforeach
            </select>
            @error($config['campo'])
            <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        @endif
    @else
    <div class="form-group col-{{$config['col']}}-{{$config['tam']}} {{$config['class_div']}}" div-id="{{$config['campo']}}" >
        @if ($config['label'])
            <label for="{{$config['campo']}}">{{$config['label']}}</label>
        @endif
        <input type="{{$config['type']}}" class="form-control @error($config['campo']) is-invalid @enderror {{$config['class']}}" id="inp-{{$config['campo']}}" name="{{$config['campo']}}" aria-describedby="{{$config['campo']}}" placeholder="{{$config['placeholder']}}" value="@if(isset($config['value'])){{$config['value']}}@elseif($config['ac']=='cad'){{old($config['campo'])}}@endif" {{$config['event']}} />
        @error($config['campo'])
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror
    </div>
    @endif
@endif
