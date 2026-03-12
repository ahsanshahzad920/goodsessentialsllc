@props(['tableId', 'fetchUrl', 'columns'])

@push('script')
    <script>
        function getData(tableId, fetchUrl, columns) {

            $.ajax({
                url: fetchUrl,
                method: 'GET',
                dataType: 'JSON',
                success: function(response) {
                    if (response.status) {
                        let data = response.data;
                        let tbody = $(tableId + ' tbody');
                        tbody.empty();

                        data.forEach(function(item) {
                            let row = '<tr>';

                            if (typeof columns === 'string') {
                                try {
                                    columns = JSON.parse(columns);
                                } catch (e) {
                                    console.error('Failed to parse columns:', e);
                                    return;
                                }
                            }

                            columns.forEach(function(column) {
                                row += `<td>${item[column]}</td>`;
                            });

                            row += `
                        <td class="text-end d-flex gap-1">
                            <button type="button" class="editBtn btn btn-warning"
                            data-bs-toggle="modal" data-bs-target="#updateModal"
                            data-id="${item.id}">
                                Edit
                            </button>
                            <form class="destroyForm">
                                @csrf
                                <button type="submit" class="delSubBtn btn btn-danger"
                                data-id="${item.id}">
                                    Delete
                                </button>
                            </form>
                        </td>
                    `;

                            row += '</tr>';
                            tbody.append(row);
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseText);
                }
            });
        }

        getData('#{{ $tableId }}', '{{ $fetchUrl }}', @json($columns));
    </script>
@endpush
