<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function storeComment(Request $request, Post $post)
    {
        $request->validate([
            'contenu' => 'required|string|max:1000',
        ]);

        Comment::create([
            'contenu' => $request->contenu,
            'user_id' => Auth::id(),
            'post_id' => $post->id,
        ]);

        return redirect()->route('post.show', $post)->with('success', 'Comment added successfully.');
    }

    public function destroy(Comment $comment)
    {
        if (Auth::id() !== $comment->user_id) {
            abort(403, 'Unauthorized action.');
        }

        $post = $comment->post;
        $comment->delete();

        return redirect()->route('post.show', $post)->with('success', 'Comment deleted successfully.');
    }
}