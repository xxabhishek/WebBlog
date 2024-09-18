<?php


namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\like;
use App\Models\Post;
use App\Models\PostModel;
use App\Models\share;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PostController extends Controller
{
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function index()
    {
        $posts = Post::all();
        return view('posts.index', compact('posts'));
    }

    public function create()
    {
        return view('posts.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
        ]);

        Post::create([
            'title' => $request->title,
            'content' => $request->content,
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('posts.index');
    }

    public function show(Post $post)
    {
        return view('posts.show', compact('post'));
    }

    public function edit(Post $post)
    {
        return view('posts.edit', compact('post'));
    }

    public function update(Request $request, Post $post)
    {
        $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
        ]);

        $post->update([
            'title' => $request->title,
            'content' => $request->content,
        ]);

        return redirect()->route('posts.index');
    }


    public function destroy($id)
    {
        $post = Post::find($id);

        if (Auth::user()->can('delete', $post)) {
            $post->delete();
            dd($post);
            return redirect()->route('posts.index')->with('success', 'Post deleted.');
        }

        return redirect()->route('posts.index')->with('error', 'Unauthorized.');
    }


    public function storeComment(Request $request, $postId)
    {
        $request->validate([
            'content' => 'required'
        ]);

        $post = Post::findOrFail($postId);

        // Create the comment
        $comment = $post->comments()->create([
            'content' => $request->content,
            'user_id' => auth()->id(),
        ]);

        // Return a JSON response
        return redirect()->route('posts.show', $postId)->with('success', 'Comment added successfully!');
    }

    public function destroyComment($id)
    {
        $comment = Comment::findOrFail($id);

        // Check if the logged-in user is the owner of the comment
        if (auth()->user()->id !== $comment->user_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Delete the comment
        $comment->delete();

        return response()->json(['success' => 'Comment deleted successfully']);
    }

    public function likeComment($commentId)
    {
        $comment = Comment::findOrFail($commentId);

        if (Like::where('comment_id', $commentId)->where('user_id', Auth::id())->exists()) {
            return response()->json(['message' => 'You already liked this comment'], 400);
        }

        like::create([
            'comment_id' => $commentId,
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('posts.index')->with('success', 'Comment liked successfully');
    }

    // Share a comment
    public function shareComment($commentId)
    {
        $comment = Comment::findOrFail($commentId);

        share::create([
            'comment_id' => $commentId,
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('posts.index')->with('success', 'Comment Shared successfully');
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
        dd($query); // This will dump the query parameter and stop execution

        // Your search logic
        $posts = Post::where('title', 'like', "%{$query}%")
                     ->orWhere('content', 'like', "%{$query}%")
                     ->get();

        return view('posts.index', ['posts' => $posts]);
    }

}
