@extends('layouts.main')
@section('page_title', 'Dashboard')

@section('content')
    <div class="card card-primary card-primary card-outline">
        <div class="card-header">
            <h3 class="card-title">{{ __('Dashboard') }}</h3>
        </div>
        <div class="card-body">
            <p>{{ __('Welecome back') }} {{ Auth::User()->name }}!</p>
        </div>
    </div>
@endsection

