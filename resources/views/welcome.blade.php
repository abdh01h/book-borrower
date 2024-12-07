@extends('auth.layout')
@section('page_title', __('Welcome!'))

@section('content')
    <div class="card-body login-card-body">
        <h3 class="login-box-msg">{{ __('Welcome!') }}</h3>
        <a href="{{ route('login') }}" class="btn btn-primary btn-block mt-2">{{ __('Log In') }}</a>
        @if (Route::has('register'))
            <a href="{{ route('register') }}" class="btn btn-primary btn-block mt-2">{{ __('Register') }}</a>
        @endif
    </div>
@endsection
