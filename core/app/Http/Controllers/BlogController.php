<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\PostCategory;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $query = Post::published()->with(['category', 'author']);

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%")
                    ->orWhereJsonContains('tags', $search);
            });
        }

        if ($request->has('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Sorting Logic
        switch ($request->get('sort')) {
            case 'popular':
                $query->popular();
                break;
            case 'oldest':
                $query->orderBy('published_at', 'asc');
                break;
            default: // newest
                $query->orderBy('published_at', 'desc');
                break;
        }

        $posts = $query->paginate(12);

        $categories = PostCategory::withCount('posts')->get();

        return view('blog.index', compact('posts', 'categories'));
    }

    public function show($slug)
    {
        $post = Post::published()->where('slug', $slug)->with(['category', 'author', 'comments.user'])->firstOrFail();

        $post->increment('views');

        // Simple related posts based on category
        $relatedPosts = Post::published()
            ->where('category_id', $post->category_id)
            ->where('id', '!=', $post->id)
            ->take(3)
            ->get();

        return view('blog.show', compact('post', 'relatedPosts'));
    }
}
