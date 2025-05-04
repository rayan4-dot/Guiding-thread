<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Models\Connection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    // public function index()
    // {
    //     $user = Auth::user();
    //     $posts = $user->posts()->with('user')->latest()->paginate(10); 
    //     $friends = User::getNonAdminUsers(); 

    //     return view('user.profile', compact('user', 'posts', 'friends'));
    // }

    // public function showPublicProfile($username)
    // {
    //     $user = User::where('username', $username)->firstOrFail();
    //     $friends = User::where('role_id', 2)
    //                   ->where('id', '!=', $user->id)
    //                   ->take(5)
    //                   ->get();

    //     $isOwnProfile = Auth::check() && Auth::user()->id === $user->id;
    //     $isFriend = !$isOwnProfile; 

    //     return view('user.public-profile', compact('user', 'friends', 'isOwnProfile', 'isFriend'));
    // }



    public function index()
    {
        $user = Auth::user();

        // display mitual friends
        $friends = User::whereIn('id', function ($query) use ($user) {
            $query->select('friend_id')
                ->from('connections')
                ->where('user_id', $user->id)
                ->where('status', 'accepted')
                ->union(
                    Connection::select('user_id')
                        ->where('friend_id', $user->id)
                        ->where('status', 'accepted')
                );
        })->where('role_id', 2)->take(8)->get();

        //  total friends
        $friendsCount = Connection::where(function ($query) use ($user) {
            $query->where('user_id', $user->id)->orWhere('friend_id', $user->id);
        })->where('status', 'accepted')->count();
/** @var \App\Models\User $user */
$posts = $user->posts()->with('user')->latest()->paginate(10);

        Log::error('ProfileController::index', [
            'user_id' => $user->id,
            'friends_count' => $friends->count(),
            'total_friends' => $friendsCount,
            'posts_count' => $posts->count(),
        ]);

        return view('user.profile', compact('user', 'posts', 'friends', 'friendsCount'));
    }

    public function show($username)
    {
        $user = User::where('username', $username)->firstOrFail();

        // display mutual friends
        $friends = User::whereIn('id', function ($query) use ($user) {
            $query->select('friend_id')
                ->from('connections')
                ->where('user_id', $user->id)
                ->where('status', 'accepted')
                ->union(
                    Connection::select('user_id')
                        ->where('friend_id', $user->id)
                        ->where('status', 'accepted')
                );
        })->where('role_id', 2)->take(8)->get();

        // count total friends
        $friendsCount = Connection::where(function ($query) use ($user) {
            $query->where('user_id', $user->id)->orWhere('friend_id', $user->id);
        })->where('status', 'accepted')->count();

        // display posts
        $posts = $user->posts()->with('user')->latest()->paginate(10);

        $isOwnProfile = Auth::check() && Auth::user()->id === $user->id;
        $isFriend = Auth::check() && Connection::where(function ($query) use ($user) {
            $query->where('user_id', Auth::id())->where('friend_id', $user->id);
        })->orWhere(function ($query) use ($user) {
            $query->where('user_id', $user->id)->where('friend_id', Auth::id());
        })->where('status', 'accepted')->exists();

        Log::info('ProfileController::show', [
            'user_id' => $user->id,
            'username' => $username,
            'friends_count' => $friends->count(),
            'total_friends' => $friendsCount,
            'posts_count' => $posts->count(),
            'is_own_profile' => $isOwnProfile,
            'is_friend' => $isFriend,
        ]);

        return view('user.public-profile', compact('user', 'friends', 'friendsCount', 'posts', 'isOwnProfile', 'isFriend'));
    }


    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:50',
            'username' => 'required|string|max:50|unique:users,username,' . Auth::id(),
            'bio' => 'nullable|string|max:160',
            'profile_picture' => 'nullable|image|max:2048',
            'cover_photo' => 'nullable|image|max:2048',
        ]);

        try {
            /** @var User $user */
            $user = Auth::user();


            if ($request->hasFile('profile_picture')) {
                if ($user->profile_picture && Storage::disk('public')->exists($user->profile_picture)) {
                    Storage::disk('public')->delete($user->profile_picture);
                }
                $path = $request->file('profile_picture')->store('profiles', 'public');
                $user->profile_picture = $path;
            }


            if ($request->hasFile('cover_photo')) {
                if ($user->cover_photo && Storage::disk('public')->exists($user->cover_photo)) {
                    Storage::disk('public')->delete($user->cover_photo);
                }
                $path = $request->file('cover_photo')->store('covers', 'public');
                $user->cover_photo = $path;
            }


            $user->update([
                'name' => $request->input('name'),
                'username' => $request->input('username'),
                'bio' => $request->input('bio'),
                'profile_picture' => $user->profile_picture ?? $user->getOriginal('profile_picture'),
                'cover_photo' => $user->cover_photo ?? $user->getOriginal('cover_photo'),
            ]);

            return redirect()->route('user.profile')->with('success', 'Profile updated successfully');
        } catch (\Exception $e) {
            Log::error('Profile update failed: ' . $e->getMessage());
            return redirect()->route('user.profile')->with('error', 'Failed to update profile: ' . $e->getMessage());
        }
    }

    public function removePicture()
    {
        try {
            /** @var User $user */
            $user = Auth::user();

            if ($user->profile_picture && Storage::disk('public')->exists($user->profile_picture)) {
                Storage::disk('public')->delete($user->profile_picture);
                $user->profile_picture = null;
                $user->save();
            }

            return redirect()->route('user.profile')->with('success', 'Profile picture removed successfully');
        } catch (\Exception $e) {
            Log::error('Profile picture removal failed: ' . $e->getMessage());
            return redirect()->route('user.profile')->with('error', 'Failed to remove profile picture: ' . $e->getMessage());
        }
    }

    public function removeCoverPhoto()
    {
        try {
            /** @var User $user */
            $user = Auth::user();

            if ($user->cover_photo && Storage::disk('public')->exists($user->cover_photo)) {
                Storage::disk('public')->delete($user->cover_photo);
                $user->cover_photo = null;
                $user->save();
            }

            return redirect()->route('user.profile')->with('success', 'Cover photo removed successfully');
        } catch (\Exception $e) {
            Log::error('Cover photo removal failed: ' . $e->getMessage());
            return redirect()->route('user.profile')->with('error', 'Failed to remove cover photo: ' . $e->getMessage());
        }
    }


}