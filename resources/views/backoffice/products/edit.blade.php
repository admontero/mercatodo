@extends('layouts.backoffice.app')

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="/admin/products">
            {{ __('Products') }}
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
    <product-form :product-slug="{{ json_encode($product->slug) }}"></product-form>
@endsection
