@extends('base.base')

@section('content')
    <div id="postsContainer" class="mt-5">
        @foreach ($data as $post)
            <div class="card mb-3 shadow-sm border-primary">
                <div class="card-body">
                    <h5 class="card-title text-primary">{{ $post->judul }}</h5>
                    @if ($post->gambar)
                        <img src="{{ asset('storage/' . $post->gambar) }}" class="img-fluid rounded mb-2" alt="Post Image"
                            style="max-width: 100%; height: auto;">
                    @endif
                    <p class="card-text mt-2"><strong>Hashtags:</strong> {{ $post->hashtag }}</p>
                    <p class="card-text"><strong>Description:</strong> {{ $post->deskripsi }}</p>
                    <p class="card-text"><strong>Description:</strong> {{ $post->user->name }}</p>
                    <small class="text-muted">Posted on {{ $post->created_at->format('d M Y H:i') }}</small>


                    <hr>

                    <h6 class="mt-3">Comments:</h6>
                    <ul class="list-group mb-3">
                        @foreach ($post->comment as $comment)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>{{ $comment->user->name }}</strong>: {{ $comment->comments }}
                                    <small class="text-muted">- {{ $comment->created_at->diffForHumans() }}</small>

                                    <!-- Menampilkan gambar jika ada -->
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
                                                <button type="button" class="dropdown-item" data-bs-toggle="modal"
                                                    data-bs-target="#editCommentModal-{{ $comment->id }}">
                                                    Edit
                                                </button>

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
                            <!-- Edit Comment Modal -->
                            <div class="modal fade" id="editCommentModal-{{ $comment->id }}" tabindex="-1"
                                aria-labelledby="editCommentModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <form id="editCommentForm" method="POST" enctype="multipart/form-data"
                                        action="{{ route('comments.update', $comment->id) }}">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editCommentModalLabel">Edit Comment</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <input type="hidden" name="id_comment" value="{{ $comment->id }}">
                                                <input type="text" name="comments" class="form-control"
                                                    id="editCommentInput" required value="{{ $comment->comments }}">
                                                <input type="file" name="file" class="form-control mt-2"
                                                    accept="image/*">
                                                <input type="hidden" name="comment_id" id="commentIdInput">
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-primary">Save changes</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </ul>
                    <form action="{{ route('comments.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id_posts" value="{{ $post->id }}">
                        <input type="hidden" name="id_user" value="{{ Auth::user()->id }}">
                        <div class="input-group mb-3">
                            <input type="text" name="comments" class="form-control" placeholder="Add a comment">
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
        @endforeach
    </div>
    <script>
        function openEditModal(commentId, commentText) {
            document.getElementById('editCommentInput').value = commentText;
            document.getElementById('commentIdInput').value = commentId;
            document.getElementById('editCommentForm').action = '/comments/' + commentId; // Adjust this route as necessary
            $('#editCommentModal').modal('show');
        }
    </script>
@endsection
