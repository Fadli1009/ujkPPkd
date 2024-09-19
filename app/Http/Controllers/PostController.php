<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $data = Post::where('id_user', $user->id)->get();
        return view('pages.yourPost', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $namaFile = time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('images', $namaFile, 'public');
        }
        Post::create([
            'id_user' => Auth::user()->id,
            'hashtag' => $request->hashtag,
            'judul' => $request->judul,
            'gambar' => $path,
            'deskripsi' => $request->deskripsi
        ]);
        return redirect()->route('post.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        return view('posts.edit', compact('post'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $post = Post::find($id);

        // Pastikan post ditemukan
        if (!$post) {
            return redirect()->back()->with('error', 'Post not found.');
        }

        // Menghapus gambar lama jika ada file baru yang diupload
        if ($request->hasFile('gambar')) {
            // Hapus gambar lama
            if ($post->gambar) {
                Storage::delete('public/' . $post->gambar);
            }

            // Upload gambar baru
            $file = $request->file('gambar');
            $namaFile = time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('images', $namaFile, 'public');

            // Update gambar path
            $post->gambar = $path;
        }

        // Update data post
        $post->update([
            'hashtag' => $request->hashtag,
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
        ]);

        return redirect()->back()->with('success', 'Berhasil diubah');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        $post->delete();
        Storage::delete('public/' . $post->gambar);
        return redirect()->back()->with('success', 'Berhasil dihapus');
    }

    public function filterPost(Request $request)
    {
        // $data = Post::with('hashtag', $request->input('cari'))->get();
        $hashtag = $request->input('cari');

        // Mengambil posts berdasarkan hashtag
        $data = Post::with('comment')
            ->where('hashtag', $hashtag)
            ->orWhereHas('comment', function ($query) use ($hashtag) {
                $query->where('comments', 'like', '%' . $hashtag . '%');
            })
            ->get();

        // $data = $posts->merge($comment);
        // dd($data);
        return view('posts.filter', compact('data'));
    }
}
