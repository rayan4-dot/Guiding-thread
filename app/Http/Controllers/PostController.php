<?php

namespace App\Http\Controllers;
use App\Models\Reaction;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Post;
use App\Models\Hashtag;

class PostController extends Controller
{

    public function like(Post $post)
    {
        $user = Auth::user();
    
        $existingReaction = Reaction::where('post_id', $post->id)
            ->where('user_id', $user->id)
            ->first();
    
        if ($existingReaction) {
            $existingReaction->delete(); 
        } else {
            Reaction::create([
                'post_id' => $post->id,
                'user_id' => $user->id,
            ]);
        }
    

        return response()->noContent();
    }
    
    public function show($id)
    {
        $post = Post::with('user')->findOrFail($id);
        $post->load('comments.user');
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

        if ($post->content) {
            preg_match_all('/#(\w+)/u', $post->content, $matches);
        
            $hashtags = [];
            foreach ($matches[1] as $tagName) {
                $tag = Hashtag::firstOrCreate(['name' => strtolower($tagName)]);
                $hashtags[] = $tag->id;
            }
        
            if (!empty($hashtags)) {
                $post->hashtags()->sync($hashtags); 
            }
        }

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

    public function destroy($id, Request $request)
    {
        $post = Post::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        if ($post->media_path) {
            $mediaItems = json_decode($post->media_path, true);
            if (is_array($mediaItems)) {
                foreach ($mediaItems as $media) {
                    $filePath = str_replace('/storage/', '', $media['path']);
                    if (Storage::disk('public')->exists($filePath)) {
                        Storage::disk('public')->delete($filePath);
                    }
                }
            }
        }

        $post->delete();
        if ($request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->json([
                'success' => true,
                'message' => 'Post deleted successfully',
                'post_id' => $id,
            ]);
        }
        
        // $redirectUrl = $request->headers->get('referer') ? url()->previous() : route('home');
        // return response()->json([
        //     'success' => true,
        //     'message' => 'Post deleted successfully',
        //     'post_id' => $id,
        //     'redirect' => $redirectUrl,
        // ]);

        return redirect()->route('user.home')->with('success', 'Post deleted successfully.');

    }
}