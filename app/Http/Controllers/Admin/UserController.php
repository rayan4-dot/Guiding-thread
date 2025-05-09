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


        $growth = $this->getGrowthData();
        $demographics = $this->getDemographicsData();

        return view('admin.users', compact('stats', 'users', 'growth', 'demographics'));
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

    protected function getGrowthData()
    {
        try {
            $data = User::selectRaw('DATE(created_at) as date, COUNT(*) as new_users')
                ->where('created_at', '>=', Carbon::now()->subDays(30))
                ->groupBy('date')
                ->orderBy('date')
                ->get();

            $labels = $data->pluck('date')->map(fn($date) => Carbon::parse($date)->format('M d'))->toArray();
            $newUsers = $data->pluck('new_users')->toArray();
            $activeUsers = User::selectRaw('DATE(created_at) as date, COUNT(*) as active_users')
                ->where('created_at', '>=', Carbon::now()->subDays(30))
                ->where('is_active', true)
                ->groupBy('date')
                ->orderBy('date')
                ->pluck('active_users')
                ->toArray();

            return [
                'labels' => $labels ?: ['No data'],
                'new_users' => $newUsers ?: [0],
                'active_users' => $activeUsers ?: [0]
            ];
        } catch (\Exception $e) {
            Log::error('Failed to fetch growth data', ['error' => $e->getMessage()]);
            return [
                'labels' => ['Error'],
                'new_users' => [0],
                'active_users' => [0]
            ];
        }
    }

    protected function getDemographicsData()
    {
        try {
            $data = User::selectRaw('role_id, COUNT(*) as count')
                ->groupBy('role_id')
                ->get();

            $labels = $data->pluck('role_id')->map(fn($id) => 'Role ' . $id)->toArray();
            $counts = $data->pluck('count')->toArray();

            return [
                'labels' => $labels ?: ['No data'],
                'data' => $counts ?: [0]
            ];
        } catch (\Exception $e) {
            Log::error('Failed to fetch demographics data', ['error' => $e->getMessage()]);
            return [
                'labels' => ['No data'],
                'data' => [0]
            ];
        }
    }



    public function export(Request $request)
    {
        try {
            $query = User::withCount([
                'posts',
                'connections' => fn($query) => $query->where('status', 'accepted')
            ]);

            // Apply search filter
            if ($search = $request->query('search')) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('username', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            }

            // Apply join date filter
            if ($joinDate = $request->query('join_date')) {
                $query->where('created_at', '>=', match ($joinDate) {
                    'today' => Carbon::today(),
                    'week' => Carbon::now()->startOfWeek(),
                    'month' => Carbon::now()->startOfMonth(),
                    '3months' => Carbon::now()->subMonths(3),
                    default => Carbon::now()->subYears(100),
                });
            }

            $users = $query->orderByDesc('created_at')->get();

            // Generate CSV
            $filename = 'users_export_' . Carbon::now()->format('Ymd_His') . '.csv';
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => "attachment; filename=\"$filename\"",
            ];

            $columns = ['ID', 'Name', 'Username', 'Email', 'Bio', 'Is Active', 'Posts Count', 'Connections Count', 'Joined At'];

            $callback = function () use ($users, $columns) {
                $file = fopen('php://output', 'w');
                fputcsv($file, $columns);

                foreach ($users as $user) {
                    fputcsv($file, [
                        $user->id,
                        $user->name ?? 'N/A',
                        $user->username ?? 'N/A',
                        $user->email ?? 'N/A',
                        $user->bio ?? 'N/A',
                        $user->is_active ? 'Active' : 'Suspended',
                        $user->posts_count ?? 0,
                        $user->connections_count ?? 0,
                        $user->created_at ? $user->created_at->format('Y-m-d H:i:s') : 'N/A',
                    ]);
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        } catch (\Exception $e) {
            Log::error('Failed to export users', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->route('admin.users')->with('error', 'Failed to export users: ' . $e->getMessage());
        }
    }
}