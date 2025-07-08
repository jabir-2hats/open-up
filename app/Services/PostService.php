<?php

namespace App\Services;

use App\Models\Post;
use App\Repositories\PostRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PostService
{
    
    public function __construct(protected PostRepository $postRepository) {}

    /**
     * Get the list of posts, with optional filters.
     *
     * The filters supported are:
     * - search['value']: a search string to filter posts by title, author name, or tag name.
     * - order[0]['column'], order[0]['dir']: sorting order (asc/desc) for columns 'title', 'author.name', 'published_at', 'status', 'comments_count'.
     * - start, length: pagination offset and limit.
     *
     * @param array $filters
     * @return array
     */
    public function getPosts(array $filters = []): array
    {
        ['posts' => $posts, 'recordsTotal' => $recordsTotal, 'recordsFiltered' => $recordsFiltered] = $this->postRepository->getPosts($filters);

        $result = [];
        $start = $filters['start'] ?? 0;

        $rowIndex = $start + 1;
        foreach ($posts as $post) {
            $result[] = [
                'DT_RowIndex' => $rowIndex++,
                'title' => $post->title,
                'author' => ['name' => $post->author->name ?? ''],
                'published_at' => $post->published_at,
                'status' => view('posts.partials.status', ['status' => $post->status])->render(),
                'comments_count' => view('posts.partials.comments', ['post' => $post])->render(),
                'tags' => view('posts.partials.tags', ['tags' => $post->tags->pluck('name')])->render(),
                'actions' => view('posts.partials.actions', ['post' => $post])->render(),
            ];
        }

        return [
            'draw' => intval($filters['draw'] ?? 1),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $result,
        ];
        
    }

    /**
     * Creates a new post with the given data.
     *
     * Automatically sets the user_id and published_at fields.
     * If an 'image' key is present in the data, it will be stored and the 'image_url' field will be set.
     * The 'image' key will then be removed from the data.
     *
     * @param array $data The data to create the post with.
     * @return Post The newly created post instance.
     */
    public function createPost(array $data)
    {
        $data['user_id'] = Auth::id();
        $data['published_at'] = Carbon::now();

        if (isset($data['image'])) {
            $data['image_url'] = $data['image']->store('posts', 'public');
            unset($data['image']);
        }

        return $this->postRepository->createPost($data);
    }

    /**
     * Updates the specified post with the given data.
     *
     * Handles image updates by storing a new image and deleting the old one if present.
     * Also supports image removal based on the 'remove_image' flag.
     *
     * @param Post $post The post to update.
     * @param array $data The data to update the post with, including optional 'image' and 'remove_image'.
     * @return Post The updated post instance.
     */
    public function updatePost(Post $post, array $data)
    {
        if (isset($data['image'])) {
            if ($post->image_url) {
                Storage::disk('public')->delete($post->image_url);
            }
            $data['image_url'] = $data['image']->store('posts', 'public');
            unset($data['image']);
        }

        if (isset($data['remove_image']) && $data['remove_image'] === 'on') {
            if ($post->image_url) {
                Storage::disk('public')->delete($post->image_url);
            }
            $data['image_url'] = null;
        }

        return $this->postRepository->updatePost($post, $data);
    }

    /**
     * Deletes a post and its associated image, if any.
     *
     * @param  Post  $post
     * @return void
     */
    public function deletePost(Post $post)
    {
        if ($post->image_url) {
            Storage::disk('public')->delete($post->image_url);
        }
        $this->postRepository->deletePost($post);
    }
}
