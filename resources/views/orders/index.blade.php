@extends('layouts.app')

@section('content')
    <div class="container-fluid max-w-3xl">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-1 border-bottom mb-3">
            <h1 class="h4 text-primary">{{ __('My Orders') }}</h1>
        </div>
        <customer-order-list></customer-order-list>
    </div>
@endsection
