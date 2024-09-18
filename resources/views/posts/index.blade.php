

@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <!-- Create Post Section -->
        @auth
            @can('create-blog')
                <div class="row mb-4">
                    <div class="col-md-12">
                        <div class="card shadow-lg">
                            <div class="card-body">
                                <h1 class="mb-4">Blog Posts</h1>
                            </div>
                        </div>
                    </div>
                </div>
            @endcan
        @endauth

        <!-- Search Section -->
        <div class="row mb-4">
            <div class="col-md-12">
                <form id="searchForm" class="d-flex mb-4">
                    <input type="text" name="query" id="searchInput" class="form-control me-2"
                           placeholder="Search for posts..." required>
                    <button type="submit" id="searchButton" class="btn btn-outline-primary mx-3">Search</button>
                </form>
            </div>
        </div>
        <div class="row" id="postsContainer"></div>

        <!-- Blog Posts Section -->
        <div id="postsContainer" class="row">
            @foreach ($posts as $post)
                <div class="col-md-4 mb-4">
                    <div class="card shadow-lg">
                        <div class="card-body">
                            <h5 class="card-title">{{ $post->title }}</h5>
                            <p class="card-text">{{ Str::limit($post->content, 100, '...') }}</p>

                            <!-- View Post (Visible to Everyone) -->
                            <a href="{{ route('posts.show', $post->id) }}" class="btn btn-info btn-sm">View</a>

                            @auth
                                <!-- Edit Post (Visible to Authenticated Users with Edit Permission) -->
                                @can('edit-blog')
                                    <a href="{{ route('posts.edit', $post->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                @endcan

                                <!-- Delete Post (Visible to Authenticated Users with Delete Permission) -->
                                @can('delete-blog')
                                    <form method="POST" action="{{ route('posts.destroy', $post->id) }}" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                    </form>
                                @endcan
                            @endauth
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Attach event listener to the form submission
            document.getElementById('searchForm').addEventListener('submit', function(e) {
                e.preventDefault();  // Prevent the default form submission

                let query = document.getElementById('searchInput').value;  // Get the search input value

                if (query.trim() !== '') {  // Check if the query is not empty
                    // Make an AJAX request using Fetch API
                    fetch(`{{ route('posts.search') }}?query=${encodeURIComponent(query)}`, {
                        method: 'GET',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',  // For identifying as AJAX request
                        }
                    })
                    .then(response => response.json())  // Convert response to JSON
                    .then(data => {
                        let postsContainer = document.getElementById('postsContainer');
                        postsContainer.innerHTML = '';  // Clear any previous posts

                        // Check if posts were found
                        if (data.posts.length > 0) {
                            // Loop through posts and display them
                            data.posts.forEach(post => {
                                postsContainer.innerHTML += `
                                    <div class="col-md-4 mb-4">
                                        <div class="card shadow-lg">
                                            <div class="card-body">
                                                <h5 class="card-title">${post.title}</h5>
                                                <p class="card-text">${post.content.substring(0, 100)}...</p>
                                                <a href="/posts/${post.id}" class="btn btn-info btn-sm">View</a>
                                            </div>
                                        </div>
                                    </div>
                                `;
                            });
                        } else {
                            postsContainer.innerHTML = '<p>No posts found.</p>';  // If no posts found
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching posts:', error);  // Log any errors
                    });
                }
            });
        });
    </script>

@endsection
