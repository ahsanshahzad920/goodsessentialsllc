@if ($errors->any())
    <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4 text-red-700 shadow-sm">
        <div class="mb-2 flex items-center gap-2 font-bold">
            <i class="fa-solid fa-circle-exclamation"></i>
            Please fix the following errors:
        </div>

        <ul class="list-inside list-disc space-y-1 text-sm">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif