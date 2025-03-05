<div class="navbar bg-base-200 shadow-lg">
    <div class="flex-none lg:hidden">
        <label for="my-drawer" class="btn btn-square btn-ghost">
            <i class="fas fa-bars"></i>
        </label>
    </div>
    <div class="flex-1">
        <span class="text-xl font-bold px-2">SocialAdmin</span>
    </div>
    <div class="flex-none gap-2">
        <div class="dropdown dropdown-end">
            <label tabindex="0" class="btn btn-ghost btn-circle">
                <div class="indicator">
                    <i class="fas fa-bell text-lg"></i>
                    <span class="badge badge-sm badge-primary indicator-item">8</span>
                </div>
            </label>
            <div tabindex="0" class="mt-3 z-[1] card card-compact dropdown-content w-52 bg-base-200 shadow">
                <div class="card-body">
                    <span class="font-bold text-lg">8 Notifications</span>
                    <span class="text-info">3 new user reports</span>
                    <span class="text-info">5 new posts flagged</span>
                    <div class="card-actions">
                        <button class="btn btn-primary btn-block">View all</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="dropdown dropdown-end">
            <label tabindex="0" class="btn btn-ghost btn-circle avatar">
                <div class="w-10 rounded-full">
                    <img src="https://api.dicebear.com/6.x/avataaars/svg?seed=admin" alt="Admin Avatar" />
                </div>
            </label>
            <ul tabindex="0" class="menu menu-sm dropdown-content mt-3 z-[1] p-2 shadow bg-base-200 rounded-box w-52">
                <li><a>Profile</a></li>
                <li><a>Settings</a></li>
                <li><a>Logout</a></li>
            </ul>
        </div>
    </div>
</div>