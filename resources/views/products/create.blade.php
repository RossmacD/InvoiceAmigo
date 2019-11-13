@extends('layouts.app')
@section('content')
    <h3 class="text-center">Create Product</h3>
    <form action="{{ route('products.store') }}" method="post">
        @csrf
        <div class="form-group">
            <label for="product_name">Product Name</label>
            <input type="text" name="product_name" id="product_name" class="form-control {{ $errors->has('product_name') ? 'is-invalid' : '' }}" value="{{ old('product_name') }}">
            @if($errors->has('product_name'))
                <span class="invalid-feedback">
                    {{ $errors->first('product_name') }}
                </span>
            @endif
        </div>
        <div class="form-group">
            <label for="product_description">Product Description</label>
            <input type="text" name="product_description" id="product_description" class="form-control {{ $errors->has('product_description') ? 'is-invalid' : '' }}" value="{{ old('product_description') }}" >
            @if($errors->has('product_description'))
                <span class="invalid-feedback">
                    {{ $errors->first('product_description') }}
                </span>
            @endif
        </div>
        <div class="form-group">
            <label for="product_cost">Product Cost</label>
            <input type="number" name="product_cost" id="product_cost" class="form-control {{ $errors->has('product_cost') ? 'is-invalid' : '' }}" value="{{ old('product_cost') }}" >
            @if($errors->has('product_cost'))
                <span class="invalid-feedback">
                    {{ $errors->first('product_cost') }}
                </span>
            @endif
        </div>

        <button type="submit" class="btn btn-primary">Create</button>
    </form>
@endsection
