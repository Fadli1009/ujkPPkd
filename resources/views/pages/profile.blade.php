@extends('base.base')

@section('content')
    <div class="container mt-5">
        <h2 class="text-center mb-4">Profile User</h2>
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="text-center mb-4">
            @if (auth()->user()->avatar)
                <img src="{{ asset('storage/' . auth()->user()->avatar) }}" class="img-fluid rounded-circle"
                    alt="Profile Image" width="150">
            @else
                <img src="{{ asset('img/pp.jpg') }}" class="img-fluid rounded-circle" alt="Default Image" width="150">
            @endif
        </div>

        <form method="POST" action="{{ route('update.profile') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            @if (session('error'))
                {{ session('error') }}
            @endif
            <div class="mb-3">
                <label for="name" class="form-label">Nama</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ auth()->user()->name }}"
                    required>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="{{ auth()->user()->email }}"
                    required>
            </div>

            <div class="mb-3">
                <label for="bio" class="form-label">Bio</label>
                <input type="text" class="form-control" id="bio" name="bio" value="{{ auth()->user()->bio }}"
                    required>
            </div>

            <div class="mb-3">
                <label for="profile_image" class="form-label">Gambar Profil</label>
                <input type="file" class="form-control" id="profile_image" name="avatar" accept="image/*">
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password Baru (kosongkan jika tidak ingin mengganti)</label>
                <input type="password" class="form-control" id="password" name="password">
            </div>
            {{--
            <div class="mb-3">
                <label for="konfirmasi_password" class="form-label">Konfirmasi Password Baru</label>
                <input type="password" class="form-control" id="konfirmasi_password" name="konfirmasi_password">
            </div> --}}

            <button type="submit" class="btn btn-primary w-100">Simpan Perubahan</button>
        </form>

        <div class="text-center mt-4">
            <h5>Hapus Akun</h5>
            <form method="POST" action="users/delete/{{ Auth::user()->id }}">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Hapus Akun</button>
            </form>
        </div>
    </div>
@endsection
