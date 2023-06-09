@extends('layouts.checkout')

@section('content')
    <div class="container-fluid">
        <checkout :processors="{{ json_encode($processors) }}"></checkout>
    </div>
@endsection
