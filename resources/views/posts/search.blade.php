@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <!-- Create Post Section -->
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card shadow-lg">
                    <div class="card-body">
                        <h1 class="mb-4 ms-3">Search Results</h1>
                        <a href="{{ route('posts.create') }}" class="btn btn-primary">Create Post</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search Form -->
        <div class="row mb-4">
            <div class="col-md-12">
                <form id="search-form" class="d-flex">
                    <input type="text" name="query" id="search-query" class="form-control me-2" placeholder="Search for posts..." value="{{ request('query') }}">
                    <button type="submit" class="btn btn-outline-primary">Search</button>
                </form>
            </div>
        </div>

        <!-- Blog Posts Section -->
        <div id="search-results" class="row">
            @if($posts->isEmpty())
                <div class="col-md-12">
                    <p>No posts found.</p>
                </div>
            @else
                @foreach ($posts as $post)
                    <div class="col-md-4 mb-4">
                        <div class="card shadow-lg">
                            <div class="card-body">
                                <h5 class="card-title">{{ $post->title }}</h5>
                                <p class="card-text">{{ Str::limit($post->content, 100, '...') }}</p>
                                <a href="{{ route('posts.show', $post->id) }}" class="btn btn-info btn-sm">View</a>
                                <a href="{{ route('posts.edit', $post->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                <form method="POST" action="{{ route('posts.destroy', $post->id) }}" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>

    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#search-form').on('submit', function(e) {
                e.preventDefault(); // Prevent the form from submitting the traditional way

                let query = $('#search-query').val(); // Get the query value

                $.ajax({
                    url: "{{ route('posts.search') }}",
                    method: "GET",
                    data: { query: query },
                    success: function(response) {
                        $('#search-results').html(response);
                    },
                    error: function(xhr) {
                        console.log('An error occurred:', xhr.responseText);
                    }
                });
            });
        });
    </script>
@endsection
