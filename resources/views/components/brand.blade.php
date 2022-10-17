@php
    $store = \Illuminate\Support\Facades\DB::table('brands')
        ->select('id','brand_name as name')
        ->orderBy('brand_name')
        ->get();

@endphp

<div class="form-group">
    <label for="seller_id_search" class="col-md-1 control-label">{{__("language.brand")}} :</label>

    <div class="col-md-3">
        <select class="form-control select2" style="width: 100%;" id="brand_id_search" name="brand_id_search">
            <option value="">{{__("language.all")}}</option>
            @foreach($store as $s)
                <option  value="{{$s->id}}">{{$s->name  }}</option>
            @endforeach
        </select>
    </div>
</div>
