@push('script')
    <script>
        $(document).ready(function() {
            $('#updateForm').on('submit', function(e) {
                e.preventDefault();

                let formData = $('#updateForm').serialize();
                let dataID = $('[name="id"]').val();

                $.ajax({
                    url: `{{ $routeName }}/${dataID}`,
                    type: 'PUT',
                    data: formData,
                    dataType: 'json',
                    success: function(response) {
                        if (response.status) {
                            $('input').removeClass('is-invalid');
                            $('.error').html('');

                            $('#updateForm').closest('.modal').modal('hide');

                            Swal.fire({
                                title: "Success",
                                text: response.message,
                                icon: "success"
                            });

                            // Refresh Data Table
                            getData('#{{ $tableId }}', '{{ $fetchUrl }}',
                                @json($columns));
                        }
                    },
                    error: function(xhr, status, error) {
                        var errors = xhr.responseJSON.errors;
                        console.log(errors);
                        $.each(errors, function(key, value) {
                            $('#update_' + key).next().html(value);
                            $('#update_' + key).addClass('is-invalid');
                        });
                    }
                });
            });
        });
    </script>
@endpush
