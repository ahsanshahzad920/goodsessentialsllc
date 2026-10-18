@extends('layouts.guest')

@section('content')
<div class="container-shop space-y-8 py-8">
    <header class="text-center">
        <p class="text-sm font-bold uppercase tracking-wider text-brand-500">Mom's Guide</p>
        <h1 class="mt-1 text-3xl font-bold sm:text-4xl">Baby & Parenting Tips for Pakistani Moms</h1>
        <p class="mx-auto mt-3 max-w-2xl text-sm text-muted">
            Practical, honest guides on baby clothing, feeding, gifts and everyday care — written for moms shopping in Pakistan.
        </p>
    </header>

    @if ($posts->isEmpty())
        <p class="rounded-2xl bg-white p-6 text-center text-sm text-muted">No guides published yet. Check back soon.</p>
    @else
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($posts as $post)
                <article class="flex flex-col overflow-hidden rounded-2xl border border-line bg-white shadow-sm transition hover:shadow-md">
                    <a href="{{ route('blog.show', $post->slug) }}" class="block overflow-hidden bg-mist-100">
                        <img src="{{ $post->featured_image ? asset('storage/' . $post->featured_image) : asset('images/product-placeholder.svg') }}"
                             alt="{{ $post->title }}" loading="lazy" class="aspect-video w-full object-cover">
                    </a>
                    <div class="flex flex-1 flex-col p-4">
                        <p class="text-xs text-muted">{{ $post->published_at->format('M j, Y') }}</p>
                        <h2 class="mt-1 text-base font-bold leading-snug">
                            <a href="{{ route('blog.show', $post->slug) }}" class="hover:text-brand-500">{{ $post->title }}</a>
                        </h2>
                        @if ($post->excerpt)
                            <p class="mt-2 line-clamp-3 text-sm text-muted">{{ $post->excerpt }}</p>
                        @endif
                        <a href="{{ route('blog.show', $post->slug) }}" class="mt-auto pt-3 text-xs font-bold text-brand-500 hover:text-brand-600">Read more →</a>
                    </div>
                </article>
            @endforeach
        </div>

        <div class="pt-4">
            {{ $posts->links() }}
        </div>
    @endif
</div>
@endsection
