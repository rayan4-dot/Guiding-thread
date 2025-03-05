<div class="alert bg-base-300 shadow-lg">
    <div class="avatar">
        <div class="w-8 rounded-full">
            <img src="@slot('avatar')" alt="User Avatar" />
        </div>
    </div>
    <div class="flex-1">
        <div class="font-bold text-sm">@slot('title')</div>
        <div class="text-xs opacity-70">@slot('description')</div>
    </div>
    <div class="flex gap-1">
        <button class="btn btn-xs btn-success btn-square"><i class="fas fa-check"></i></button>
        <button class="btn btn-xs btn-error btn-square"><i class="fas fa-ban"></i></button>
    </div>
</div>