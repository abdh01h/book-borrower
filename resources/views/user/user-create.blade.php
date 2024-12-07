@extends('layouts.main')
@section('page_title', __('Create New User'))

@push('css')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endpush

@section('breadcrumb')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0">{{ __('Create New User') }}</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Dashboard') }}</a></li>
                <li class="breadcrumb-item"><a href="#">{{ __('Users & Roles') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('users.index') }}">{{ __('User Management') }}</a></li>
                <li class="breadcrumb-item active">{{ __('Create New User') }}</li>
            </ol>
        </div>
    </div>
@endsection

@section('content')
    <div class="card card-primary card-outline">
        <div class="card-header">
            <h3 class="card-title">{{ __('Create New User') }}</h3>
        </div>
        <div class="card-body">
            <form class="row" id="create_user_form" action="{{ route('users.store') }}" method="post">
                @csrf
                <div class="col-lg-6 col-sm-12">
                    <div class="form-group">
                        <label>{{ __('User Name') }}</label>
                        <input type="text" class="form-control" name="name" id="name" placeholder="{{ __('User Name') }}"
                            required>
                    </div>
                    <div class="form-group">
                        <label>{{ __('Email Address') }}</label>
                        <input type="email" class="form-control" name="email" id="email" placeholder="{{ __('Email Address') }}"
                            required>
                    </div>
                    <div class="form-group">
                        <label>{{ __('Password') }}</label>
                        <input type="password" class="form-control" name="password" id="password" placeholder="{{ __('Password') }}" required>
                    </div>
                    <div class="form-group">
                        <label>{{ __('User Role') }}</label>
                        <select class="form-control select2" name="role" id="role" style="width: 100%;">
                            <option value="" selected="selected">{{ __('Select a role') }}</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </form>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary btn_submit">
                <i class="fas fa-plus-circle mr-1"></i>
                {{ __('Create') }}
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

            $('#create_user_form').validate({
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
                                }
                            }
                        }
                    },
                    password: {
                        required: true,
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
                }
            });

            $('.btn_submit').click(function() {
                let btn = $(this);
                if ($("#create_user_form").valid()) {
                    $(btn).attr("disabled", true);
                    $(btn).html(
                        '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>'
                    );
                    $('#create_user_form').submit();
                } else {
                    $(btn).attr("disabled", false);
                    $(btn).html(btn.html());
                }
            });


        });
    </script>
@endsection
