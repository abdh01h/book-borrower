@extends('layouts.main')
@section('page_title', __('Role Management'))

@section('css')
@endsection

@section('breadcrumb')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0">{{ __('Role Management') }}</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Dashboard') }}</a></li>
                <li class="breadcrumb-item"><a href="#">{{ __('Users & Roles') }}</a></li>
                <li class="breadcrumb-item">{{ __('Role Management') }}</li>
            </ol>
        </div>
    </div>
@endsection

@section('content')
    <div class="card card-primary card-outline">
        <div class="card-header">
            <h3 class="card-title">{{ __('Role Management') }}</h3>
            <div class="card-tools">
                <form action="" method="get" class="input-group input-group-sm" style="width: 180px;">
                    <input type="text" name="search" class="form-control float-right" placeholder="Search" value="{{ request('search') }}">
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-default">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <div class="card-body table-responsive">
            <div class="d-flex flex-row-reverse">
                @can('role.create')
                    <a href="{{ route('roles.create') }}" class="btn btn-primary" data-toggle="tooltip"
                        data-placement="left" title="{{ __('Create New Role') }}">
                        <i class="fas fa-plus-circle mr-1"></i>
                        {{ __('Create New Role') }}
                    </a>
                @endcan
            </div>
            <table class="table table-hover table-striped text-nowrap">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ __('Role Name') }}</th>
                        <th>{{ __('Created At') }}</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($roles as $role)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <a href="{{ route('roles.show', $role->id) }}">{{ $role->name }}</a>
                            </td>
                            <td>
                                {{ date('j F, Y, g:i A', strtotime($role->created_at)) }}
                            </td>
                            <td>
                                <div class="input-group-prepend">
                                    <button type="button" class="btn btn-info btn-sm dropdown-toggle"
                                        data-toggle="dropdown" aria-expanded="false">
                                        {{ __('Options') }}
                                    </button>
                                    <div class="dropdown-menu" style="">
                                        <a class="dropdown-item" href="{{ route('roles.show', $role->id) }}">
                                            <i class="far fa-eye mr-1"></i>
                                            {{ __('View') }}
                                        </a>
                                        @can('user.update')
                                            @if ($role->id != 1)
                                                <a class="dropdown-item" href="{{ route('roles.edit', $role->id) }}">
                                                    <i class="far fa-edit mr-1"></i>
                                                    {{ __('Edit') }}
                                                </a>
                                            @endif
                                        @endcan
                                        @can('role.delete')
                                            @if ($role->id != 1)
                                                <button type="submit" class="btn btn-link dropdown-item delete_role_btn">
                                                    <i class="far fa-trash-alt mr-1"></i>
                                                    {{ __('Delete') }}
                                                    <form action="{{ route('roles.destroy', $role->id) }}" method="post"
                                                        class="d-none">
                                                        @csrf
                                                        @method('delete')
                                                    </form>
                                                </button>
                                            @endif
                                        @endcan
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr class="text-center">
                            <td colspan="4">{{ __('No data!') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if(count($roles) > env('PER_PAGE'))
            <div class="card-footer">
                <div class="d-flex justify-content-center">
                    {{ $roles->links('pagination::custom') }}
                </div>
            </div>
        @endif
    </div>
@endsection

@section('javascript')
    <script>
        $(function() {

            $('.delete_role_btn').click(function() {
                Swal.fire({
                    title: "Are you sure?",
                    text: "You won't be able to revert this!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DC3545",
                    cancelButtonColor: "#596268",
                    confirmButtonText: "Yes, delete it!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        let btn = $(this);
                        $(btn).children('form').submit();
                    }
                });
            });

        });
    </script>
@endsection
