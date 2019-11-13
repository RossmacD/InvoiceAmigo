@extends('layouts.app')

@section('content')
    <h2 class="text-center">All Products</h2>
    <a href="{{route('products.create')}}" class="btn btn-primary ">New Product</a>
    <ul class="list-group py-3 mb-3">
        @forelse($products as $product)
            <li class="list-group-item my-2">
                <h5>{{ $product->product_name }}</h5>
                <h4 class="float-right">€{{ $product->product_cost }} EUR</h4>
                <p>{{ Str::limit($product->product_description,100) }}</p>
                <small class="float-right">{{ $product->created_at->diffForHumans() }}</small>
                <a href="{{route('products.show',$product->id)}}">View Details</a>
            </li>
        @empty
            <h4 class="text-center">No Products Found!</h4>
        @endforelse
    </ul>
    <div class="d-flex justify-content-center">
        {{ $products->links() }}
    </div>
@endsection
