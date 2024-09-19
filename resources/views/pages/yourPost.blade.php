@extends('base.base')

@section('content')
    <div id="postsContainer" class="mt-5">
        @forelse ($data as $post)
            <div class="card mb-3 shadow-sm border-primary">
                <div class="card-body">
                    <h5 class="card-title text-primary">{{ $post->judul }}</h5>
                    @if ($post->gambar)
                        <img src="{{ asset('storage/' . $post->gambar) }}" class="img-fluid rounded mb-2" alt="Post Image"
                            style="max-width: 100%; height: auto;">
                    @endif
                    <p class="card-text mt-2"><strong>Hashtags:</strong> {{ $post->hashtag }}</p>
                    <p class="card-text"><strong>Description:</strong> {{ $post->deskripsi }}</p>
                    <small class="text-muted">Posted on {{ $post->created_at->format('d M Y H:i') }}</small>

                    <div class="mt-3">
                        <span class="badge bg-primary">{{ $post->like->count() }} Likes</span>
                        <form action="{{ route('likes.store') }}" method="POST" style="display:inline;">
                            @csrf
                            <input type="hidden" name="post_id" value="{{ $post->id }}">
                            <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                            <button type="submit" class="btn btn-outline-primary btn-sm">Like</button>
                        </form>
                    </div>

                    <hr>

                    <h6 class="mt-3">Comments:</h6>
                    <ul class="list-group mb-3">
                        @foreach ($post->comment as $comment)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>{{ $comment->user->name }}</strong>: {{ $comment->comments }}
                                    <small class="text-muted">- {{ $comment->created_at->diffForHumans() }}</small>

                                    @if ($comment->file)
                                        <div class="mt-2">
                                            <img src="{{ asset('storage/' . $comment->file) }}" class="img-fluid"
                                                alt="Comment Image" style="max-width: 200px;">
                                        </div>
                                    @endif
                                </div>
                                @if (auth()->user()->id == $comment->id_user)
                                    <div class="dropdown">
                                        <button class="btn btn-link p-0" type="button" id="dropdownMenuButton"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            &#8230; <!-- Simbol titik tiga -->
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <li>
                                                <a class="dropdown-item"
                                                    href="{{ route('comments.edit', $comment->id) }}">Edit</a>
                                            </li>
                                            <li>
                                                <form action="{{ route('comments.destroy', $comment->id) }}" method="POST"
                                                    style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item"
                                                        onclick="return confirm('Are you sure you want to delete this comment?');">Delete</button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                @endif
                            </li>
                        @endforeach
                    </ul>

                    <form action="{{ route('comments.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id_posts" value="{{ $post->id }}">
                        <input type="hidden" name="id_user" value="{{ Auth::user()->id }}">
                        <div class="input-group mb-3">
                            <input type="text" name="comments" class="form-control" placeholder="Add a comment" required>
                            <input type="file" name="file" class="form-control" accept="image/*">
                            <button class="btn btn-primary" type="submit">Submit</button>
                        </div>
                    </form>

                    <div class="mt-3">
                        <a href="{{ route('post.edit', $post->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('post.destroy', $post->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm"
                                onclick="return confirm('Are you sure you want to delete this post?');">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <p>No posts found.</p>
        @endforelse
    </div>
@endsection
