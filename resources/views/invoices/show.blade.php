@extends('layouts.app')
@section('content')
    <h2 class="">Invoice Number: {{$invoice->invoice_number}}</h2>
    <hr>
    <h4>Invoice date:</h4><p> {{$invoice->invoice_date}}</p>
    <h4>Due date:</h4><p>{{$invoice->due_date}}</p>
    <hr>
    <h5>Current Status:</h5><p>{{$invoice->status}}</p>
    <h5>Currency:</h5><p>{{$invoice->currency}}</p>
    <hr>
    <h3>Notes:</h3>
    <p>{{$invoice->note}}</p>
    <br>
    <script src="https://cdn.jsdelivr.net/gh/davidshimjs/qrcodejs/qrcode.min.js"></script>
    <div id="qrcode"></div>
    <script type="text/javascript">
        new QRCode(document.getElementById("qrcode"), {
                text: `http://192.168.5.207:8000/pay/{{$invoice->id}}`,
                width: 100,
                height: 100,
                colorDark : "#000000",
                colorLight : "#ffffff",
                correctLevel : QRCode.CorrectLevel.H
                });
    </script>
    <style>
        #qrcode {
            width: 100px;
            height: 100px;
            margin-top: 15px;
        }
    </style>



    <a href="{{route('invoices.edit',$invoice->id)}}" class="btn btn-primary float-left">Update</a>
    <a href="#" class="btn btn-danger float-right" data-toggle="modal" data-target="#delete-modal">Delete</a>
    <div class="clearfix"></div>
    <div class="modal fade" id="delete-modal">
        <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete Invoice</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure!</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" onclick="document.querySelector('#delete-form').submit()">Proceed</button>
                <button type="button" class="btn btn-primary" data-dismiss="modal">Cancel</button>
            </div>
        </div>
        </div>
    </div>
    <form method="POST" id="delete-form" action="{{route('invoices.destroy',$invoice->id)}}">
        @csrf
        @method('DELETE')
    </form>
@endsection