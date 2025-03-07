{{-- <div class="stat bg-base-200 rounded-box shadow">
    <div class="stat-figure text-@color">
        <i class="fas @icon text-3xl"></i>
    </div>
    <div class="stat-title">@slot('title')</div>
    <div class="stat-value text-@color">@slot('value')</div>
    <div class="stat-desc">@slot('description')</div>
</div> --}}

<div class="stats shadow bg-base-200">
    <div class="stat">
        <div class="stat-figure text-{{ $color }}">
            <i class="fas {{ $icon }} text-3xl"></i>
        </div>
        <div class="stat-title">{{ $title }}</div>
        <div class="stat-value text-{{ $color }}">{{ $value }}</div>
        <div class="stat-desc">{{ $description }}</div>
    </div>
</div>
