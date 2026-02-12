<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, Post $post)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
            'parent_id' => 'nullable|exists:comments,id',
        ]);

        $comment = $post->comments()->create([
            'user_id' => auth()->id(),
            'parent_id' => $request->parent_id,
            'content' => $request->content,
            'is_approved' => true, // Auto-approve for now, can be changed later
        ]);

        return back()->with('success', 'Yorumunuz başarıyla paylaşıldı.');
    }

    public function destroy(Comment $comment)
    {
        if (auth()->id() !== $comment->user_id && ! auth()->user()->isAdmin()) {
            abort(403);
        }

        $comment->delete();

        return back()->with('success', 'Yorum silindi.');
    }
}
