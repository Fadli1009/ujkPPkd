<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CommentController extends Controller
{
    /**
     * Menampilkan daftar sumber daya.
     * Saat ini belum diimplementasikan.
     */
    public function index()
    {
        //
    }

    /**
     * Menampilkan formulir untuk membuat sumber daya baru.
     * Saat ini belum diimplementasikan.
     */
    public function create()
    {
        //
    }

    /**
     * Menyimpan sumber daya yang baru dibuat ke dalam penyimpanan.
     * Mengelola pembuatan komentar baru, termasuk unggahan file.
     */
    public function store(Request $request)
    {
        // Memeriksa apakah ada file yang diunggah
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            // Menghasilkan nama file unik menggunakan timestamp
            $filename = time() . '.' . $file->getClientOriginalExtension();
            // Menyimpan file di direktori 'comments' dalam disk publik
            $path = $file->storeAs('comments', $filename, 'public');
        }

        // Membuat entri komentar baru di database
        Comment::create([
            'id_user' => $request->id_user, // ID pengguna yang terkait dengan komentar
            'id_posts' => $request->id_posts, // ID postingan yang dikomentari
            'comments' => $request->comments, // Teks komentar
            'file' => $path ?? '' // Menyimpan jalur file jika ada, jika tidak kosong
        ]);

        // Mengarahkan kembali ke halaman sebelumnya
        return redirect()->back();
    }

    /**
     * Menampilkan sumber daya tertentu.
     * Saat ini belum diimplementasikan.
     */
    public function show(Comment $comment)
    {
        //
    }

    /**
     * Menampilkan formulir untuk mengedit sumber daya tertentu.
     * Saat ini belum diimplementasikan.
     */
    public function edit(Comment $comment)
    {
        //
    }

    /**
     * Memperbarui sumber daya tertentu di penyimpanan.
     * Mengelola pembaruan komentar yang ada, termasuk unggahan file opsional.
     */
    public function update(Request $request)
    {
        // Memvalidasi data yang masuk
        $request->validate([
            'id_comment' => 'required|exists:comments,id', // Memastikan komentar ada
            'comments' => 'required|string|max:255', // Memvalidasi teks komentar
            'file' => 'nullable|image|max:2048', // Memvalidasi unggahan file (opsional)
        ]);

        $id = $request->input('id_comment'); // Mengambil ID komentar dari permintaan
        $comment = Comment::findOrFail($id); // Mencari komentar berdasarkan ID, atau gagal jika tidak ditemukan
        $path = $comment->file; // Menyimpan jalur file yang ada

        // Mengelola unggahan file jika ada file baru yang diberikan
        if ($request->hasFile('file')) {
            // Menghapus file lama jika ada
            if ($comment->file) {
                Storage::disk('public')->delete($comment->file); // Menghapus file lama dari penyimpanan
            }
            $file = $request->file('file');
            // Menghasilkan nama file unik untuk file baru
            $filename = time() . '.' . $file->getClientOriginalExtension();
            // Menyimpan file baru
            $path = $file->storeAs('comments', $filename, 'public');
        }

        // Memperbarui komentar dengan data baru
        $comment->update([
            'comments' => $request->comments, // Memperbarui teks komentar
            'file' => $path, // Memperbarui jalur file
        ]);

        // Mengarahkan kembali dengan pesan sukses
        return redirect()->back()->with('success', 'Komentar berhasil diperbarui.');
    }

    /**
     * Menghapus sumber daya tertentu dari penyimpanan.
     * Mengelola penghapusan komentar dari database.
     */
    public function destroy(Comment $comment)
    {
        $comment->delete(); // Menghapus komentar
        return redirect()->back(); // Mengarahkan kembali setelah penghapusan
    }
}
