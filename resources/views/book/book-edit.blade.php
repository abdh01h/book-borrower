@extends('layouts.main')
@section('page_title', __('Edit') . ' ' . $book->book_name)

@push('css')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endpush

@section('breadcrumb')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0">{{ __('Edit') . ' ' . $book->book_name }}</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Dashboard') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('books.index') }}">{{ __('Book Management') }}</a></li>
                <li class="breadcrumb-item active">{{ __('Edit') . ' ' . $book->book_name }}</li>
            </ol>
        </div>
    </div>
@endsection

@section('content')
    <div class="card card-primary card-outline">
        <div class="card-header">
            <h3 class="card-title">{{ __('Edit') . ' ' . $book->book_name }}</h3>
        </div>
        <div class="card-body">
            <form class="row" id="submit_form" action="{{ route('books.update', $book->id) }}" method="post" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="col-lg-6 col-sm-12">
                    <div class="form-group">
                        <label>{{ __('Book Name') }}</label>
                        <input type="text" class="form-control" name="book_name" id="book_name" placeholder="{{ __('Book Name') }}"
                            value="{{ $book->book_name }}" required>
                    </div>
                    <div class="form-group">
                        <label>{{ __('Author Name') }}</label>
                        <input type="text" class="form-control" name="author" id="author" placeholder="{{ __('Author Name') }}"
                        value="{{ $book->author }}" required>
                    </div>
                    <div class="form-group">
                        <div class="img-preview text-center mb-2">
                            <img class="img-fluid" src="{{ route('books.image.show', $book->cover_image) }}" alt="{{ __('Book Cover') }}" style='width:600px;'>
                        </div>
                        <label for="cover_image">Cover Image</label>
                        <div class="input-group">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" name="cover_image" id="cover_image"
                                    accept="image/png, image/jpeg">
                                <label class="custom-file-label" for="cover_image">Choose Cover Image</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>{{ __('Description') }}</label>
                        <textarea class="form-control" name="description" id="description" rows="5" placeholder="{{ __('Description') }}">{{ $book->description }}</textarea>
                    </div>
                    <div class="form-group">
                        <label>{{ __('Book Status') }}</label>
                        <select class="form-control select2" name="status" id="status" style="width: 100%;">
                            <option value="1" {{ $book->status ? 'selected' : '' }}>{{ __('Available') }}</option>
                            <option value="0" {{ !$book->status ? 'selected' : '' }}>{{ __('Unavailable') }}</option>
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

            $("#cover_image").change(function(){
                readURL(this);
            });

            //Initialize Select2 Elements
            $('.select2').select2();

            $('#submit_form').validate({
                rules: {
                    book_name: {
                        required: true,
                        maxlength: 255,
                    },
                    author: {
                        required: true,
                        maxlength: 255,
                    },
                    description: {
                        maxlength: 400,
                    },
                    cover_image: {
                        extension: "jpg,png",
                        maxsize: 1024*1024*2
                    },
                    status: {
                        required: true,
                    },
                },
                messages: {
                    cover_image: {
                        maxsize: "{{ __('File size must not exceed 2.00 MB each.') }}",
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
                if ($("#submit_form").valid()) {
                    $(btn).attr("disabled", true);
                    $(btn).html(
                        '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>'
                    );
                    $('#submit_form').submit();
                } else {
                    $(btn).attr("disabled", false);
                    $(btn).html(btn.html());
                }
            });


        });

        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('.img-preview').html(`<img class='img-fluid' src='${e.target.result}' style='width:600px;'>`);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endsection
