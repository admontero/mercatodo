@extends('layouts.backoffice.app')

@section('breadcrumb')
    <li class="breadcrumb-item active">{{ __('Customers') }}</li>
@endsection

@section('header')
    <h1 class="h4 text-primary">{{ __('Customers') }}</h1>
@endsection

@section('content')
    <customer-list></customer-list>
@endsection
