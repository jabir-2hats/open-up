<?php

namespace App\Http\Controllers;

use App\Http\Requests\Post\DeletePostRequest;
use App\Http\Requests\Post\GetPostRequest;
use App\Http\Requests\Post\StorePostRequest;
use App\Http\Requests\Post\UpdatePostRequest;
use App\Models\Post;
use App\Services\PostService;
use App\Services\TagService;
use Illuminate\Http\Request;

class PostController extends Controller
{

    public function __construct(protected PostService $postService) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('posts.index');
    }

    public function getPosts(Request $request)
    {
        $posts = $this->postService->getPosts($request->all());
        
        return response()->json($posts);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(TagService $tagService)
    {
        $tags = $tagService->getTags();

        return view('posts.create', ['tags' => $tags]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request)
    {
        $data = $request->validated();
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image');
        }
        $this->postService->createPost($data);
        
        return redirect()->route('posts.index')->with('success', 'Post created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        return view('posts.show', [
            'post' => $post->load(['author', 'tags', 'comments']),  
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post, TagService $tagService)
    {
        $tags = $tagService->getTags();

        return view('posts.edit', [
            'post' => $post->load(['tags']),
            'tags' => $tags,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, Post $post)
    {

        $data = $request->validated();
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image');
        }
        $this->postService->updatePost($post, $data);
        
        return redirect()->route('posts.index')->with('success', 'Post updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DeletePostRequest $request, Post $post)
    {
        $this->postService->deletePost($post);

        return redirect()->route('posts.index')->with('success', 'Post deleted successfully.');
    }
}
