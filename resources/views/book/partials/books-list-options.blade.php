<div class="input-group-prepend">
    <button type="button" class="btn btn-info btn-sm dropdown-toggle"
        data-toggle="dropdown" aria-expanded="false">
        {{ __('Options') }}
    </button>
    <div class="dropdown-menu" style="">
        <a class="dropdown-item" href="{{ route('books.show', $book->id) }}">
            <i class="far fa-eye mr-1"></i>
            {{ __('View') }}
        </a>
        <a href="{{ route('books.export.pdf', $book->id) }}" class="dropdown-item"
            onclick="event.preventDefault();document.getElementById('export-pdf-{{ $book->id }}').submit();">
            <i class="far fa-file-pdf mr-1"></i>
            {{ __('Export as PDF') }}
            <form id="export-pdf-{{ $book->id }}" action="{{ route('books.export.pdf', $book->id) }}" method="POST" class="d-none">
                @csrf
            </form>
        </a>
        @can('book.update')
            <a class="dropdown-item" href="{{ route('books.edit', $book->id) }}">
                <i class="far fa-edit mr-1"></i>
                {{ __('Edit') }}
            </a>
            @if($book->status)
                <a class="dropdown-item borrow-modal-btn" href="#borrow-modal" data-toggle="modal" data-target="#borrow-modal" data-book_id="{{ $book->id }}">
                    <i class="fas fa-share"></i>
                    {{ __('Borrow to') }}
                </a>
            @endif
        @endcan
        @can('book.delete')
            <button type="button" class="btn btn-link dropdown-item delete_book_btn">
                <i class="far fa-trash-alt mr-1"></i>
                {{ __('Delete') }}
                <form action="{{ route('books.destroy', $book->id) }}" method="post" class="d-none">
                    @csrf
                    @method('delete')
                </form>
            </button>
        @endcan
    </div>
</div>
