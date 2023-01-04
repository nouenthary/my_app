@extends('main')
@section('content')

    <div class="box box-primary col-md-6">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-home"></i> បន្ថែមទំនិញតាមសាខា</h3>
        </div>
        <!-- /.box-header -->
        <!-- form start -->
        <form role="form">
            <div class="box-body">

                <div class="row">
                    <div class="col-md-4 col-sm-12">
                        <div class="form-group">
                            <label for="datetime">វិក្កយបត្រ</label>
                            <input type="text" class="form-control" id="exampleInputEmail1" placeholder="Enter Invoice">
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-12">
                        <div class="form-group">
                            <label for="date">កាលបរិច្ឆេទនៃការផ្ទេរ</label>
                            <input type="datetime-local" class="form-control" id="exampleInputEmail1"
                                   placeholder="Enter email">
                        </div>
                    </div>
                </div>

            </div>
            <!-- /.box-body -->

            <div class="box-footer">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
    </div>

@endsection
