@extends('auth.layout')
@section('page_title', __('Confirm Password'))

@section('content')
    <div class="card-body login-card-body">
        <p class="login-box-msg">{{ __('Confirm Password') }}</p>

        <form id="confirm-email-form" action="{{ route('password.confirm') }}" method="post">
            @csrf
            <div class="input-group mb-3">
                <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" id="password" placeholder="{{ __('Password') }}" required autofocus>
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
                    <button type="submit" class="btn btn-primary btn-block btn_submit">
                        {{ __('Confirm Password') }}
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection
