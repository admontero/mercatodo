@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row g-3">
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-0">
                        <div class="px-4 pt-3">
                            <h3 class="h5 font-weight-bold text-primary">
                                {{ __('Update Information') }}
                            </h3>

                            <customer-form :customer="{{ auth()->user() }}"></customer-form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
