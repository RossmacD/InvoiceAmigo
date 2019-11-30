@extends('layouts.app')
@section('content')
<h2 class="">Create Invoice</h2>
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
                    <option value="eur" {{$user->default_currency=="eur"? 'selected':''}}>€ - Euro</option>
                    <option value="gbp" {{$user->default_currency=="gbp"? 'selected':''}}>£ - Pound Sterling</option>
                    <option value="usd" {{$user->default_currency=="usd"? 'selected':''}}>$ - U.S Dollar</option>
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
    <h2>Products</h2>
    <input type="hidden" id="formCount" name="formCount" value="0" />
    <p>{{old('formCount')}}</p>
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
                        <th scope="col">Options</th>

                    </tr>
                </thead>
                <tbody id="myAddedRows">
                    <script>
                        count=0;
                        for(i=0;i<{{old('formCount',0)}};i++){
                            myTableRowAdd();
                        }

                        
                        function myTableRowAdd(){
                             newInnerHTML='<tr><th scope="row">'+(count+1)+'</th><td><input type="type" name="product['+count+'][name]" id="product['+count+'][name]" placeholder="Product Name"class="form-control {{ $errors->has("product.'+count+'.name") ? "is-invalid" : "" }}"value="{{ old("product.'+count+'.name") }}">@if($errors->has("product.'+count+'.name"))<span class="invalid-feedback">{{ $errors->first("product.'+count+'.name") }}</span>@endif</td><td><input type="type" name="product['+count+'][description]" id="product['+count+'][description]" placeholder="Product Description"class="form-control {{ $errors->has("product.'+count+'.description") ? "is-invalid" : "" }}"value="{{ old("product.'+count+'.description")}}">@if($errors->has("product.'+count+'.description"))<span class="invalid-feedback">{{ $errors->first("product.'+count+'.description") }}</span>@endif</td><td><input type="number" name="product['+count+'][quantity]" id="product['+count+'][quantity]"class="form-control {{ $errors->has("product.'+count+'.quantity") ? "is-invalid" : "" }}"value="{{ old("product.'+count+'.quantity")}}" placeholder="0">@if($errors->has("product.'+count+'.quantity"))<span class="invalid-feedback">{{ $errors->first("product.'+count+'.quantity") }}</span>@endif</td><td><input type="number" name="product['+count+'][cost]" id="product['+count+'][cost]"class="form-control {{ $errors->has("product.'+count+'.cost") ? "is-invalid" : "" }}"value="{{ old("product.'+count+'.cost") }}" placeholder="0">@if($errors->has("product.'+count+'.cost"))<span class="invalid-feedback">{{ $errors->first("product.'+count+'.cost") }}</span>@endif</td></tr>';
                             $('#myAddedRows').append(newInnerHTML);
                            count++;
                            //$('#formCount').val(count);
                        }
                        
                    </script>


                    <tr id="invoiceItem0">
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
                        <td>
                            <input type="checkbox" name="product[0][save]" id="product[0][save]" value="save_as_product"/>Save
                            <button type="button" class="btn btn-danger btn-sm">Delete</button>
                        </td>
                    </tr>
                </tbody>
            </table>
            <button class="btn btn-success" onclick="myTableRowAdd();return false;">+</button>
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
    <div class="row">
        <div class="col-md-3">
            <div class="form-group">
                <label for="client_email">Client Email</label>
                <input type="email" name="client_email" id="client_email"
                    class="form-control {{ $errors->has('client_email') ? 'is-invalid' : '' }}"
                    value="{{ old('client_email') }}" placeholder="Client Email">
                @if($errors->has('client_email'))
                <span class="invalid-feedback">
                    {{ $errors->first('client_email') }}
                </span>
                @endif
            </div>
        </div>
    </div>
    <button type="submit" class="btn btn-primary">Create</button>
</form>
@endsection