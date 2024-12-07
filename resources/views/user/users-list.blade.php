@extends('layouts.main')
@section('page_title', __('User Management'))

@section('css')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
@endsection

@section('breadcrumb')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0">{{ __('User Management') }}</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Dashboard') }}</a></li>
                <li class="breadcrumb-item"><a href="#">{{ __('Users & Roles') }}</a></li>
                <li class="breadcrumb-item">{{ __('User Management') }}</li>
            </ol>
        </div>
    </div>
@endsection


@section('content')
    <div class="card card-primary card-outline">
        <div class="card-header">
            <h3 class="card-title">{{ __('User Management') }}</h3>
        </div>
        <div class="card-body table-responsive">
            <div class="d-flex flex-row-reverse">
                @can('user.create')
                    <a href="{{ route('users.create') }}" class="btn btn-primary" data-toggle="tooltip"
                        data-placement="left" title="{{ __('Create New User') }}">
                        <i class="fas fa-plus-circle mr-1"></i>
                        {{ __('Create New User') }}
                    </a>
                @endcan
            </div>
            <div class="mt-3">
                <table class="table table-hover table-striped text-nowrap datatable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ __('ID') }}</th>
                            <th>{{ __('User Name') }}</th>
                            <th>{{ __('Email Address') }}</th>
                            <th>{{ __('Role') }}</th>
                            <th>{{ __('Registered At') }}</th>
                            <th>{{ __('Options') }}</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- DataTables  & Plugins -->
    <script src="{{ asset('assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>
@endpush

@section('javascript')
    <script>
        $(function() {

            $('.datatable').DataTable({
                processing: true,
                serverSide: true,
                ordering: true,
                ajax: "{{ route('users.datatables') }}",
                columns: [
                    { data: 'DT_RowIndex', orderable: true, searchable: true },
                    { data: 'id' },
                    { data: 'name', name: 'name', orderable: true },
                    { data: 'email', name: 'email', orderable: true },
                    { data: 'role', name: 'roles.name', orderable: true },
                    { data: 'created_at', name: 'created_at', orderable: true },
                    { data: 'options', orderable: false, searchable: false },
                ]
            });

            $(document).on('click', '.delete_user_btn', function() {
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
