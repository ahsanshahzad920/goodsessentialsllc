@props(['count' => 2])

<tr>
    <td colspan="{{ $count }}">
        <div class="alert alert-info text-center" role="alert">
            <i class="fas fa-info-circle"></i> No Records Found
        </div>
    </td>
</tr>
