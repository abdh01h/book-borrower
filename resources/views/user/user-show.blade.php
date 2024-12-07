@extends('layouts.main')
@section('page_title', $user->name)

@section('breadcrumb')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0">{{ $user->name }}</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Dashboard') }}</a></li>
                <li class="breadcrumb-item"><a href="#">{{ __('Users & Roles') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('users.index') }}">{{ __('User Management') }}</a></li>
                <li class="breadcrumb-item active">{{ $user->name }}</li>
            </ol>
        </div>
    </div>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card card-primary card-outline card-outline-tabs">
            <div class="card-header p-0 border-bottom-0"></div>
            <div class="card-body">
                <div class="tab-content" id="custom-tabs-profile">
                    <div class="tab-pane fade show active" id="custom-tabs-profile" role="tabpanel"
                        aria-labelledby="custom-tabs-profile-tab">
                        <h3 class="profile-username text-center">{{ $user->name }}</h3>
                        <p class="text-muted text-center">{{ $user->getRoleNames()[0] }}</p>

                        <div class="text-center mt-2 mb-4">
                            @can('user.update')
                                <a href="{{ route('users.edit', $user->id) }}" class="btn btn-primary btn-sm">
                                    <i class="far fa-edit"></i>
                                    {{ __('Edit User') }}
                                </a>
                            @endcan
                        </div>

                        <ul class="list-group container list-group-unbordered mb-3">
                            <li class="list-group-item">
                                <b>{{ __('ID') }}:</b>
                                <span>{{ $user->id }}</span>
                            </li>
                            <li class="list-group-item">
                                <b>{{ __('Email Address') }}:</b>
                                <span>{{ $user->email }}</span>
                            </li>
                            <li class="list-group-item">
                                <b>{{ __('Registered At') }}:</b>
                                <span>{{ date('j F, Y, g:i A', strtotime($user->created_at)) }}</span>
                            </li>
                        </ul>

                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

