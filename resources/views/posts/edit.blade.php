<!-- resources/views/posts/edit.blade.php -->

@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <div class="card custom-card">
            <div class="card-header">
                <h1>Edit Post</h1>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('posts.update', $post->id) }}">
                    @csrf
                    @method('PUT')
                    <div class="form-group mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" value="{{ $post->title }}" required>
                        @error('title')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="form-group mb-3">
                        <label for="content" class="form-label">Content</label>
                        <textarea name="content" id="content" class="form-control @error('content') is-invalid @enderror" rows="5" required>{{ $post->content }}</textarea>
                        @error('content')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </div>

    @section('styles')
    <style>
        .custom-card {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); /* Shadow for emphasis */
            border-radius: 10px;
            overflow: hidden; /* Ensures the shadow is uniformly applied */
        }

        .card-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
            padding: 1rem; /* Padding for spacing */
        }

        .card-body {
            padding: 2rem;
        }

        .form-label {
            font-weight: bold;
        }

        .invalid-feedback {
            display: block;
        }
    </style>
    @endsection
@endsection
