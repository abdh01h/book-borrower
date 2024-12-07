@extends('auth.layout')
@section('page_title', __('Verify Your Email Address'))

@section('content')
    <div class="card-body login-card-body">
        <h4 class="login-box-msg">{{ __('Verify Your Email Address') }}</h4>
        @if (session('resent'))
            <h5 class="text-center my-2">{{ __('A fresh verification link has been sent to your email address.') }}</h5>
        @endif
        <div class="text-center">
            {{ __('Before proceeding, please check your email for a verification link.') }}
            {{ __('If you did not receive the email') }},
            <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                @csrf
                <button type="submit" class="btn btn-link p-0 m-0 align-baseline">{{ __('click here to request another') }}</button>.
            </form>
        </div>
    </div>
@endsection

