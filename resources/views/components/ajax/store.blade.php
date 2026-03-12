@push('script')
    <script>
        $(document).ready(function() {
            $form = $('#createForm');
            $form.on('submit', function(e) {
                e.preventDefault();

                let formData = $form.serialize();

                // Ajax
                $.ajax({
                    url: "{{ $routeName }}",
                    type: 'POST',
                    data: formData,
                    dataType: 'json',
                    success: function(response) {
                        if (response.status) {
                            $('input').removeClass('is-invalid');
                            $('.error').html('');

                            $form[0].reset();
                            $form.closest('.modal').modal('hide');

                            Swal.fire({
                                title: "Success",
                                text: response.message,
                                icon: "success"
                            });

                            // Refresh Data Table
                            $("#table").load(window.location + " #table");
                        }
                    },
                    error: function(xhr, status, error) {
                        var errors = xhr.responseJSON.errors;
                        console.log('xhr', xhr);
                        console.log('status', status);

                        if (xhr.status === 500) {
                            Swal.fire({
                                title: "Error",
                                text: xhr.responseJSON.message,
                                icon: "error"
                            });
                        } else if (xhr.status === 404) {
                            Swal.fire({
                                title: "Error",
                                text: xhr.responseJSON.message,
                                icon: "error"
                            });
                        } else {
                            $.each(errors, function(key, value) {
                                $('#' + key).next().html(value);
                                $('#' + key).addClass('is-invalid');
                            });
                        }
                    }
                });
            });
        });
    </script>
@endpush
