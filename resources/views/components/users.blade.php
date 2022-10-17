@php
    $store = \Illuminate\Support\Facades\DB::table('tec_sales')
        ->join('tec_users','tec_sales.created_by','=','tec_users.id')
        ->select('tec_users.id','tec_users.username as name', 'tec_users.last_name','tec_users.first_name')
        ->groupBy('tec_users.id','tec_users.username', 'tec_users.last_name','tec_users.first_name')
        ->orderBy('tec_users.username')
        ->get();
@endphp

<div class="form-group">
    <label for="product_id" class="col-md-1 control-label">{{__("language.seller")}} :</label>

    <div class="col-md-3">
        <select class="form-control select2" style="width: 100%;" id="seller_id" name="seller_id">
            <option value="">{{__("language.all")}}</option>
            @foreach($store as $s)
                <option value="{{$s->id}}">{{$s->first_name . ' ' .  $s->last_name }}</option>
            @endforeach
        </select>
    </div>
</div>
