<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\PostCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Services\FileUploadService;

class BlogController extends Controller
{
    public function index()
    {
        $posts = Post::with(['category', 'author'])->latest()->paginate(20);
        return view('admin.blog.index', compact('posts'));
    }

    public function create()
    {
        $categories = PostCategory::all();
        return view('admin.blog.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category_id' => 'required|exists:post_categories,id',
            'featured_image' => 'nullable|image|max:2048',
            'is_published' => 'boolean',
            'is_featured' => 'boolean',
            'tags' => 'nullable|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
        ]);

        $validated['author_id'] = auth()->id();
        $validated['slug'] = Str::slug($validated['title']);
        $validated['is_published'] = $request->has('is_published');
        $validated['is_featured'] = $request->has('is_featured');
        $validated['published_at'] = $validated['is_published'] ? now() : null;
        
        if ($request->has('tags') && !empty($request->tags)) {
            $validated['tags'] = array_map('trim', explode(',', $request->tags));
        } else {
            $validated['tags'] = null;
        }

        if ($request->hasFile('featured_image')) {
            $paths = FileUploadService::uploadImage($request->file('featured_image'), 'blog');
            $validated['featured_image'] = $paths['original'];
        }

        Post::create($validated);

        return redirect()->route('admin.blog.index')->with('success', 'Blog yazısı başarıyla oluşturuldu.');
    }

    public function edit(Post $post)
    {
        $categories = PostCategory::all();
        return view('admin.blog.edit', compact('post', 'categories'));
    }

    public function update(Request $request, Post $post)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category_id' => 'required|exists:post_categories,id',
            'featured_image' => 'nullable|image|max:2048',
            'is_published' => 'boolean',
            'is_featured' => 'boolean',
            'tags' => 'nullable|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
        ]);

        $validated['slug'] = Str::slug($validated['title']);
        $validated['is_published'] = $request->has('is_published');
        $validated['is_featured'] = $request->has('is_featured');
        
        if ($request->has('tags') && !empty($request->tags)) {
            $validated['tags'] = array_map('trim', explode(',', $request->tags));
        } else {
            $validated['tags'] = null;
        }

        if ($validated['is_published'] && !$post->published_at) {
            $validated['published_at'] = now();
        }

        if ($request->hasFile('featured_image')) {
            // Delete old image if exists
            if ($post->featured_image) {
                FileUploadService::delete($post->featured_image);
            }
            $paths = FileUploadService::uploadImage($request->file('featured_image'), 'blog');
            $validated['featured_image'] = $paths['original'];
        }

        $post->update($validated);

        return redirect()->route('admin.blog.index')->with('success', 'Blog yazısı başarıyla güncellendi.');
    }

    public function destroy(Post $post)
    {
        if ($post->featured_image) {
            FileUploadService::delete($post->featured_image);
        }
        $post->delete();
        return back()->with('success', 'Blog yazısı silindi.');
    }

    // Quick Category Management
    public function categories()
    {
        $categories = PostCategory::withCount('posts')->get();
        return view('admin.blog.categories', compact('categories'));
    }

    public function storeCategory(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:post_categories,name',
            'description' => 'nullable|string',
        ]);

        PostCategory::create($validated);

        return back()->with('success', 'Kategori başarıyla eklendi.');
    }
}
