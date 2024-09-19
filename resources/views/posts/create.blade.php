@extends('base.base')

@section('content')
    <div class="card mt-5">
        <div class="card-header">
            <h3 class="card-title">Add Post</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('post.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group mb-4">
                    <div class="form-floating">
                        <input type="file" name="gambar" class="form-control" id="image">
                        <label for="image">Gambar/File</label>
                    </div>
                </div>
                <div class="form-group mb-4">
                    <div class="form-floating">
                        <input type="text" name="hashtag" id="floatingHashtag" class="form-control">
                        <label for="floatingHashtag">Hashtag</label>
                    </div>
                </div>
                <div class="form-group mb-4">
                    <div class="form-floating">
                        <input type="text" name="judul" id="floatingHashtag" class="form-control" name="judul">
                        <label for="floatingHashtag">Judul</label>
                    </div>
                </div>
                <div class="form-group mb-4">
                    <div class="form-floating">
                        <textarea class="form-control" name="deskripsi" placeholder="Leave a caption here" id="floatingTextarea" maxlength="255"
                            oninput="updateCounter()"></textarea>
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
