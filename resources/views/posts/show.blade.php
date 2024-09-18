@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <!-- Post Details -->
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">{{ $post->title }}</h5>
                <p class="card-text">{{ $post->content }}</p>
            </div>
        </div>

        <!-- Comment Form -->
        @auth
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Add a Comment</h5>
                    <form method="POST" action="{{ route('comments.store', $post->id) }}">
                        @csrf
                        <div class="form-group mb-3">
                            <textarea name="content" class="form-control" rows="3" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        @endauth
{{-- without logged in user can comment here --}}
        <!-- Display Comments -->
        @if($post->comments->isNotEmpty())
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Comments</h5>
                    @foreach($post->comments as $comment)
                        <div class="border-bottom pb-2 mb-2">
                            <p><strong>{{ $comment->user->name }}:</strong> {{ $comment->content }}</p>
                            @auth
                                <form method="POST" action="{{ route('comments.like', $comment->id) }}" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-info btn-sm">Like ({{ $comment->likes->count() }})</button>
                                </form>
                                <form method="POST" action="{{ route('comments.share', $comment->id) }}" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-sm">Share ({{ $comment->shares->count() }})</button>
                                </form>
                                @can('delete', $comment)
                                    <form method="POST" action="{{ route('comments.destroy', $comment->id) }}" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                    </form>
                                @endcan
                            @endauth
                        </div>
                    @endforeach
                </div>
            </div>

        @else
            <p>No comments yet.</p>
        @endif
    </div>
    <script>
        $(document).ready(function() {
            // AJAX for comment submission
            $('#comment-form').on('submit', function(e) {
                e.preventDefault();

                // Prepare form data
                var formData = {
                    '_token': '{{ csrf_token() }}',
                    'content': $('#content').val()
                };

                // AJAX request to submit the comment
                $.ajax({
                    type: 'POST',
                    url: '{{ route('comments.store', $post->id) }}',
                    data: formData,
                    success: function(response) {
                        // Append the new comment to the comment list
                        $('#comment-list').append(
                            '<li><strong>' + response.user.name + ':</strong> ' + response.comment.content + '</li>'
                        );

                        // Clear the comment form
                        $('#content').val('');
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                    }
                });
            });
        });
    </script>

@endsection
<!-- Added jQuery and AJAX script -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
{{-- <script>
    $(document).ready(function() {
        // AJAX for comment submission
        $('#comment-form').on('submit', function(e) {
            e.preventDefault();

            // Prepare form data
            var formData = {
                '_token': '{{ csrf_token() }}',
                'content': $('#content').val()
            };

            // AJAX request to submit the comment
            $.ajax({
                type: 'POST',
                url: '{{ route('comments.store', $post->id) }}',
                data: formData,
                success: function(response) {
                    // Append the new comment to the comment list
                    $('#comment-list').append('<li>' + response.comment.content + ' - <strong>' + response.user.name + '</strong></li>');

                    // Clear the comment form
                    $('#content').val('');
                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                }
            });
        });
    });
</script> --}}


