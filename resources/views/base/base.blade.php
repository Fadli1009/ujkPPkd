<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('bts/dist/css/bootstrap.min.css') }}">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body>
    @include('base.partials.navbar')
    <div class="container">
        @yield('content')
    </div>

    <script src="{{ asset('bts/dist/js/bootstrap.bundle.min.js') }}"></script>
</body>

</html>
