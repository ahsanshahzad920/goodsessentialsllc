@push('script')
    <script>
        $(document).ready(function() {
            $(document).on('click', '.editBtn', function(e) {
                let dataId = $(this).data('id');
                let csrfToken = $('meta[name="csrf-token"]').attr('content');

                $.ajax({
                    url: `/{{ $routeName }}/${dataId}/edit`,
                    type: 'GET',
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    success: function(response) {
                        if (response.status) {
                            $.each(response.data, function(key, value) {
                                $(`#updateForm [name="${key}"]`).val(value);
                            });

                            // Show the modal
                            $('#updateModal').modal('show');
                        } else {
                            console.log('No record found.');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log(error);
                    }
                });
            });
        });
    </script>
@endpush
