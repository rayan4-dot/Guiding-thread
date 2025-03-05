<div class="drawer-side">
    <label for="my-drawer" class="drawer-overlay"></label>
    <aside class="bg-base-200 w-64 h-screen">
        <div class="px-4 py-8 border-b border-base-300">
            <div class="flex flex-col items-center gap-2 mb-8 text-center">
                <div class="avatar">
                    <div class="w-16 rounded-full ring ring-primary ring-offset-base-100 ring-offset-2">
                        <img src="https://api.dicebear.com/6.x/avataaars/svg?seed=admin" alt="Admin Avatar" />
                    </div>
                </div>
                <div class="space-y-2">
                    <div class="font-bold text-2xl tracking-wide">Admin Panel</div>
                    <div class="text-lg opacity-70">Social Media Manager</div>
                </div>
            </div>
        </div>
        <div class="px-4 py-6 flex-1">
            <ul class="menu menu-md bg-base-200 rounded-none space-y-2">
                <!-- <li><a href="{{ route('welcome') }}" class="{{ request()->routeIs('welcome') ? 'active' : '' }}"><i class="fas fa-chart-line mr-2"></i>Dashboard</a></li> -->
                <li><a href="{{ route('users') }}" class="{{ request()->routeIs('users') ? 'active' : '' }}"><i class="fas fa-users mr-2"></i>Users</a></li>
                <li><a href="{{ route('post') }}" class="{{ request()->routeIs('post') ? 'active' : '' }}"><i class="fas fa-file-alt mr-2"></i>Posts</a></li>
                <li><a href="{{ route('notifications') }}" class="{{ request()->routeIs('notifications') ? 'active' : '' }}"><i class="fas fa-bell mr-2"></i>Notifications</a></li>
            </ul>
        </div>
        <div class="px-4 py-6 border-t border-base-300">
            <div class="flex flex-col items-start gap-4">
                <div class="flex items-center justify-between w-full">
                    <div>
                        <div class="text-sm font-bold">System Status</div>
                        <div class="text-xs opacity-70">All systems operational</div>
                    </div>
                    <div class="badge badge-success">Online</div>
                </div>
                <button class="btn btn-outline btn-error btn-sm w-full"><i class="fas fa-sign-out-alt mr-2"></i>Logout</button>
            </div>
        </div>
    </aside>
</div>