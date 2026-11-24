@props(['count' => 2])

<tr>
    <td colspan="{{ $count }}">
        <div class="alert alert-info text-center" role="alert">
            <x-shop.icon name="info" class="size-4 shrink-0" /> No Records Found
        </div>
    </td>
</tr>
