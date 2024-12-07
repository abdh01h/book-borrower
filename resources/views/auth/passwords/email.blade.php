@extends('auth.layout')
@section('page_title', __('Forgot your password!'))

@section('content')
    <div class="card-body login-card-body">
        <p class="login-box-msg">{{ __('Forgot your password!') }}</p>

        <form id="forgot-password-form" action="{{ route('password.email') }}" method="post">
            @csrf
            <div class="input-group mb-3">
                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" placeholder="{{ __('Email Address') }}" value="{{ old('email') }}" required autocomplete="email" autofocus required>
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-envelope"></span>
                    </div>
                </div>

                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="row">
                <div class="col-12">
                    <button type="submit" class="btn btn-primary btn-block btn_submit">
                        {{ __('Send Password Reset Link') }}
                    </button>
                </div>
            </div>
        </form>

        <p class="mt-3 mb-1">
            <a href="{{ route('login') }}">{{ __('Login') }}</a>
        </p>
    </div>
@endsection
