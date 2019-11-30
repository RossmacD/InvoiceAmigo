@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        
        <div class="col-md-8 col-md-offset-2">
            <div class="card">
                <div class="card-header">Dashboard</div>
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    You are logged in!
                </div>
            </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col"></div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">Info:</div>
                    <div class="card-body">
                        <h6>Name:</h6><p>{{$user->name}}</p> 
                        <h6>Email:</h6>
                        <p>{{$user->email}}</p>
                                    
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">Settings:</div>
                    <div class="card-body">
                        Currency:
            
                    </div>
                </div>
            </div>
            <div class="col"></div>
        </div>
</div>
@endsection
