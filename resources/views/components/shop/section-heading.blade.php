{{-- Kept for existing call sites; the design-system header is <x-section-header>. --}}
@props(['title', 'linkText' => null, 'href' => '#', 'heart' => false])
<x-section-header :title="$title" :link-text="$linkText" :href="$href" :heart="$heart" {{ $attributes }} />
