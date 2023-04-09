@extends('layouts.app')

@section('content')
    <div id="main-wrapper" class="container">
        <div class="row justify-content-center">
            <div class="col-lg-7 d-none d-lg-inline-block">
                <img class="img-fluid" src="{{ asset('images/illustrations/login.svg') }}" alt="ecommerce-image">
            </div>

            <div class="col-lg-5">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-0">
                        <div class="row no-gutters">
                            <div class="px-5 py-4">
                                <div class="mb-4">
                                    <h3 class="h4 font-weight-bold text-primary">
                                        {{ __('Login') }}
                                    </h3>
                                </div>

                                <h6 class="h5 mb-0">{{ __('Welcome back!') }}</h6>
                                <p class="text-muted mt-2 @if(!session('error')) mb-5 @else mb-2 @endif">{{ __('Enter your email address and password to access.') }}</p>

                                @if(session('error'))
                                    <div class="alert alert-danger">
                                        {{ session('error') }}
                                    </div>
                                @endif

                                <form method="POST" action="{{ route('login') }}">
                                    @csrf

                                    <div class="mb-3">
                                        <input
                                            class="form-control @error('email') is-invalid @enderror"
                                            id="email"
                                            type="email"
                                            name="email"
                                            value="{{ old('email') }}"
                                            autocomplete="email"
                                            placeholder="{{ __('Email Address') }}"
                                            autofocus
                                        >
                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <input
                                            class="form-control @error('password') is-invalid @enderror"
                                            id="password"
                                            type="password"
                                            name="password"
                                            placeholder="{{ __('Password') }}"
                                            autocomplete="current-password"
                                        >
                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="mb-5">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                            <label class="form-check-label" for="remember">
                                                {{ __('Remember Me') }}
                                            </label>
                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn-primary">{{ __('Login') }}</button>

                                    @if (Route::has('password.request'))
                                        <a
                                            class="forgot-link float-end text-primary"
                                            href="{{ route('password.request') }}"
                                        >
                                            {{ __('Forgot Your Password?') }}
                                        </a>
                                    @endif
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
