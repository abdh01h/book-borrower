@extends('auth.layout')
@section('page_title', __('Log In'))

@section('content')
    <div class="card-body login-card-body">
        <p class="login-box-msg">{{ __('Log In') }}</p>

        <form id="login-form" action="{{ route('login') }}" method="post">
            @csrf
            <div class="input-group mb-3">
                <input type="email" id="email" name="email" placeholder="{{ __('Email Address') }}" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required autocomplete="email" autofocus>
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
            <div class="input-group mb-3">
                <input type="password" class="form-control" id="password" name="password" placeholder="{{ __('Password') }}" @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-lock"></span>
                    </div>
                </div>
                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="icheck-primary">
                        <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                        <label for="remember">
                            {{ __('Remember Me') }}
                        </label>
                    </div>
                </div>
            </div>

            <div class="my-4">
                <button type="submit" class="btn btn-primary btn-block btn_submit">{{ __('Log In') }}</button>
            </div>
        </form>
        @if (Route::has('password.request'))
            <p class="mb-1">
                <a href="{{ route('password.request') }}">{{ __('Forgot your password?') }}</a>
            </p>
        @endif
        @if (Route::has('register'))
            <p class="mb-0">
                <a href="{{ route('register') }}" class="text-center">{{ __('Register a new account') }}</a>
            </p>
        @endif
    </div>
@endsection

