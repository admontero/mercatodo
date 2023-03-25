@extends('layouts.backoffice.app')

@section('breadcrumb')
    <li class="breadcrumb-item active">Customers</li>
@endsection

@section('header')
    @include('partials.header', ['title' => 'Customers'])
@endsection

@section('content')
    <customer-list></customer-list>
@endsection
