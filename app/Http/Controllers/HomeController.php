<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class HomeController extends Controller
{
    public static function middleware(): array
    {
        return [
            new Middleware('auth', except: ['show', 'index']),
        ];
    }

    public function __invoke()
    {
        $ids = '';

        if (!Auth::user()) {
            return redirect()->route('login');
        } else {
            $ids = Auth::user()->following->pluck('id')->toArray();
        }

        $posts = Post::whereIn('user_id', $ids)->latest()->paginate(20);

        return view('home', [
            'posts' => $posts
        ]);
    }
}
