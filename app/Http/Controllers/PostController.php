<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Post;

class PostController extends Controller
{
    public function show($id)
    {
        $post = Post::with('user')->findOrFail($id);
        return view('user.post', compact('post'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'content' => 'nullable|string|max:10000',
            'media.*' => 'nullable|file|mimes:jpg,jpeg,png,gif,mp4,webp|max:90480',
            'shared_link' => 'nullable|url'
        ]);

        $post = new Post();
        $post->user_id = Auth::id();
        $post->content = $request->input('content', '');
        $post->shared_link = $request->input('shared_link');
        $post->media_type = 'none';

        $mediaPaths = [];
        if ($request->hasFile('media')) {
            foreach ($request->file('media') as $file) {
                $path = $file->store('posts/media', 'public');
                $mediaPaths[] = [
                    'path' => '/storage/' . $path,
                    'type' => str_contains($file->getMimeType(), 'image') ? 'image' : 'video'
                ];
            }
            $post->media_type = count($mediaPaths) > 0 ? $mediaPaths[0]['type'] : 'none';
            $post->media_path = json_encode($mediaPaths);
        }

        $post->save();

        return response()->json([
            'success' => true,
            'post' => [
                'id' => $post->id,
                'content' => $post->content,
                'media_path' => $mediaPaths,
                'media_type' => $post->media_type,
                'shared_link' => $post->shared_link,
                'created_at' => $post->created_at->diffForHumans(),
                'user' => [
                    'name' => Auth::user()->name,
                    'username' => Auth::user()->username,
                    'profile_picture' => Auth::user()->profile_picture 
                        ? Storage::url(Auth::user()->profile_picture) 
                        : asset('images/default-profile.png'),
                ],
            ],
        ]);
    }
}