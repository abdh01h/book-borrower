@extends('layouts.main')
@section('page_title', __('Edit') . ' ' . $user->name)

@push('css')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endpush

@section('breadcrumb')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0">{{ __('Edit') . ' ' . $user->name }}</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Dashboard') }}</a></li>
                <li class="breadcrumb-item"><a href="#">{{ __('Users & Roles') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('users.index') }}">{{ __('User Management') }}</a></li>
                <li class="breadcrumb-item active">{{ __('Edit') . ' ' . $user->name }}</li>
            </ol>
        </div>
    </div>
@endsection


@section('content')
    <div class="card card-primary card-outline">
        <div class="card-header">
            <h3 class="card-title">{{ __('Edit') . ' ' . $user->name }}</h3>
        </div>
        <div class="card-body">
            <form class="row" id="edit_user_form" action="{{ route('users.update', $user->id) }}" method="post">
                @csrf
                @method('put')
                <div class="col-lg-6 col-sm-12">
                    <div class="form-group">
                        <label>{{ __('User Name') }}</label>
                        <input type="text" class="form-control" name="name" id="name" placeholder="{{ __('User Name') }}" value="{{ $user->name }}"
                            required>
                    </div>
                    <div class="form-group">
                        <label>{{ __('Email Address') }}</label>
                        <input type="email" class="form-control" name="email" id="email" placeholder="{{ __('Email Address') }}" value="{{ $user->email }}"
                            required>
                    </div>
                    <div class="form-group">
                        <label>{{ __('New Password') }}</label>
                        <input type="password" class="form-control" name="password" id="password" placeholder="{{ __('New Password') }}">
                        <small class="form-text text-muted">
                            {{ __('Note: this will change the user password to a new password') }}
                        </small>
                    </div>
                    <div class="form-group">
                        <label>{{ __('User Role') }}</label>
                        <select class="form-control select2" name="role" id="role" style="width: 100%;">
                            <option value="" selected="selected">{{ __('Select a role') }}</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->id }}" {{ isset($user->roles[0]->id) && $user->roles[0]->id == $role->id ? 'selected' : ''}}>{{ $role->name }}</option>
                            @endforeach
                        </select>
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

@push('scripts')
    <!-- Select2 -->
    <script src="{{ asset('assets/plugins/select2/js/select2.full.min.js') }}"></script>
@endpush

@section('javascript')
    <script>
        $(function() {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            //Initialize Select2 Elements
            $('.select2').select2();

            $('#edit_user_form').validate({
                rules: {
                    name: {
                        required: true,
                        maxlength: 255,
                    },
                    email: {
                        required: true,
                        email: true,
                        maxlength: 255,
                        remote: {
                            url: "{{ route('users.check.email.exists') }}",
                            type: 'post',
                            data: {
                                email: function() {
                                    return $('#email').val();
                                },
                                user_id: "{{ $user->id }}",
                            }
                        }
                    },
                    password: {
                        maxlength: 255,
                    },
                    role: {
                        required: true,
                    },
                },
                messages: {
                    email: {
                        remote: "The email has already been taken",
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
                },
                submitHandler: function(form, event) {
                    event.preventDefault();
                    form.submit();
                }
            });

            $('.btn_submit').click(function() {
                let btn = $(this);
                if ($("#edit_user_form").valid()) {
                    $(btn).attr("disabled", true);
                    $(btn).html(
                        '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>'
                    );
                    $('#edit_user_form').submit();
                } else {
                    $(btn).attr("disabled", false);
                    $(btn).html(btn.html());
                }
            });

        });
    </script>
@endsection
