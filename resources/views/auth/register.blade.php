@extends('auth.layout')
@section('page_title', __('Register'))

@section('content')
    <div class="card-body register-card-body">
        <p class="login-box-msg">{{ __('Register a new account') }}</p>

        <form id="register-form" action="{{ route('register') }}" method="post">
            @csrf
            <div class="input-group mb-3">
                <input type="text" name="name" class="form-control  @error('name') is-invalid @enderror" placeholder="{{ __('Full name') }}" value="{{ old('name') }}" required autocomplete="name" autofocus>
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-user"></span>
                    </div>
                </div>
                @error('name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="input-group mb-3">
                <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" placeholder="{{ __('Email Address') }}" value="{{ old('email') }}" required autocomplete="email">
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
                <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" placeholder="{{ __('Password') }}" required>
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
            <div class="input-group mb-3">
                <input type="password" name="password_confirmation" class="form-control" placeholder="{{ __('Confirm Password') }}" required>
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-lock"></span>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="icheck-primary">
                        <input type="checkbox" id="agree-terms" name="terms" required>
                        <label for="agree-terms">
                            {{ __('I agree to the') }} <a href="#">{{ __('terms and conditions') }}</a>
                        </label>
                    </div>
                </div>
            </div>
            <div class="my-4">
                <button type="submit" class="btn btn-primary btn-block btn_submit">{{ __('Register') }}</button>
            </div>
        </form>

        <a href="{{ route('login') }}" class="text-center">{{ __('I already have an account') }}</a>
    </div>
@endsection

