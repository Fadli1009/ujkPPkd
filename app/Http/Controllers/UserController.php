<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * Menampilkan halaman profil pengguna.
     */
    public function profile(Request $request)
    {
        return view('pages.profile'); // Mengarahkan ke view profil
    }

    /**
     * Memperbarui data profil pengguna.
     */
    public function updateProfile(Request $request)
    {
        $cekUser = Auth::user()->id; // Mengambil ID pengguna yang sedang login
        $user = User::find($cekUser); // Mencari pengguna berdasarkan ID

        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255', // Nama wajib diisi
            'email' => 'required|email|max:255|unique:users,email,' . $user->id, // Email harus unik, kecuali email saat ini
            'password' => 'nullable|min:6', // Password opsional, minimal 6 karakter
            'bio' => 'required|string', // Bio wajib diisi
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048' // Avatar opsional, dengan batasan ukuran dan tipe
        ]);

        // Mengupdate avatar jika ada file baru
        if ($request->hasFile('avatar')) {
            // Hapus gambar lama jika ada
            if ($user->avatar) {
                Storage::delete('public/' . $user->avatar); // Menghapus gambar lama dari penyimpanan
            }

            // Simpan gambar baru
            $file = $request->file('avatar');
            $namaFile = time() . '_' . $file->getClientOriginalName(); // Menghasilkan nama file unik
            $path = $file->storeAs('images', $namaFile, 'public'); // Menyimpan gambar baru
            $user->avatar = $path; // Memperbarui jalur avatar di model
        }

        // Memperbarui data pengguna
        $user->name = $request->name; // Memperbarui nama
        $user->email = $request->email; // Memperbarui email
        $user->bio = $request->bio; // Memperbarui bio

        // Memperbarui password jika ada
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password); // Menghash password baru
        }

        // Simpan perubahan
        $user->save(); // Menyimpan perubahan ke database

        return redirect()->back()->with('success', 'Profile updated successfully!'); // Mengarahkan kembali dengan pesan sukses
    }

    /**
     * Menghapus pengguna berdasarkan ID.
     */
    public function delete($id)
    {
        $user = User::find($id); // Mencari pengguna berdasarkan ID
        $user->delete(); // Menghapus pengguna dari database
        return redirect()->route('login'); // Mengarahkan ke halaman login setelah dihapus
    }
}
