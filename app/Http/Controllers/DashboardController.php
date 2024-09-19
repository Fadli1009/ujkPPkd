<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Post;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $data = Post::with('like')->get(); // Mengambil semua post dengan relasi like
        $userLikes = Like::where('id_user', Auth::user()->id)->pluck('id_post')->toArray(); // Mendapatkan semua post_id yang disukai pengguna

        return view('pages.dashboard', compact('data', 'userLikes'));
    }
}
