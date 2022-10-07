@php
    $store = \Illuminate\Support\Facades\DB::table('tec_warehouse')->orderBy('ware_name')->get();
@endphp

@if($style == 'inline')

    <div class="form-group">
        <label for="warehouse_id" class="col-md-1 control-label">{{__("language.warehouse")}} :</label>

        <div class="col-md-3">
            <select class="form-control select2" style="width: 100%;" id="warehouse_id" name="warehouse_id">
                <option value="">{{__("language.all")}}</option>
                @foreach($store as $s)
                    <option value="{{$s->warehouse_id}}">{{$s->ware_name}}</option>
                @endforeach
            </select>
        </div>
    </div>


@else
    <div class="form-group">
        <label for="warehouse_id" class=" control-label">{{__("language.warehouse")}} :</label>

        <select class="form-control select2" style="width: 100%;" id="warehouse_id" name="warehouse_id" required>
            <option value="">{{__("language.all")}}</option>
            @foreach($store as $s)
                <option value="{{$s->warehouse_id}}">{{$s->ware_name}}</option>
            @endforeach
        </select>
    </div>
@endif


