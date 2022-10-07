@php
    $store = \Illuminate\Support\Facades\DB::table('tec_stores')->where('city','!=','None')->select('id','name')->orderBy('name')->get();
    $user_id = auth()->user()->user_id;
    $current_store = \Illuminate\Support\Facades\DB::table('tec_users')->where('id','=',$user_id)->first();
@endphp

<div class="form-group">
    <label for="store_id" class="col-md-1 control-label">{{__("language.branch")}} :</label>

    <div class="col-md-3">
        <select class="form-control form-control-sm select2" style="width: 100%;" id="store_id" name="store_id">
            <option value="">{{__("language.all")}}</option>
            @foreach($store as $s)
                <option {{ $current_store->store_id == $s->id ? 'selected="selected"' : '' }}  value="{{$s->id}}">{{$s->name}}</option>
            @endforeach
        </select>
    </div>
</div>
