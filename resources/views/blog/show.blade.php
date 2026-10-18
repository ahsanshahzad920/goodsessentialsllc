@extends('layouts.guest')

@section('content')
<div class="container-shop py-8">
    <nav aria-label="Breadcrumb" class="text-sm text-muted">
        <ol class="flex flex-wrap items-center gap-2">
            <li><a href="{{ route('blog.index') }}" class="hover:text-ink">Mom's Guide</a></li>
            <li aria-hidden="true">/</li>
            <li class="font-medium text-ink">{{ $post->title }}</li>
        </ol>
    </nav>

    <article class="mx-auto mt-6 max-w-3xl">
        <header class="mb-6">
            <h1 class="text-3xl font-extrabold leading-tight sm:text-4xl">{{ $post->title }}</h1>
            <p class="mt-3 text-sm text-muted">
                By {{ $post->author_name }} &middot; Last updated: {{ $post->updated_at->format('M j, Y') }}
            </p>
        </header>

        @if ($post->featured_image)
            <img src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->title }}"
                 class="mb-6 aspect-video w-full rounded-2xl object-cover">
        @endif

        <div class="prose-content space-y-4 text-sm leading-7 text-gray-700 sm:text-base">
            {!! $post->body !!}
        </div>

        <p class="mt-8 rounded-xl bg-mist-100 p-4 text-xs text-muted">
            This article is for general information and is not medical advice. Please consult your paediatrician for guidance specific to your baby.
        </p>

        <div class="mt-8 border-t border-line pt-6">
            <a href="{{ route('shop') }}"
               class="inline-flex items-center gap-2 rounded-full bg-brand-500 px-6 py-3 text-sm font-extrabold text-white hover:bg-brand-600">
                Shop Baby & Mom Essentials
            </a>
        </div>
    </article>

    @if ($related->isNotEmpty())
        <section aria-label="More guides" class="mx-auto mt-12 max-w-3xl space-y-4">
            <h2 class="text-xl font-bold">More from Mom's Guide</h2>
            <ul class="space-y-2">
                @foreach ($related as $relatedPost)
                    <li>
                        <a href="{{ route('blog.show', $relatedPost->slug) }}" class="text-sm font-semibold text-brand-500 hover:text-brand-600">
                            {{ $relatedPost->title }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </section>
    @endif
</div>
@endsection
