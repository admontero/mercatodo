@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-0">
                        <div class="row no-gutters">
                            <div class="p-5">
                                <div class="mb-2">
                                    <h3 class="h4 font-weight-bold text-primary">
                                        {{ __('Confirm Password') }}
                                    </h3>
                                </div>

                                <p class="text-muted mt-2 mb-5">
                                    {{ __('Please confirm your password before continuing.') }}
                                </p>

                                <form method="POST" action="{{ route('password.confirm') }}">
                                    @csrf

                                    <div class="mb-4">
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

                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Confirm Password') }}
                                    </button>

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
