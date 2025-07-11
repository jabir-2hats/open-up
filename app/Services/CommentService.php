<?php

namespace App\Services;

use App\Models\Comment;
use Illuminate\Support\Facades\Auth;

class CommentService
{
    /**
     * Retrieve comments for a post.
     *
     * @param int $postId
     * @return \Illuminate\Database\Eloquent\Collection|Comment[]
     */
    public function getCommentsForPost($postId)
    {
        return Comment::where('post_id', $postId)->with('user')->get();
    }


    public function createComment(array $data)
    {
        $comment = new Comment();
        $comment->post_id = $data['post_id'];
        $comment->user_id = Auth::id();
        $comment->content = $data['content'];
        $comment->save();

        return $comment;
    }
    /**
     * Update a comment.
     *
     * @param Comment $comment
     * @param array   $data
     *
     * @return Comment
     */

    public function updateComment(Comment $comment, array $data)
    {
        $comment->content = $data['content'];
        $comment->save();
        
        return $comment;
    }

    /**
     * Delete a comment.
     *
     * @param Comment $comment
     */
    public function deleteComment(Comment $comment)
    {
        $comment->delete();
    }
}
