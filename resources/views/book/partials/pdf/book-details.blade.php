<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        h1 {
            color: #333;
        }
        .text-center {
            text-align: center;
        }
        .mt {
            margin-top: 20px;
        }
        .mb {
            margin-bottom: 20px;
        }
        .cover-image {
            text-align: center;
            margin-bottom: 2rem;
        }
        .cover-image img {
            width: 300px;
        }
        .page-break {
            page-break-after: always;
        }
        .no-page-break {
            page-break-after: auto;
        }
        .footer {
            position: fixed;
            bottom: 5px;
            left: 0;
            right: 0;
            text-align: center;
            padding: 10px;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="text-center mb">
        <b>{{ config('app.name') }}</b>
    </div>
    @forelse ($books as $book)
        <div class="{{ $loop->last ? 'no-page-break' : 'page-break' }}">
            <div class="cover-image">
                <img src="{{ $book->cover_image_path }}" alt="{{ __('Book Cover') }}">
            </div>
            <div class="text-center">
                <h1>{{ $book->book_name }}</h1>
            </div>
            <div><b>{{ __('Author Name') }}: </b> {{ $book->author }}</div>
            <div class="mt">
                <b>{{ __('Description') }}: </b> <span>{{ $book->description }}</span>
            </div>
            <div class="mt">
                <b>{{ __('Status') }}: </b>
                @if($book->status)
                    <span>{{ __('Available') }}</span>
                @else
                    <span>{{ __('Unavailable') }}</span>
                @endif
            </div>
        </div>
    @empty
        <div class="text-center">
            {{ __('No Data') }}
        </div>
    @endforelse
</body>
</html>
