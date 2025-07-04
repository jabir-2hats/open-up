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

    public function getPosts(array $filters = [])
    {
        return $this->postRepository->getPosts($filters);
    }

    public function getPostsForDataTable(array $filters = []): array
    {
        return $this->postRepository->getPostsForDataTable($filters);
    }

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

    public function deletePost(Post $post)
    {
        if ($post->image_url) {
            Storage::disk('public')->delete($post->image_url);
        }
        $this->postRepository->deletePost($post);
    }
}
