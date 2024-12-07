@push('css')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endpush

@section('modal')
    <div class="modal fade" id="borrow-modal" role="dialog" aria-labelledby="borrowModal"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Borrow to') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <input type="hidden" name="book_id" id="book_id" value="0">
                                <label>{{ __('Borrow to') }}</label>
                                <select class="form-control select2" name="user_id" id="user_id" style="width: 100%;">
                                    <option value="" selected disabled>Select User</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times"></i>
                        {{ __('Cancel') }}
                    </button>
                    <button type="button" class="btn btn-primary submit_btn">
                        <i class="far fa-save"></i>
                        {{ __('Submit') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- Select2 -->
    <script src="{{ asset('assets/plugins/select2/js/select2.full.min.js') }}"></script>
@endpush

@push('javascript')
    <script>
        $(function() {

            $('#borrow-modal').on('shown.bs.modal', function () {
                $('.select2').select2();
            });

            $(document).on('click', '.submit_btn', function() {
                Swal.fire({
                    title: "Are you sure?",
                    text: "You won't be able to revert this!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#2aa71d",
                    cancelButtonColor: "#596268",
                    confirmButtonText: "Yes"
                }).then((result) => {
                    if (result.isConfirmed) {
                        let bookId = $('#book_id').val();
                        let userId = $('#user_id').val();

                        if (!userId) {
                            Swal.fire({
                                text: "{{ __('Please select a user.') }}",
                                icon: "warning",
                                showCancelButton: false,
                                confirmButtonColor: "#2aa71d",
                                confirmButtonText: "Ok"
                            })
                            return;
                        }

                        let formData = {
                            book_id: bookId,
                            user_id: userId,
                            _token: '{{ csrf_token() }}'
                        };

                        let url = "{{ route('books.borrow', ':bookId') }}".replace(':bookId', bookId);
                        $.ajax({
                            url: url,
                            type: 'POST',
                            data: formData,
                            success: function(response) {
                                if(response.success) {
                                    $('.book-status-' + bookId).text("{{ __('Unavailable') }}").removeClass('badge-success').addClass('badge-danger');
                                    $('#borrow-modal').modal('hide');
                                    Swal.fire({
                                        text: response.message,
                                        icon: "success",
                                        showCancelButton: false,
                                        confirmButtonColor: "#2aa71d",
                                        confirmButtonText: "Ok"
                                    })
                                    return;
                                } else {
                                    Swal.fire({
                                        text: response.message,
                                        icon: "danger",
                                        showCancelButton: false,
                                        confirmButtonColor: "#2aa71d",
                                        confirmButtonText: "Ok"
                                    })
                                }
                            },
                            error: function(xhr) {
                                console.error(xhr);
                            }
                        });
                    }
                });
            });

        });
    </script>
@endpush
