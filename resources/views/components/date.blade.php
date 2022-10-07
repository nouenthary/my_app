@php
    $store = \Illuminate\Support\Facades\DB::table('tec_stores')->where('city','!=','None')->select('id','name')->orderBy('name')->get();
    $user_id = auth()->user()->user_id;
    $current_store = \Illuminate\Support\Facades\DB::table('tec_users')->where('id','=',$user_id)->first();
@endphp

<div class="form-group">
    <label for="date" class="col-md-1 control-label">{{__("language.date")}}</label>

    <div class="col-md-3">
        <div class="input-group">
            <div class="input-group-addon">
                <i class="fa fa-clock-o"></i>
            </div>
            <input type="text" class="form-control pull-right date-range-picker" id="date" name="date" value="">
        </div>
    </div>
</div>


