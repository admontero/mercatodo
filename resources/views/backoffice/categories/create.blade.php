@extends('layouts.backoffice.app')

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="/admin/categories">
            {{ __('Categories') }}
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
    <category-form></category-form>
@endsection
