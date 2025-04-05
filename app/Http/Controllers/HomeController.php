<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $posts = Post::with('user')->latest()->take(10)->get(); // Fetch latest 10 posts from all users
        return view('user.home', compact('posts'));
    }
}
