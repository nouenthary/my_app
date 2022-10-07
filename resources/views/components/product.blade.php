@php
    $store = \Illuminate\Support\Facades\DB::table('tec_products')->select('id','name')->orderBy('name')->get();
@endphp

<div class="form-group">
    <label for="product_id" class="col-md-1 control-label">{{__("language.product")}} :</label>

    <div class="col-md-3">
        <select class="form-control select2" style="width: 100%;" id="product_id" name="product_id">
            <option value="">{{__("language.all")}}</option>
            @foreach($store as $s)
                <option value="{{$s->id}}">{{$s->name}}</option>
            @endforeach
        </select>
    </div>
</div>
