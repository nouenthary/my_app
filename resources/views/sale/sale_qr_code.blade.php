@extends('main')
@section('content')

    <style>
        @media (max-width: 687px) {
            .logo, .content-header, .navbar-custom-menu {
                display: none !important;
            }

            .fixed .content-wrapper, .fixed .right-side {
                padding-top: 0;
            }


        }

    </style>



    <div class="text-center">
        <h4>Scan QR</h4>
    </div>

    <div style="height:auto;" id="reader"></div>
    <br>



    <table class="table table-bordered table-striped">
        <tr class="bg-successs text-primary bg-active">
            <td><span>ចំនួនសរុប : </span><span id="total-qty"> 0 (0) </span></td>
            <td><span>តម្លៃសរុប : </span> <span id="total-price"> 0$</span></td>
        </tr>
    </table>



    <ul class="list-group">
        {{--        <li></li>--}}
    </ul>

    <button class="btn btn-success btn-block btn-payment"><i class="fa fa-shopping-bag"></i> {{lang('payment')}}
    </button>

    <button class="btn btn-danger btn-block btn-cancel"><i class="fa fa-trash"></i> {{lang('cancel')}}</button>


    <audio id="water_droplet" >
        <source src="{{URL::asset('sounds/water_droplet.mp3')}}" type="audio/mpeg"></source>
    </audio>

    <audio id="camera_flashing_2" >
        <source src="{{URL::asset('sounds/camera_flashing_2.mp3')}}" type="audio/mpeg"></source>
    </audio>

    <audio id="door_bell" >
        <source src="{{URL::asset('sounds/door_bell.mp3')}}" type="audio/mpeg"></source>
    </audio>




@endsection

@push('scripts')
    <script src="https://reeteshghimire.com.np/wp-content/uploads/2021/05/html5-qrcode.min_.js"></script>
    <script type="text/javascript">
        let product = "{{lang('product')}}", unit_price = "{{lang('unit_price')}}", total = "{{lang('total')}}";
    </script>
{{--    <script src="{{asset('js/ion.sound.js')}}"></script>--}}
    <script type="text/javascript" src="{{ URL::asset('js/sales/sale.qr.code.js')}}"></script>
@endpush
