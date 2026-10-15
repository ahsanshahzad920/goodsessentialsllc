@if (session('success') || session('error'))
    <div class="container-shop pt-4" role="status">
        <div class="rounded-2xl px-4 py-3 text-sm font-semibold {{ session('error') ? 'bg-blush-100 text-blush-600' : 'bg-mint-100 text-mint-600' }}">
            {{ session('error') ?? session('success') }}
        </div>
    </div>
@endif
