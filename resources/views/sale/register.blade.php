@extends('main')
@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">{{lang('register_sale')}}</h3>
        </div>
        <!-- /.box-header -->
        <!-- form start -->
        <form role="form" method="get">
            <div class="box-body">
                <div class="form-group">
                    <label for="cash_in_hand">{{lang('cash_in_hand')}} :</label>
                    <input type="text" class="form-control" name="cash_in_hand" id="cash_in_hand"
                           required placeholder="{{lang('enter')}} {{lang('cash_in_hand')}}"
                           onkeypress="return isDecimalNumber(event)">
                </div>
                <div class="form-group">
                    <label for="note">{{lang('note')}} : </label>

                    <textarea rows="5" class="form-control" id="note" name="note"
                              placeholder="{{lang('enter')}} {{lang('note')}}"></textarea>
                </div>

            </div>
            <!-- /.box-body -->

            <div class="box-footer">
                <button type="submit" class="btn btn-primary">{{lang('register_sale')}}</button>
            </div>
        </form>
    </div>

@endsection
