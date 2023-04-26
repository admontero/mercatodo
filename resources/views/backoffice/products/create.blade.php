@extends('layouts.backoffice.app')

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="/admin/products">
            {{ __('Products') }}
        </a>
    </li>
    <li class="breadcrumb-item active">
        {{ __('Creating Form') }}
    </li>
@endsection

@section('header')
    <h1 class="h4 text-primary">{{ __('Creating Form') }}</h1>
@endsection

@section('content')
    <product-form></product-form>
@endsection
