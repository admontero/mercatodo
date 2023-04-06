@extends('layouts.backoffice.app')

@section('breadcrumb')
    <li class="breadcrumb-item active">{{ __('Categories') }}</li>
@endsection

@section('header')
    @include('partials.header', ['title' => __('Categories')])
@endsection

@section('content')
    <category-list></category-list>
@endsection
