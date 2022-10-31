@extends('main')
@section('content')

    @php
        //print_r($product[0]);
    @endphp

    <style>
        .mailbox-attachment-info {
            background-color: #fff;
        }
    </style>

    @foreach($product as $item)

        <div id="external-events">
            <div class="external-event bg-blue ui-draggable ui-draggable-handle" style="position: relative;">
                {{lang('price')}} <?php print_r(number_format($item[0]->price))?>
            </div>
        </div>

        <div class="box-footer">
            <ul class="mailbox-attachments clearfix">
                @foreach($item as $row)

                    <li>
                        <span class="mailbox-attachment-icon has-img">
                            <img src="/uploads/{{$row->image}}" alt="Attachment"
                                 style="width:200px; height: 180px; cursor:pointer;"
                                 onerror="this.src='/uploads/none.jpg'">
                        </span>

                        <div class="mailbox-attachment-info">
                            <a href="#" class="mailbox-attachment-name"><i
                                    class="fa fa-heart text-danger"></i> {{$row->name}}</a>
                            <span class="mailbox-attachment-size">
                            <div class="text-danger text-bold"> {{number_format($row->price)}} ៛ </div>
                            <div class="text-blue text-bold">In stock</div>
                        </span>
                        </div>
                    </li>

                @endforeach

            </ul>
        </div>

    @endforeach

@endsection

@push('scripts')
    <script>
        $(function () {
            $(document).on('click', 'img', function () {
                $('#modal-avatar').modal('show');
                $('#modal-avatar img').attr('src', $(this).attr('src'));
            });
        })
    </script>
@endpush



