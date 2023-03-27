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
    @include('partials.header', ['title' => __('Editing Form')])
@endsection

@section('content')
    <customer-form :customer="{{ json_encode($customer) }}"></customer-form>
@endsection
