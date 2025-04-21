<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index(Request $request)
    {
        // Unchanged
        $totalUsers = User::count();
        $newUsersWeek = User::where('created_at', '>=', Carbon::now()->subDays(7))->count();
        $userGrowthPercent = $totalUsers ? round(($newUsersWeek / $totalUsers) * 100, 1) : 0;
        $activeUsers = User::where('is_active', true)->count();
        $activeUsersPercent = $totalUsers ? round(($activeUsers / $totalUsers) * 100, 1) : 0;
        $newUsersMonth = User::where('created_at', '>=', Carbon::now()->subDays(30))->count();
        $newUsersLastMonth = User::where('created_at', '>=', Carbon::now()->subDays(60))
            ->where('created_at', '<', Carbon::now()->subDays(30))
            ->count();
        $newUsersGrowthPercent = $newUsersLastMonth ? round((($newUsersMonth - $newUsersLastMonth) / $newUsersLastMonth) * 100, 1) : 100;

        $stats = [
            'total_users' => $totalUsers,
            'new_users_week' => $newUsersWeek,
            'user_growth_percent' => $userGrowthPercent,
            'active_users' => $activeUsers,
            'active_users_percent' => $activeUsersPercent,
            'new_users_month' => $newUsersMonth,
            'new_users_growth_percent' => $newUsersGrowthPercent,
        ];

        $query = User::withCount([
            'posts',
            'connections' => fn($query) => $query->where('status', 'accepted')
        ]);

        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($joinDate = $request->query('join_date')) {
            $query->where('created_at', '>=', match ($joinDate) {
                'today' => Carbon::today(),
                'week' => Carbon::now()->startOfWeek(),
                'month' => Carbon::now()->startOfMonth(),
                '3months' => Carbon::now()->subMonths(3),
                default => Carbon::now()->subYears(100),
            });
        }

        $users = $query->orderByDesc('created_at')->paginate(10);

        return view('admin.users', compact('stats', 'users'));
    }

    public function details(User $user)
    {
        try {
            if (!$user->exists) {
                Log::warning('User not found', ['user_id' => request()->route('user')]);
                return response()->json(['error' => 'User not found'], 404);
            }

            $user->loadCount([
                'posts' => fn($query) => $query->whereNotNull('id'),
                'connections' => fn($query) => $query->where('status', 'accepted')
            ]);

            return response()->json([
                'name' => $user->name ?? 'N/A',
                'username' => $user->username ?? 'N/A',
                'email' => $user->email ?? 'N/A',
                'bio' => $user->bio ?? 'N/A',
                'is_active' => $user->is_active ?? false,
                'posts_count' => $user->posts_count ?? 0,
                'connections_count' => $user->connections_count ?? 0,
                'created_at' => $user->created_at?->format('M d, Y') ?? 'N/A',
                'profile_picture' => $user->profile_picture && Storage::disk('public')->exists($user->profile_picture)
                    ? Storage::url($user->profile_picture)
                    : 'https://api.dicebear.com/6.x/avataaars/svg?seed=' . ($user->username ?? 'user' . ($user->id ?? 'unknown')),
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to fetch user details', [
                'user_id' => request()->route('user'),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => 'Failed to load user details: ' . $e->getMessage()], 500);
        }
    }

    public function suspend(User $user)
    {
        try {
            $user->update(['is_active' => !$user->is_active]);
            return redirect()->route('admin.users')->with('success', 'User ' . ($user->is_active ? 'activated' : 'suspended') . ' successfully.');
        } catch (\Exception $e) {
            Log::error('Failed to suspend user', ['user_id' => $user->id, 'error' => $e->getMessage()]);
            return redirect()->route('admin.users')->with('error', 'Failed to suspend user: ' . $e->getMessage());
        }
    }

    public function destroy(User $user)
    {
        try {
            // Debug: Inspect user and request
            dd([
                'user' => $user->toArray(),
                'user_exists' => $user->exists,
                'user_id' => request()->route('user'),
                'request_method' => request()->method(),
                'csrf_token' => request()->header('X-CSRF-TOKEN'),
                'auth_user' => auth()->user()?->toArray() ?? 'Guest'
            ]);

            if (!$user->exists) {
                Log::warning('User not found for deletion', ['user_id' => request()->route('user')]);
                return redirect()->route('admin.users')->with('error', 'User not found.');
            }

            $user->delete();
            return redirect()->route('admin.users')->with('success', 'User deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Failed to delete user', [
                'user_id' => request()->route('user'),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->route('admin.users')->with('error', 'Failed to delete user: ' . $e->getMessage());
        }
    }
}