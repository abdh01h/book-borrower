@extends('layouts.main')
@section('page_title', $book->book_name)

@section('breadcrumb')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0">{{ $book->book_name }}</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Dashboard') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('books.index') }}">{{ __('Book Management') }}</a></li>
                <li class="breadcrumb-item active">{{ $book->book_name }}</li>
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
                        <div class="text-center my-3">
                            <img class="img-fluid" src="{{ route('books.image.show', $book->cover_image) }}" alt="{{ __('Book Cover') }}" style='width:600px;'>
                        </div>
                        <h3 class="text-center">{{ $book->book_name }}</h3>

                        <div class="text-center mt-3 mb-4">
                            @can('book.update')
                                <a href="{{ route('books.edit', $book->id) }}" class="btn btn-primary btn-sm">
                                    <i class="far fa-edit"></i>
                                    {{ __('Edit Book') }}
                                </a>
                                <a href="{{ route('books.export.pdf', $book->id) }}" class="btn btn-danger btn-sm"
                                    onclick="event.preventDefault();document.getElementById('export-pdf-{{ $book->id }}').submit();">
                                    <i class="far fa-file-pdf"></i>
                                    {{ __('Export as PDF') }}
                                    <form id="export-pdf-{{ $book->id }}" action="{{ route('books.export.pdf', $book->id) }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </a>
                            @endcan
                        </div>

                        <ul class="list-group container list-group-unbordered mb-3">
                            <li class="list-group-item">
                                <b>{{ __('ID') }}:</b>
                                <span>{{ $book->id }}</span>
                            </li>
                            <li class="list-group-item">
                                <b>{{ __('Author Name') }}:</b>
                                <span>{{ $book->author }}</span>
                            </li>
                            <li class="list-group-item">
                                <b>{{ __('Description') }}:</b>
                                {{ $book->description }}
                            </li>
                            <li class="list-group-item">
                                <b>{{ __('Status') }}:</b>
                                <span>
                                    @if($book->status)
                                        <span class="badge badge-success">Available</span>
                                    @else
                                        <span class="badge badge-danger">Unavailable</span>
                                    @endif
                                </span>
                            </li>
                        </ul>

                        @can('book.update')
                            <div class="mt-5">
                                <h2>Borrowers' History</h2>
                                <table class="table table-hover table-striped text-nowrap">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>{{ __('Name') }}</th>
                                            <th>{{ __('Borrowed At') }}</th>
                                            <th>{{ __('Returned At') }}</th>
                                            <th>Return</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($book->borrows as $borrower)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>
                                                    {{ $borrower->user->name }}
                                                </td>
                                                <td>
                                                    {{ date('j F, Y, g:i A', strtotime($borrower->created_at)) }}
                                                </td>
                                                <td>
                                                    @if(!empty($borrower->returned_at))
                                                        {{ date('j F, Y, g:i A', strtotime($borrower->returned_at)) }}
                                                    @endif
                                                </td>
                                                <td>
                                                    @if(empty($borrower->returned_at))
                                                        <a href="{{ route('books.return', [$borrower->user->id, $borrower->borrowable_id]) }}" class="btn btn-warning btn-sm btn_return">
                                                            <i class="fas fa-redo"></i>
                                                            <form id="return-{{ $borrower->id }}" action="{{ route('books.return', [$borrower->user->id, $borrower->borrowable_id]) }}" method="POST" class="d-none">
                                                                @csrf
                                                            </form>
                                                        </a>
                                                    @endif
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
                        @endcan

                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@can('book.update')
    @push('javascript')
        <script>
            $(function() {
                $('.btn_return').click(function(event) {
                    event.preventDefault();
                    const form = $(this).find('form');
                    Swal.fire({
                        title: "Are you sure?",
                        text: "You won't be able to revert this!",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#2aa71d",
                        cancelButtonColor: "#596268",
                        confirmButtonText: "Yes, return!"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        </script>
    @endpush
@endcan
