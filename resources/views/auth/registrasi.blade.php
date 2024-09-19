<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" href="{{ asset('bts/dist/css/bootstrap.min.css') }}">
    <style>
        body {
            background: linear-gradient(to right, #4facfe, #00f2fe);
            height: 100vh;
        }

        .login-form {
            background: rgba(255, 255, 255, 0.9);
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body>
    <div class="container d-flex align-items-center justify-content-center" style="height: 100vh;">
        <form class="login-form" style="width: 500px;" method="POST" action="{{ route('register.func') }}">
            @csrf
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $item)
                            <li>{{ $item }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
            <h2 class="text-center mb-4 text-primary">Registration Account</h2>
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}"
                    required>
            </div>
            <div class="mb-3">
                <label for="name" class="form-label">BIO</label>
                <input type="text" class="form-control" id="name" name="bio" value="{{ old('bio') }}"
                    required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email address</label>
                <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}"
                    required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" name="password" id="password" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Konfirmasi Password</label>
                <input type="password" class="form-control" id="password" name="konfirmasiPassword" required>
            </div>
            <button type="submit" class="btn btn-success w-100">Login</button>
            <div class="text-center mt-2">
                <span>Don't have an account? <a href="{{ route('login') }}" class="text-primary">Sign in</a></span>
            </div>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.min.js"></script>
</body>

</html>
