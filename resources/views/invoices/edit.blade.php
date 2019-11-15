@extends('layouts.app')
@section('content')
    <h3 class="text-center">Edit Todo</h3>
    <form action="{{ route('invoices.update',$invoice->id) }}" method="post">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="invoice_number">Invoice Number</label>
            <input type="number" name="invoice_number" id="invoice_number" class="form-control {{ $errors->has('invoice_number') ? 'is-invalid' : '' }}" value="{{ $invoice->invoice_number }}" placeholder="{{ $invoice->invoice_number }}">
            @if($errors->has('invoice_number'))
                <span class="invalid-feedback">
                    {{ $errors->first('invoice_number') }}
                </span>
            @endif
        </div>
        <div class="form-group">
            <label for="invoice_date">Invoice Date</label>
            <input type="date" name="invoice_date" id="invoice_date" class="form-control {{ $errors->has('invoice_date') ? 'is-invalid' : '' }}" value="{{ $invoice->invoice_date }}" placeholder={{ $invoice->invoice_date }}>
            @if($errors->has('invoice_date'))
                <span class="invalid-feedback">
                    {{ $errors->first('invoice_date') }}
                </span>
            @endif
        </div>
        <div class="form-group">
            <label for="due_date">Due Date</label>
            <input type="date" name="due_date" id="due_date" class="form-control {{ $errors->has('due_date') ? 'is-invalid' : '' }}" value="{{ $invoice->due_date }}" placeholder="{{ $invoice->due_date }}">
            @if($errors->has('due_date'))
                <span class="invalid-feedback">
                    {{ $errors->first('due_date') }}
                </span>
            @endif
        </div>
        <div class="form-group">
            <label for="currency">Currency</label>
            <select name="currency" id="currency" class="form-control {{ $errors->has('currency') ? 'is-invalid' : '' }}"  >
                    <option value="eur" {{$invoice->currency=="eur"? 'selected':''}}>€ - Euro</option>
                    <option value="gbp" {{$invoice->currency=="gbp"?'selected':''}}>£ - Pound Sterling</option>
                    <option value="usd" {{$invoice->currency=="usd"? 'selected':''}}>$ - U.S Dollar</option>
            </select>
            @if($errors->has('currency'))
                <span class="invalid-feedback">
                    {{ $errors->first('currency') }}
                </span>
            @endif
        </div>
        <div class="form-group">
            <label for="note">Invoice Notes</label>
            <textarea name="note" id="note" rows="4" class="form-control {{ $errors->has('note') ? 'is-invalid' : '' }}" placeholder="{{ $invoice->note  }}" value="{{ $invoice->note  }}"> </textarea>
            @if($errors->has('note')) {{-- <-check if we have a validation error --}}
                <span class="invalid-feedback">
                    {{ $errors->first('note') }} {{-- <- Display the First validation error --}}
                </span>
            @endif
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
@endsection