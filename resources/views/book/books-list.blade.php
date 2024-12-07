@extends('layouts.main')
@section('page_title', __('Book Management'))

@section('css')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
@endsection

@section('breadcrumb')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0">{{ __('Book Management') }}</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Dashboard') }}</a></li>
                <li class="breadcrumb-item">{{ __('Book Management') }}</li>
            </ol>
        </div>
    </div>
@endsection


@section('content')
    <div class="card card-primary card-outline">
        <div class="card-header">
            <h3 class="card-title">{{ __('Book Management') }}</h3>
        </div>
        <div class="card-body table-responsive">
            <div class="d-flex flex-row-reverse">
                @can('book.create')
                    <a href="{{ route('books.create') }}" class="btn btn-primary" data-toggle="tooltip"
                        data-placement="left" title="{{ __('Create New Book') }}">
                        <i class="fas fa-plus-circle mr-1"></i>
                        {{ __('Create New Book') }}
                    </a>
                @endcan
                <a href="{{ route('books.export.all.pdf') }}" class="btn btn-danger mr-2"
                    onclick="event.preventDefault();document.getElementById('export-pdf').submit();">
                    <i class="far fa-file-pdf"></i>
                    {{ __('Export all as PDF') }}
                    <form id="export-pdf" action="{{ route('books.export.all.pdf') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </a>
            </div>
            <div class="mt-3">
                <table class="table table-hover table-striped text-nowrap datatable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ __('Book Name') }}</th>
                            <th>{{ __('Author Name') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th>{{ __('Options') }}</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
@endsection

@can('book.update')
    @include('book.partials.modal.borrow')
@endcan

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
                ajax: "{{ route('books.datatables') }}",
                columns: [
                    { data: 'DT_RowIndex', orderable: true, searchable: true },
                    { data: 'book_name', orderable: true },
                    { data: 'author', orderable: true },
                    { data: 'status', orderable: true },
                    { data: 'options', orderable: false, searchable: false },
                ]
            });
        });
    </script>
    @can('book.delete')
        <script>
            $(function() {
                $(document).on('click', '.delete_book_btn', function() {
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
    @endcan
    @can('book.update')
    <script>
        $(function() {
            $(document).on('click', '.borrow-modal-btn', function() {
                let bookId = $(this).data('book_id');
                $('#book_id').val(bookId);
                $.ajax({
                    url: "{{ route('books.valid.borrowers') }}",
                    method: 'GET',
                    success: function(response) {
                        if (response.success) {
                            $('#user_id').find('option:not(:first)').remove();
                            response.users.forEach(function(user) {
                                $('#user_id').append(new Option(`${user.name} (${user.email})`, user.id));
                            });
                        }
                        $('.select2').select2();
                    },
                    error: function(xhr) {
                        console.error(xhr);
                    }
                });
            });
        });
    </script>
    @endcan
@endsection