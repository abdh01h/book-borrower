@extends('layouts.main')
@section('page_title', __('Edit') . ' ' . ucfirst($role->name))

@section('css')
@endsection

@section('breadcrumb')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0">{{ __('Edit') . ' ' . ucfirst($role->name) }}</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Dashboard') }}</a></li>
                <li class="breadcrumb-item"><a href="#">{{ __('Users & Roles') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('roles.index') }}">{{ __('Role Management') }}</a></li>
                <li class="breadcrumb-item active">{{ __('Edit') . ' ' . ucfirst($role->name) }}</li>
            </ol>
        </div>
    </div>
@endsection


@section('content')
    <div class="card card-primary card-outline">
        <div class="card-header">
            <h3 class="card-title">{{ __('Edit') . ' ' . ucfirst($role->name) }}</h3>
        </div>
        <div class="card-body">
            <form class="row" id="edit_role_form" action="{{ route('roles.update', $role->id) }}" method="post">
                @csrf
                @method('put')
                <div class="col-lg-6 col-sm-12">
                    <div class="form-group">
                        <label>{{ __('Role Name') }}</label>
                        <input type="text" class="form-control" name="role_name" id="role_name" placeholder="{{ __('Role Name') }}" value="{{ $role->name }}" required>
                    </div>
                    <div class="col-sm-12 form-group clearfix mt-2">
                        <div class="row">
                            @foreach ($all_permissions as $key => $module)
                                <div class="col-lg-4 col-sm-12 form-group">
                                    <div class="text-capitalize font-weight-bold mb-2">
                                        {{ $key }}
                                    </div>
                                    @forelse ($module as $permission)
                                        <div class="icheck-success">
                                            @php
                                                $selected = false;
                                                if(isset($assigned_permissions[$key])) {
                                                    foreach($assigned_permissions[$key] as $assigned_permission) {
                                                        if($permission['id'] == $assigned_permission['id']) {
                                                            $selected = true;
                                                        }
                                                    }
                                                }
                                            @endphp
                                            <input type="checkbox" name="permissions[]" id="{{ $permission['view_name'] . '_' . $permission['id'] }}" value="{{ $permission['id'] }}" {{ $selected ? 'checked' : '' }}>
                                            <label class="text-capitalize font-weight-normal" for="{{ $permission['view_name'] . '_' . $permission['id'] }}">
                                                {{ $permission['view_name'] }}
                                            </label>
                                        </div>
                                    @empty
                                        <div class="icheck-success">
                                            <label class="text-capitalize font-weight-normal" for="no_permissions">
                                                {{ __('No permissions!') }}
                                            </label>
                                        </div>
                                    @endforelse
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary btn_submit">
                <i class="far fa-save mr-1"></i>
                {{ __('Save') }}
            </button>
        </div>
    </div>
@endsection

@section('javascript')
    <script>
        $(function() {

            $('#edit_role_form').validate({
                rules: {
                    role_name: {
                        required: true,
                        maxlength: 255,
                    },
                },
                errorElement: 'span',
                errorPlacement: function(error, element) {
                    error.addClass('invalid-feedback');
                    element.closest('.form-group').append(error);
                },
                highlight: function(element, errorClass, validClass) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function(element, errorClass, validClass) {
                    $(element).removeClass('is-invalid');
                }
            });

            $('.btn_submit').click(function() {
                let btn = $(this);
                if ($("#edit_role_form").valid()) {
                    $(btn).attr("disabled", true);
                    $(btn).html(
                        '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>'
                    );
                    $('#edit_role_form').submit();
                } else {
                    $(btn).attr("disabled", false);
                    $(btn).html(btn.html());
                }
            });


        });
    </script>
@endsection