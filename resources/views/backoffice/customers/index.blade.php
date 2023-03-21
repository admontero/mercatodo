@extends('layouts.backoffice.app')

@section('header')
    @include('partials.header', ['title' => 'Customers'])
@endsection

@section('content')
    <customer-list></customer-list>
@endsection
