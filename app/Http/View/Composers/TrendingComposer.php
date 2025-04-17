<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;
use App\Models\Hashtag;

class TrendingComposer
{
    public function compose(View $view)
    {
        $trendingHashtags = Hashtag::withCount('posts')
            ->orderByDesc('posts_count')
            ->take(4)
            ->get();

        $view->with('trendingHashtags', $trendingHashtags);
    }
}
