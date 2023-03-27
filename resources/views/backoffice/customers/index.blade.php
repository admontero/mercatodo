@extends('layouts.backoffice.app')

@section('breadcrumb')
    <li class="breadcrumb-item active">{{ __('Customers') }}</li>
@endsection

@section('header')
    @include('partials.header', ['title' => __('Customers')])
@endsection

@section('content')
    <customer-list></customer-list>
@endsection
