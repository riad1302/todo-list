<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Response;
use function Termwind\render;
use Inertia\Inertia;

class PostController extends Controller
{
    public function index(Request $request): Response
    {
        $query = Post::query();

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $posts = $query->orderBy('id', 'DESC')->paginate(5)->withQueryString();

        return Inertia::render('posts/Index', [
            'posts' => $posts,
            'filters' => $request->only('search'),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('posts/PostForm');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'status' => 'required|boolean',
        ]);

        Post::create($validated);

        return redirect()->route('posts.index')->with('success', 'Post created successfully!');
    }

    public function edit(Post $post): Response
    {
        return Inertia::render('posts/PostForm', ['post' => $post]);
    }

    public function update(Request $request, Post $post): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'status' => 'required|boolean',
        ]);

        $post->update($validated);

        return redirect()->route('posts.index')->with('success', 'Post updated!');
    }
}
