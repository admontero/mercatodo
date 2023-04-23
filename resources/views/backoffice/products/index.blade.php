@extends('layouts.backoffice.app')

@section('breadcrumb')
    <li class="breadcrumb-item active">{{ __('Products') }}</li>
@endsection

@section('header')
    <h1 class="h4 text-primary">{{ __('Products') }}</h1>
@endsection

@section('content')
    <admin-product-list></admin-product-list>
@endsection
