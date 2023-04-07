@extends('layouts.backoffice.app')

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="/admin/categories">
            {{ __('Categories') }}
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
    <category-form :category="{{ json_encode($category) }}"></category-form>
@endsection
