@extends('layouts.backoffice.app')

@section('breadcrumb')
    <li class="breadcrumb-item active">{{ __('Reports') }}</li>
@endsection

@section('header')
    <h1 class="h4 text-primary">{{ __('Reports') }}</h1>
@endsection

@section('content')
   <report-list></report-list>
@endsection
