@extends('layouts.app')
@section('content')
    <h2 class="">Invoice Number: {{$invoice->invoice_number}}</h2>
    <hr>
    <h4>Invoice to:</h4>
    <p>Name: {{$client->name}}</p>
    <p>Email: {{$client->email}}</p>
    <hr>
    <h4>Invoice date:</h4><p> {{$invoice->invoice_date}}</p>
    <h4>Due date:</h4><p>{{$invoice->due_date}}</p>
    <hr>
    <h5>Currency:</h5><p>{{$invoice->currency}}</p>


<h2>Products</h2>
<div class="row">
    <div class="col-md-12">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th scope="col">Num</th>
                    <th scope="col">Name</th>
                    <th scope="col">Description</th>
                    <th scope="col">Quantity</th>
                    <th scope="col">Cost</th>

                </tr>
            </thead>
            <tbody id="myAddedRows">
                @foreach ($invoiceItems as $invoiceItem)
               
                <tr id="invoiceItem{{$loop->index}}">
                    <th scope="row">{{$loop->index+1}}</th>
                    <td>
                        {{$invoiceItem->product_name}}
                        
                    </td>
                    <td>
                        {{$invoiceItem->product_description}}
                        
                    </td>
                    <td>
                        {{$invoiceItem->product_quantity}}
                        
                    </td>
                    <td>
                        {{$invoiceItem->product_cost}}
                        
                    </td>
                @endforeach

            </tbody>
        </table>
        <hr><div class="ml-5">
        <h3>Total Cost:</h3>
    <h4>€{{$invoice->total_cost}}</h4></div>
    <hr>
    @if($invoice->note!==null)
    <h5>Notes:</h5>
    <p>{{$invoice->note}}</p><hr>
    @endif
    
    <script src="https://cdn.jsdelivr.net/gh/davidshimjs/qrcodejs/qrcode.min.js"></script>
    <div class="row justify-content-center">
    <div class="card px-5 py-1">
        
        <div class="card-body">
            <div class="row justify-content-center">
        <h5 class="card-title">Cost: €{{$invoice->total_cost}}</h5>
            </div>
            <p class="card-text"></p>
            <div class="row justify-content-center">
                <div id="qrcode"></div>
           </div>
           <div class="row justify-content-center">
               <h6>Scan to pay</h6>
           </div>
        </div>
    </div>
    
        
        
    </div>
    <script type="text/javascript">
        new QRCode(document.getElementById("qrcode"), {
                text: `http://192.168.5.207:8000/pay/{{$invoice->id}}`,
                width: 150,
                height: 150,
                colorDark : "#000000",
                colorLight : "#ffffff",
                correctLevel : QRCode.CorrectLevel.H
                });
    </script>
    <style>
        #qrcode {
            width: 150px;
            height: 150px;
            margin-top: 15px;
            margin-bottom: 15px;
        }
    </style>
<hr>


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