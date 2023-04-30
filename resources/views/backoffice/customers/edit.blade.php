@extends('layouts.backoffice.app')

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="/admin/customers">
            {{ __('Customers') }}
        </a>
    </li>
    <li class="breadcrumb-item active">
        {{ __('Editing Form') }}
    </li>
@endsection

@section('header')
    <h1 class="h4 text-primary">{{ __('Editing Form') }}</h1>
@endsection

@section('content')
    <customer-form :customer-id="{{ json_encode($customer->id) }}"></customer-form>
@endsection
