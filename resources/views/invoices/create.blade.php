@extends('layouts.app')
@section('content')
    <h3 class="text-center">Create Todo</h3>
    <form action="{{ route('invoices.store') }}" method="post">
        @csrf
        <div class="form-group">
            <label for="invoice_number">Invoice Number</label>
            <input type="number" name="invoice_number" id="invoice_number" class="form-control {{ $errors->has('invoice_number') ? 'is-invalid' : '' }}" value="{{ old('invoice_number') }}" placeholder="0">
            @if($errors->has('invoice_number'))
                <span class="invalid-feedback">
                    {{ $errors->first('invoice_number') }}
                </span>
            @endif
        </div>
        <div class="form-group">
            <label for="body">Invoice Notes</label>
            <textarea name="body" id="body" rows="4" class="form-control {{ $errors->has('note') ? 'is-invalid' : '' }}" placeholder="Enter Note">{{ old('note') }}</textarea>
            @if($errors->has('note')) {{-- <-check if we have a validation error --}}
                <span class="invalid-feedback">
                    {{ $errors->first('note') }} {{-- <- Display the First validation error --}}
                </span>
            @endif
        </div>
        <button type="submit" class="btn btn-primary">Create</button>
    </form>
@endsection