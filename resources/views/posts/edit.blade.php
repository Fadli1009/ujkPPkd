@extends('base.base')

@section('content')
    <div class="card mt-5">
        <div class="card-header">
            <h3 class="card-title">{{ $post->judul }}</h3>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            <form action="{{ route('post.update', $post->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @if (isset($post))
                    @method('PUT')
                @endif

                <div class="form-group mb-4">
                    <div class="form-floating">
                        <input type="file" name="gambar" class="form-control" id="image">
                        <label for="image">Gambar/File (Kosongkan jika tidak ingin mengganti)</label>
                    </div>
                    @if (isset($post) && $post->gambar)
                        <div class="mt-3">
                            <img src="{{ asset('storage/' . $post->gambar) }}" class="img-fluid" alt="Current Image"
                                style="max-width: 200px;">
                        </div>
                    @endif
                </div>

                <div class="form-group mb-4">
                    <div class="form-floating">
                        <input type="text" name="hashtag" id="floatingHashtag" class="form-control"
                            value="{{ isset($post) ? $post->hashtag : '' }}">
                        <label for="floatingHashtag">Hashtag</label>
                    </div>
                </div>

                <div class="form-group mb-4">
                    <div class="form-floating">
                        <input type="text" name="judul" id="floatingJudul" class="form-control"
                            value="{{ isset($post) ? $post->judul : '' }}">
                        <label for="floatingJudul">Judul</label>
                    </div>
                </div>

                <div class="form-group mb-4">
                    <div class="form-floating">
                        <textarea class="form-control" name="deskripsi" placeholder="Leave a caption here" id="floatingTextarea" maxlength="255"
                            oninput="updateCounter()">{{ isset($post) ? $post->deskripsi : '' }}</textarea>
                        <label for="floatingTextarea">Captions</label>
                    </div>
                    <div class="mt-2 text-end text-secondary">
                        <span id="charCount">255</span> karakter tersisa
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100">Submit Post</button>
            </form>
        </div>
    </div>

    <script>
        function updateCounter() {
            const textarea = document.getElementById('floatingTextarea');
            const charCount = document.getElementById('charCount');
            const maxLength = 255;
            const remaining = maxLength - textarea.value.length;

            charCount.textContent = remaining;

        }
    </script>
@endsection
