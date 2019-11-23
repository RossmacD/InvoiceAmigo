@extends('layouts.app')
@section('content')
<h3 class="text-center">Create Invoice</h3>
<form action="{{ route('invoices.store') }}" method="post">
    @csrf
    <div class="row">
        <div class="col-md-3">
            <div class="form-group">
                <label for="invoice_number">Invoice Number</label>
                <input type="number" name="invoice_number" id="invoice_number"
                    class="form-control {{ $errors->has('invoice_number') ? 'is-invalid' : '' }}"
                    value="{{ old('invoice_number') }}" placeholder="0">
                @if($errors->has('invoice_number'))
                <span class="invalid-feedback">
                    {{ $errors->first('invoice_number') }}
                </span>
                @endif
            </div>
        </div>

        <div class="col-md-3">
            <div class="form-group">
                <label for="currency">Currency</label>

                <select name="currency" id="currency"
                    class="form-control {{ $errors->has('currency') ? 'is-invalid' : '' }}"
                    value="{{ old('currency') }}">
                    <option value="eur" selected>€ - Euro</option>
                    <option value="gbp">£ - Pound Sterling</option>
                    <option value="usd">$ - U.S Dollar</option>
                </select>
                @if($errors->has('currency'))
                <span class="invalid-feedback">
                    {{ $errors->first('currency') }}
                </span>
                @endif
            </div>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col">
            <div class="form-group">
                <label for="invoice_date">Invoice Date</label>
                <input type="date" name="invoice_date" id="invoice_date"
                    class="form-control {{ $errors->has('invoice_date') ? 'is-invalid' : '' }}"
                    value="{{ old('invoice_date',date('mm-dd-yyyy')) }}">
                @if($errors->has('invoice_date'))
                <span class="invalid-feedback">
                    {{ $errors->first('invoice_date') }}
                </span>
                @endif
            </div>
        </div>
        <div class="col">
            <div class="form-group">
                <label for="due_date">Due Date</label>
                <input type="date" name="due_date" id="due_date"
                    class="form-control {{ $errors->has('due_date') ? 'is-invalid' : '' }}"
                    value="{{ old('due_date') }}">
                @if($errors->has('due_date'))
                <span class="invalid-feedback">
                    {{ $errors->first('due_date') }}
                </span>
                @endif
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th scope="col">Product</th>
                        <th scope="col">Name</th>
                        <th scope="col">Description</th>
                        <th scope="col">Quantity</th>
                        <th scope="col">Cost</th>

                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th scope="row">1</th>
                        <td>
                            <input type="type" name="product[0][name]" id="product[0][name]" placeholder="Product Name"
                                class="form-control {{ $errors->has('product.0.name') ? 'is-invalid' : '' }}"
                                value="{{ old('product.0.name') }}">
                            @if($errors->has('product.0.name'))
                            <span class="invalid-feedback">
                                {{ $errors->first('product.0.name') }}
                            </span>
                            @endif
                        </td>
                        <td>
                            <input type="type" name="product[0][description]" id="product[0][description]"
                                placeholder="Product Description"
                                class="form-control {{ $errors->has('product.0.description') ? 'is-invalid' : '' }}"
                                value="{{ old('product.0.description')}}">
                            @if($errors->has('product.0.description'))
                            <span class="invalid-feedback">
                                {{ $errors->first('product.0.description') }}
                            </span>
                            @endif
                        </td>
                        <td>
                            <input type="number" name="product[0][quantity]" id="product[0][quantity]"
                                class="form-control {{ $errors->has('product.0.quantity') ? 'is-invalid' : '' }}"
                                value="{{ old('product.0.quantity')}}" placeholder="0">
                            @if($errors->has('product.0.quantity'))
                            <span class="invalid-feedback">
                                {{ $errors->first('product.0.quantity') }}
                            </span>
                            @endif
                        </td>
                        <td>
                            <input type="number" name="product[0][cost]" id="product[0][cost]"
                                class="form-control {{ $errors->has('product.0.cost') ? 'is-invalid' : '' }}"
                                value="{{ old('product.0.cost') }}" placeholder="0">
                            @if($errors->has('product.0.cost'))
                            <span class="invalid-feedback">
                                {{ $errors->first('product.0.cost') }}
                            </span>
                            @endif
                        </td>

                    </tr>
                    <tr>
                        <td><button class="btn btn-success">+</button></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    @isset($errors)
                    {{-- {{dd($errors)}} --}}
                    @endisset
                </tbody>
            </table>
        </div>
    </div>
    <hr>
    <div class="form-group">
        <label for="note">Invoice Notes</label>
        <textarea name="note" id="note" rows="4" class="form-control {{ $errors->has('note') ? 'is-invalid' : '' }}"
            placeholder="Enter Note">{{ old('note') }}</textarea>
        @if($errors->has('note')) {{-- <-check if we have a validation error --}}
        <span class="invalid-feedback">
            {{ $errors->first('note') }} {{-- <- Display the First validation error --}}
        </span>
        @endif
    </div>
    <button type="submit" class="btn btn-primary">Create</button>

</form>
@endsection