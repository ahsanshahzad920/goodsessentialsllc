<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Services\SeoService;
use Spatie\SchemaOrg\Schema;

class BlogController extends Controller
{
    public function index()
    {
        $posts = BlogPost::published()->orderByDesc('published_at')->paginate(9);

        SeoService::set(
            "Mom's Guide | Baby & Parenting Tips | " . config('app.name'),
            'Practical guides for Pakistani moms: baby clothing sizes, feeding tips, gift ideas, and newborn checklists from Goods Essentials.',
        );

        return view('blog.index', compact('posts'));
    }

    public function show(BlogPost $post)
    {
        abort_unless($post->status === 'published' && $post->published_at && $post->published_at->isPast(), 404);

        SeoService::set(
            $post->meta_title ?: $post->title . ' | ' . config('app.name'),
            $post->meta_description ?: SeoService::truncate(strip_tags($post->excerpt ?: $post->body), 160),
        );

        $article = Schema::blogPosting()
            ->headline($post->title)
            ->description($post->excerpt ?: '')
            ->datePublished($post->published_at)
            ->dateModified($post->updated_at)
            ->author(Schema::person()->name($post->author_name))
            ->mainEntityOfPage(route('blog.show', $post->slug));

        if ($post->featured_image) {
            $article->image(asset('storage/' . $post->featured_image));
        }

        SeoService::addSchema($article);

        $related = BlogPost::published()
            ->where('id', '!=', $post->id)
            ->orderByDesc('published_at')
            ->take(3)
            ->get();

        return view('blog.show', compact('post', 'related'));
    }
}
