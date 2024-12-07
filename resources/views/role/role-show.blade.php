@extends('layouts.main')
@section('page_title', ucfirst($role->name))

@section('breadcrumb')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0">{{ ucfirst($role->name) }}</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Dashboard') }}</a></li>
                <li class="breadcrumb-item"><a href="#">{{ __('Users & Roles') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('roles.index') }}">{{ __('Role Management') }}</a></li>
                <li class="breadcrumb-item active">{{ ucfirst($role->name) }}</li>
            </ol>
        </div>
    </div>
@endsection

@section('content')
    <div class="row card card-primary card-outline">
        <div class="card-header">
            <h3 class="card-title">{{ ucfirst($role->name) }}</h3>
        </div>
        <div class="col-sm-12 card-body">
            <div class="d-flex flex-row-reverse">
                @can('user.update')
                    @if($role->id != 1)
                        <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-primary" data-toggle="tooltip" data-placement="left" title="{{ __('Edit role') }}">
                            <i class="far fa-edit mr-1"></i>
                            {{ __('Edit') }}
                        </a>
                    @endif
                @endcan
            </div>
            <table class="table table-hover">
                <tbody>
                    <tr>
                        <td class="border-0">
                            <strong>{{ __('Role name') }}:</strong>
                            {{ $role->name  }}
                        </td>
                    </tr>
                    <tr data-widget="expandable-table" aria-expanded="true">
                        <td>
                            <i class="expandable-table-caret fas fa-caret-right fa-fw"></i>
                            {{ __('Role Permissions') }}
                        </td>
                    </tr>
                    <tr class="expandable-body">
                        <td>
                            <div class="p-0">
                                <table class="table table-hover">
                                    <tbody>
                                        @forelse ($permissions as $key => $module)
                                            <tr data-widget="expandable-table" aria-expanded="false">
                                                <td class="text-capitalize">
                                                    <i class="expandable-table-caret fas fa-caret-right fa-fw"></i>
                                                    {{ $key }}
                                                </td>
                                            </tr>
                                            <tr class="expandable-body d-none">
                                                <td>
                                                    <div class="p-0">
                                                        <table class="table table-hover">
                                                            <tbody>
                                                                @forelse ($module as $permission)
                                                                    <tr>
                                                                        <td class="text-capitalize">
                                                                            {{ $permission['view_name'] }}
                                                                        </td>
                                                                    </tr>
                                                                @empty
                                                                    <tr>
                                                                        <td>
                                                                            {{ __('No permissions!') }}
                                                                        </td>
                                                                    </tr>
                                                                @endforelse
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr class="expandable-body d-none">
                                                <td>
                                                    {{ __('No permissions!') }}
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>
    </div>
@endsection

