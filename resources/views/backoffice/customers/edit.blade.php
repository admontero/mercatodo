@extends('layouts.backoffice.app')

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="/admin/customers">
            Customers
        </a>
    </li>
    <li class="breadcrumb-item active">
        Formulario de Edición
    </li>
@endsection

@section('header')
    @include('partials.header', ['title' => 'Formulario de Edición'])
@endsection

@section('content')
    <customer-form :customer="{{ json_encode($customer) }}"></customer-form>
@endsection
