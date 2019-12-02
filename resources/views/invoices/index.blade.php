@extends('layouts.app')

@section('content')

<h2 class="text-center">All Invoices</h2>
<a href="{{route('invoices.create')}}" class="btn btn-primary ">New Invoice</a>
<ul class="list-group py-3 mb-3">
    @forelse($invoices as $invoice)
    <li class="list-group-item my-2"><h5 class="float-right badge badge-{{$invoice->outgoing?'success':'info'}}">
        {{$invoice->outgoing?'Outgoing':'Incoming'}}</h5>
        <h5>Invoice Number: {{ $invoice->invoice_number }} </h5>
        
        <p>{{ Str::limit($invoice->note,10) }}</p>
        <small class="float-right">{{ $invoice->created_at->diffForHumans() }}</small>
        <h6>Total Cost: {{number_format((float)$invoice->total_cost/100, 2, '.', '')}}</h6>
        <a href="{{route('invoices.show',$invoice->id)}}">View</a>
        @if (!$invoice->outgoing)
        <a class="float-right btn btn-success" href="{{route('stripe.paySingleInvoice',$invoice->id)}}">Pay Now</a>  
        @else
        
        @endif
        

        
    </li>
    @empty
    <h4 class="text-center">No Invoices Found!</h4>
    @endforelse
</ul>
<div class="d-flex justify-content-center">
    {{ $invoices->links() }}
</div>
@endsection