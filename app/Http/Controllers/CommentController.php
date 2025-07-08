<?php

namespace App\Http\Controllers;

use App\Http\Requests\Comment\CreateCommentRequest;
use App\Http\Requests\Comment\DeleteCommentRequest;
use App\Http\Requests\Comment\UpdateCommentRequest;
use App\Models\Comment;
use App\Services\CommentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class CommentController extends Controller
{
    public function __construct(protected CommentService $commentService) {}
    

    /**
     * Display a listing of the resource.
     *
     * @param Request $request The HTTP request object.
     * @return \Illuminate\Http\JsonResponse The JSON response containing the comments.
     */
    public function index(Request $request): JsonResponse
    {
        $postId = $request->query('post_id');
        $comments = $this->commentService->getCommentsForPost($postId);

        return response()->json([
            'comments' => $comments,
        ]);
    }


    /**
     * Store a newly created comment in storage.
     *
     * @param  CreateCommentRequest  $request The request containing the comment data.
     * @return \Illuminate\Http\RedirectResponse The redirect response object.
     */
    public function store(CreateCommentRequest $request): RedirectResponse
    {
        $this->commentService->createComment($request->validated());

        return redirect()->back()->with('success', 'Comment added successfully.');
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateCommentRequest  $request The request containing the comment data.
     * @param  Comment  $comment The comment object to be updated.
     * @return \Illuminate\Http\RedirectResponse The redirect response object.
     */
    public function update(UpdateCommentRequest $request, Comment $comment): RedirectResponse
    {
        $this->commentService->updateComment($comment, $request->validated());

        return redirect()->back();
    }

 
    /**
     * Remove the specified resource from storage.
     *
     * @param  DeleteCommentRequest  $request The request containing the comment to delete.
     * @param  Comment  $comment The comment object to be deleted.
     * @return \Illuminate\Http\RedirectResponse The redirect response object.
     */
    public function destroy(DeleteCommentRequest $request, Comment $comment): RedirectResponse
    {
        $this->commentService->deleteComment($comment);

        return redirect()->back();
    }
}
