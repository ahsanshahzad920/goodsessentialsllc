@push('script')
    <script>
        $(document).ready(function() {
            $(document).on('submit', '.destroyForm', function(e) {
                e.preventDefault();
                var dataId = $(e.originalEvent.submitter).closest('.delSubBtn').data('id');
                console.log('id', dataId);
                console.log('id', `/{{ $route }}/${dataId}`);
                Swal.fire({
                    title: "Are you sure?",
                    text: "You will not be able to recover this record!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'No, cancel!',
                    reverseButtons: true
                }).then(function(result) {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `/{{ $route }}/${dataId}`,
                            method: 'DELETE',
                            dataType: 'json',
                            success: function(response) {
                                // console.log(response)
                                if (response.status) {
                                    Swal.fire({
                                        title: "Info",
                                        text: response.message,
                                        icon: "info"
                                    });

                                    // Refresh Data Table
                                    $("#table").load(window.location + " #table");
                                }
                            },
                            error: function(xhr, status, error) {
                                var errors = xhr.responseJSON.message;

                                Swal.fire({
                                    icon: "error",
                                    title: "Oops...",
                                    html: errors
                                });
                            }
                        });
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        Swal.fire(
                            'Cancelled',
                            'Your record is safe :)',
                            'error'
                        )
                    }
                })
            });
        });
    </script>
@endpush
