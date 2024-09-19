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
     * Menampilkan daftar semua postingan pengguna saat ini.
     */
    public function index()
    {
        $user = Auth::user(); // Mengambil informasi pengguna yang sedang login
        $data = Post::where('id_user', $user->id)->get(); // Mengambil semua postingan oleh pengguna
        return view('pages.yourPost', compact('data')); // Mengirim data ke view
    }

    /**
     * Menampilkan formulir untuk membuat postingan baru.
     */
    public function create()
    {
        return view('posts.create'); // Mengarahkan ke view untuk membuat postingan
    }

    /**
     * Menyimpan postingan baru ke dalam penyimpanan.
     */
    public function store(Request $request)
    {
        // Memeriksa apakah ada file gambar yang diunggah
        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            // Menghasilkan nama file unik menggunakan timestamp
            $namaFile = time() . '.' . $file->getClientOriginalExtension();
            // Menyimpan gambar di direktori 'images' pada disk publik
            $path = $file->storeAs('images', $namaFile, 'public');
        }

        // Membuat entri postingan baru di database
        Post::create([
            'id_user' => Auth::user()->id, // ID pengguna yang membuat postingan
            'hashtag' => $request->hashtag, // Hashtag terkait postingan
            'judul' => $request->judul, // Judul postingan
            'gambar' => $path ?? '', // Jalur gambar yang diunggah
            'deskripsi' => $request->deskripsi // Deskripsi postingan
        ]);

        // Mengarahkan kembali ke halaman daftar postingan
        return redirect()->route('post.index');
    }

    /**
     * Menampilkan postingan tertentu.
     * Saat ini belum diimplementasikan.
     */
    public function show(Post $post)
    {
        //
    }

    /**
     * Menampilkan formulir untuk mengedit postingan tertentu.
     */
    public function edit(Post $post)
    {
        return view('posts.edit', compact('post')); // Mengirim data postingan ke view edit
    }

    /**
     * Memperbarui postingan tertentu di penyimpanan.
     */
    public function update(Request $request, $id)
    {
        $post = Post::find($id); // Mencari postingan berdasarkan ID

        // Pastikan postingan ditemukan
        if (!$post) {
            return redirect()->back()->with('error', 'Post not found.'); // Mengarahkan kembali jika tidak ditemukan
        }

        // Menghapus gambar lama jika ada file baru yang diunggah
        if ($request->hasFile('gambar')) {
            // Hapus gambar lama jika ada
            if ($post->gambar) {
                Storage::delete('public/' . $post->gambar); // Menghapus gambar lama dari penyimpanan
            }

            // Unggah gambar baru
            $file = $request->file('gambar');
            $namaFile = time() . '.' . $file->getClientOriginalExtension(); // Nama file baru
            $path = $file->storeAs('images', $namaFile, 'public'); // Menyimpan gambar baru

            // Memperbarui jalur gambar di model
            $post->gambar = $path;
        }

        // Memperbarui data postingan
        $post->update([
            'hashtag' => $request->hashtag, // Memperbarui hashtag
            'judul' => $request->judul, // Memperbarui judul
            'deskripsi' => $request->deskripsi, // Memperbarui deskripsi
        ]);

        // Mengarahkan kembali dengan pesan sukses
        return redirect()->back()->with('success', 'Berhasil diubah');
    }

    /**
     * Menghapus postingan tertentu dari penyimpanan.
     */
    public function destroy(Post $post)
    {
        // Menghapus postingan
        $post->delete();
        // Menghapus gambar dari penyimpanan
        Storage::delete('public/' . $post->gambar);
        // Mengarahkan kembali dengan pesan sukses
        return redirect()->back()->with('success', 'Berhasil dihapus');
    }

    /**
     * Memfilter postingan berdasarkan hashtag atau komentar.
     */
    public function filterPost(Request $request)
    {
        $hashtag = $request->input('cari'); // Mengambil nilai pencarian dari permintaan

        // Mengambil postingan berdasarkan hashtag
        $data = Post::with('comment')
            ->where('hashtag', $hashtag) // Memfilter berdasarkan hashtag
            ->orWhereHas('comment', function ($query) use ($hashtag) {
                $query->where('comments', 'like', '%' . $hashtag . '%'); // Memfilter komentar yang mengandung hashtag
            })
            ->get();

        // Mengarahkan ke view filter dengan data yang sesuai
        return view('posts.filter', compact('data'));
    }
}
