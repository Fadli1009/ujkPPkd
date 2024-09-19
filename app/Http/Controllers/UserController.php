<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function profile(Request  $request)
    {
        return view('pages.profile');
    }
    public function updateProfile(Request $request)
    {
        $cekUser = Auth::user()->id;
        $user = User::find($cekUser);

        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id, // tambahkan pengecekan unik untuk email
            'password' => 'nullable|min:6',
            'bio' => 'required|string',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048' // tambahkan batasan ukuran dan tipe
        ]);
        // Mengupdate avatar jika ada file baru
        if ($request->hasFile('avatar')) {
            // Hapus gambar lama jika ada
            if ($user->avatar) {
                Storage::delete('public/' . $user->avatar);
            }

            // Simpan gambar baru
            $file = $request->file('avatar');
            $namaFile = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('images', $namaFile, 'public');
            $user->avatar = $path;
        }

        // Update data pengguna
        $user->name = $request->name;
        $user->email = $request->email;
        $user->bio = $request->bio;

        // Update password jika ada
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        // Simpan perubahan
        $user->save();

        return redirect()->back()->with('success', 'Profile updated successfully!');
    }
    public function delete($id)
    {
        $user = User::find($id);
        $user->delete();
        return redirect()->route('login');
    }
}
