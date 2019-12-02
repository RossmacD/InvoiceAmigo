@extends('layouts.app')
@section('content')

{{-- <script>
  $( function() {
    var availableTags = [
      "ActionScript",
      "AppleScript",
      "Asp",
      "BASIC",
      "C",
      "C++",
      "Clojure",
      "COBOL",
      "ColdFusion",
      "Erlang",
      "Fortran",
      "Groovy",
      "Haskell",
      "Java",
      "JavaScript",
      "Lisp",
      "Perl",
      "PHP",
      "Python",
      "Ruby",
      "Scala",
      "Scheme"
    ];
    $( "#client_email" ).autocomplete({
      source: availableTags
    });
  } );
  </script>  --}}

<script>

    //Generate Invoice Lines
    count=0;
    removed=0;
    addedIds=[];

    function myTableRowAdd(){
        newInnerHTML=`
        <tr id="invoiceItem${count}">
            <th scope="row">${count+1}</th>
            <td>
                <input type="type" name="product[${count}][name]" id="product${count}name" placeholder="Product Name" class="form-control">
            </td>
            <td>
                <input type="type" name="product[${count}][description]" id="product${count}description" placeholder="Product Description" class="form-control">
            </td>
            <td>
                <input type="number" name="product[${count}][quantity]" id="product${count}quantity" class="form-control" value="1" placeholder="Qty">
            </td>
            <td>
                <input type="number" name="product[${count}][cost]" id="product${count}cost" class="form-control" placeholder="Cost">
            </td>
            <td>
                <input type="checkbox" name="product[${count}][save]" id="product${count}save" value="save_as_product" />Save
                <button type="button" class="btn btn-danger btn-sm" onclick="myTableDelete(${count})">Delete</button>
            </td>
        </tr>
        `;
        addedIds.push(count);
        $('#myAddedRows').append(newInnerHTML);
        //Search Ajax for product by name
        $(document).ready(function() {
            src = "{{ route('searchajax') }}";
            $(`#product${count}name`).autocomplete({
                    source: function(request, response) {
                    $.ajax({
                        url: src,
                        dataType: "json",
                        data: {
                        term : request.term
                    },
                    success: function(data) {
                    response(data);
                }
            });
        },
            minLength: 0,
            delay: 0,
            }).blur(function(){
                $(this).autocomplete('enable');
            })
            .focus(function () {
                $(this).autocomplete('search', '');
            });;
        });
        count++;
    }

    function myTableDelete(_id){
        $( `#invoiceItem${_id}` ).remove();
            //addedIds.remove(_id);
        addedIds = jQuery.grep(addedIds, function(value) {
            return value != _id;
        });
    }


    function appendSubmit(){
        for(i=0;i<addedIds.length;i++){
            $(invoiceForm).append(`<input type="hidden" name="addedId[${i}]" value="${addedIds[i]}" />`);
        }
    return true;
    }
</script>


<h2 class="">Create Invoice</h2>
<form action="{{ route('invoices.store') }}" method="post" id="invoiceForm">
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


    <input type="hidden" id="formCount" name="formCount" value="0" />
    <p>{{old('formCount')}}</p>
    {{-- Invoice Item generation --}}
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
                        <th scope="col">Options</th>

                    </tr>
                </thead>
                <tbody id="myAddedRows">
                   
                    @if(old('addedId') !== null)
                        @foreach (old('addedId') as $itemId)
                <tr id="invoiceItem{{$itemId}}">
                            <th scope="row">{{$itemId+1}}</th>
                            <td>
                                <input type="type" name="product[{{$itemId}}][name]" id="product{{$itemId}}name" placeholder="Product Name"
                                    class="form-control {{ $errors->has('product.'.$itemId.'.name') ? 'is-invalid' : '' }}"
                                    value="{{ old('product.'.$itemId.'.name') }}">
                                @if($errors->has('product.'.$itemId.'.name'))
                                <span class="invalid-feedback">
                                    {{ $errors->first('product.'.$itemId.'.name') }}
                                </span>
                                @endif
                            </td>
                            <td>
                                <input type="type" name="product[{{$itemId}}][description]" id="product{{$itemId}}description" placeholder="Product Description"
                                    class="form-control {{ $errors->has('product.'.$itemId.'.description') ? 'is-invalid' : '' }}"
                                    value="{{ old('product.'.$itemId.'.description')}}">
                                @if($errors->has('product.'.$itemId.'.description'))
                                <span class="invalid-feedback">
                                    {{ $errors->first('product.'.$itemId.'.description') }}
                                </span>
                                @endif
                            </td>
                            <td>
                                <input type="number" name="product[{{$itemId}}][quantity]" id="product{{$itemId}}quantity"
                                    class="form-control {{ $errors->has('product.'.$itemId.'.quantity') ? 'is-invalid' : '' }}"
                                    value="{{ old('product.'.$itemId.'.quantity')}}" placeholder="0">
                                @if($errors->has('product.'.$itemId.'.quantity'))
                                <span class="invalid-feedback">
                                    {{ $errors->first('product.'.$itemId.'.quantity') }}
                                </span>
                                @endif
                            </td>
                            <td>
                                <input type="number" name="product[{{$itemId}}][cost]" id="product{{$itemId}}cost"
                                    class="form-control {{ $errors->has('product.'.$itemId.'.cost') ? 'is-invalid' : '' }}"
                                    value="{{ old('product.'.$itemId.'.cost') }}" placeholder="0">
                                @if($errors->has('product.'.$itemId.'.cost'))
                                <span class="invalid-feedback">
                                    {{ $errors->first('product.'.$itemId.'.cost') }}
                                </span>
                                @endif
                            </td>
                        
                        
                            <td>
                                <input type="checkbox" name="product[{{$itemId}}][save]" id="product{{$itemId}}save" value="save_as_product" />Save
                                <button type="button" class="btn btn-danger btn-sm" onclick="myTableDelete({{$itemId}})">Delete</button>
                            </td>
                        </tr> 
                        <script>
                            addedIds.push(count);
                            count++;
                        </script>
                        @endforeach
                    @endif
                    
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
    <button type="submit" class="btn btn-primary"onclick="appendSubmit()">Create</button>
</form>





@endsection