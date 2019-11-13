@extends('layouts.app')
@section('content')
    <h2 class="">Product Name: {{$product->product_name}}</h2>
    <hr>
    <h4>Product Description:</h4><p> {{$product->product_description}}</p>
    <h4>Cost:</h4><p>{{$product->product_cost}}</p>
    <hr>
    <br>
    <a href="{{route('products.edit',$product->id)}}" class="btn btn-primary float-left">Update</a>
    <a href="#" class="btn btn-danger float-right" data-toggle="modal" data-target="#delete-modal">Delete</a>
    <div class="clearfix"></div>
    <div class="modal fade" id="delete-modal">
        <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete Product</h5>
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
    <form method="POST" id="delete-form" action="{{route('products.destroy',$product->id)}}">
        @csrf
        @method('DELETE')
    </form>
@endsection
