<?php

namespace App\Repositories;

use App\Models\Post;
use Illuminate\Pagination\LengthAwarePaginator;

class PostRepository
{
    public function getPosts(array $filters = []): array
    {
        $query = Post::with(['author', 'tags'])->withCount('comments');
        $orderByFields = [];

        if (!empty($filters['search']['value'])) {
            $search = $filters['search']['value'];
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%$search%")
                    ->orWhereHas('author', function ($q2) use ($search) {
                        $q2->where('name', 'like', "%$search%");
                    });
            });
        }

        // Ordering
        if (!empty($filters['order'][0]['column'])) {
            $columns = [
                1 => 'title',
                2 => 'author.name',
                3 => 'published_at',
                4 => 'status',
                5 => 'comments_count',
            ];

            $columnIndex = $filters['order'][0]['column'];
            
            $direction = $filters['order'][0]['dir'] ?? 'desc';
            
            if (isset($columns[$columnIndex])) {
                if ($columns[$columnIndex] === 'author.name') {
                    $query->join('users', 'posts.user_id', '=', 'users.id')->select('posts.*');
                    $query->orderBy('users.name', $direction);
                } else {
                    $query->orderBy($columns[$columnIndex], $direction);
                }
            }
        } else {
            $query->latest('posts.created_at');
        }

        $recordsTotal = Post::count();
        $recordsFiltered = $query->count();

        // Pagination
        $start = $filters['start'] ?? 0;
        $length = $filters['length'] ?? 10;
        $data = $query->skip($start)->take($length)->get();

        // Format data for DataTables
        $result = [];
        $rowIndex = $start + 1;
        foreach ($data as $post) {
            $result[] = [
                'DT_RowIndex' => $rowIndex++,
                'title' => $post->title,
                'author' => ['name' => $post->author->name ?? ''],
                'published_at' => $post->published_at,
                'status' =>view('posts.partials.status', ['status' => $post->status])->render(),
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

    public function createPost(array $data): Post
    {
        $post = Post::create($data);
        if (!empty($data['tags'])) {
            $post->tags()->attach($data['tags']);
        }
        return $post;
    }

    public function updatePost(Post $post, array $data): Post
    {
        $post->update($data);
        if (isset($data['tags'])) {
            $post->tags()->sync($data['tags']);
        }
        return $post;
    }

    public function deletePost(Post $post): void
    {
        $post->delete();
    }
}
