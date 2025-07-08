<?php

namespace App\Http\Controllers;

use App\Http\Requests\Post\DeletePostRequest;
use App\Http\Requests\Post\GetPostRequest;
use App\Http\Requests\Post\StorePostRequest;
use App\Http\Requests\Post\UpdatePostRequest;
use App\Models\Post;
use App\Services\PostService;
use App\Services\TagService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PostController extends Controller
{

    public function __construct(protected PostService $postService) {}

    /**
     * Show the posts index page.
     *
     * @return \Illuminate\View\View
     */
    public function index(): View
    {
        return view('posts.index');
    }

    /**
     * Retrieve a list of posts with optional filtering, ordering, and pagination.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPosts(Request $request): JsonResponse
    {
        $posts = $this->postService->getPosts($request->all());

        return response()->json($posts);
    }

    /**
     * Show the form for creating a new post.
     *
     * @param TagService $tagService
     * @return \Illuminate\View\View
     */
    public function create(TagService $tagService): View
    {
        $tags = $tagService->getTags();

        return view('posts.create', ['tags' => $tags]);
    }

    /**
     * Store a newly created post in storage.
     *
     * @param  StorePostRequest  $request The request containing the post data.
     * @return \Illuminate\Http\RedirectResponse The redirect response object.
     */
    public function store(StorePostRequest $request): RedirectResponse
    {
        $data = $request->validated();
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image');
        }
        $this->postService->createPost($data);

        return redirect()->route('posts.index')->with('success', 'Post created successfully.');
    }

    /**
     * Display the specified post with its related author, tags, and comments.
     *
     * @param Post $post The post instance to display.
     * @return \Illuminate\View\View The view displaying the post details.
     */
    public function show(Post $post): View
    {
        return view('posts.show', [
            'post' => $post->load(['author', 'tags', 'comments']),
        ]);
    }

    /**
     * Show the form for editing the specified post.
     *
     * @param  Post  $post The post instance to edit.
     * @param  TagService  $tagService The service to retrieve tags.
     * @return \Illuminate\View\View The view displaying the edit form.
     */
    public function edit(Post $post, TagService $tagService): View
    {
        $tags = $tagService->getTags();

        return view('posts.edit', [
            'post' => $post->load(['tags']),
            'tags' => $tags,
        ]);
    }

    /**
     * Update the specified post in storage.
     *
     * @param  UpdatePostRequest  $request The request containing the post data.
     * @param  Post  $post The post instance to update.
     * @return \Illuminate\Http\RedirectResponse The redirect response object.
     */

    public function update(UpdatePostRequest $request, Post $post): RedirectResponse
    {

        $data = $request->validated();
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image');
        }
        $this->postService->updatePost($post, $data);

        return redirect()->route('posts.index')->with('success', 'Post updated successfully.');
    }

    /**
     * Remove the specified post from storage.
     *
     * @param  DeletePostRequest  $request The request containing the post to delete.
     * @param  Post  $post The post object to be deleted.
     * @return \Illuminate\Http\RedirectResponse The redirect response object.
     */

    public function destroy(DeletePostRequest $request, Post $post): RedirectResponse
    {
        $this->postService->deletePost($post);

        return redirect()->route('posts.index')->with('success', 'Post deleted successfully.');
    }
}
