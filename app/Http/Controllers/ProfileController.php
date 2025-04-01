<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        $friends = User::getNonAdminUsers(); // Fetch non-admin users (role_id = 2)

        return view('user.profile', compact('user', 'friends'));
    }

    public function showPublicProfile($username)
    {
        // Fetch the user by username
        $user = User::where('username', $username)->firstOrFail();
        $friends = User::where('role_id', 2)
                      ->where('id', '!=', $user->id) // Exclude the profile owner
                      ->take(5) // Limit to 5 users
                      ->get();

        // Determine if the user is viewing their own profile
        $isOwnProfile = Auth::user()->id === $user->id;

        // For now, assume the user is a friend since they were fetched from the friends list
        $isFriend = !$isOwnProfile; // We'll adjust this logic later when implementing the connect feature

        return view('user.public-profile', compact('user', 'friends', 'isOwnProfile', 'isFriend'));
    }

    public function update(Request $request)
    {
        // Validate the request
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

            // Handle profile picture upload
            if ($request->hasFile('profile_picture')) {
                // Delete old profile picture if it exists
                if ($user->profile_picture && Storage::disk('public')->exists($user->profile_picture)) {
                    Storage::disk('public')->delete($user->profile_picture);
                }
                // Store new profile picture
                $path = $request->file('profile_picture')->store('profiles', 'public');
                $user->profile_picture = $path;
                $user->save();
            }

            // Handle cover photo upload
            if ($request->hasFile('cover_photo')) {
                // Delete old cover photo if it exists
                if ($user->cover_photo && Storage::disk('public')->exists($user->cover_photo)) {
                    Storage::disk('public')->delete($user->cover_photo);
                }
                // Store new cover photo
                $path = $request->file('cover_photo')->store('covers', 'public');
                $user->cover_photo = $path;
                $user->save();
            }

            // Update name, username, and bio
            $user->update([
                'name' => $request->input('name'),
                'username' => $request->input('username'),
                'bio' => $request->input('bio'),
            ]);

            return redirect()->route('user.profile')->with('success', 'Profile updated successfully');
        } catch (\Exception $e) {
            \Log::error('Profile update failed: ' . $e->getMessage());
            return redirect()->route('user.profile')->with('error', 'Failed to update profile: ' . $e->getMessage());
        }
    }

    public function removePicture()
    {
        try {
            /** @var User $user */
            $user = Auth::user();

            // Delete profile picture if it exists
            if ($user->profile_picture && Storage::disk('public')->exists($user->profile_picture)) {
                Storage::disk('public')->delete($user->profile_picture);
            }
            $user->profile_picture = null;
            $user->save();

            return redirect()->route('user.profile')->with('success', 'Profile picture removed successfully');
        } catch (\Exception $e) {
            \Log::error('Profile picture removal failed: ' . $e->getMessage());
            return redirect()->route('user.profile')->with('error', 'Failed to remove profile picture: ' . $e->getMessage());
        }
    }

    public function removeCoverPhoto()
    {
        try {
            /** @var User $user */
            $user = Auth::user();

            // Delete cover photo if it exists
            if ($user->cover_photo && Storage::disk('public')->exists($user->cover_photo)) {
                Storage::disk('public')->delete($user->cover_photo);
            }
            $user->cover_photo = null;
            $user->save();

            return redirect()->route('user.profile')->with('success', 'Cover photo removed successfully');
        } catch (\Exception $e) {
            \Log::error('Cover photo removal failed: ' . $e->getMessage());
            return redirect()->route('user.profile')->with('error', 'Failed to remove cover photo: ' . $e->getMessage());
        }
    }
}