@extends('layouts.app')

@section('content')
    <h2 class="text-center">All Invoices</h2>
    <a href="{{route('invoices.create')}}" class="btn btn-primary ">New Invoice</a>
    <ul class="list-group py-3 mb-3">
        @forelse($invoices as $invoice)
            <li class="list-group-item my-2">
                <h5>{{ $invoice->invoice_number }}</h5>
                <p>{{ Str::limit($invoice->note,10) }}</p>
                <small class="float-right">{{ $invoice->created_at->diffForHumans() }}</small>
                <a href="{{route('invoices.show',$invoice->id)}}">Read More</a>
            </li>
        @empty
            <h4 class="text-center">No Invoices Found!</h4>
        @endforelse
    </ul>
    <div class="d-flex justify-content-center">
        {{ $invoices->links() }}
    </div>
@endsection