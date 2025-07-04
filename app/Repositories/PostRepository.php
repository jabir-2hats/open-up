<?php

namespace App\Repositories;

use App\Models\Post;
use Illuminate\Pagination\LengthAwarePaginator;

class PostRepository
{
    /**
     * Retrieve a list of posts with optional filtering, sorting, and pagination.
     *
     * This method supports the following filters:
     * - search['value']: Filter posts based on title, author name, or tag name.
     * - order[0]['column'], order[0]['dir']: Specify sorting column and direction ('asc' or 'desc').
     *   Supported columns are: 'title', 'author.name', 'published_at', 'status', 'comments_count'.
     * - start, length: Pagination parameters to specify offset and limit of the result set.
     * - draw: Optional integer for DataTables draw counter.
     *
     * The returned array is formatted for DataTables and includes:
     * - draw: The draw counter.
     * - recordsTotal: Total number of records.
     * - recordsFiltered: Number of records after filtering.
     * - data: Array of posts data including title, author, published date, status, comments count, tags, and actions.
     *
     * @param array $filters An associative array of filters for querying posts.
     * @return array The resulting posts data formatted for DataTables.
     */
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
                    })
                    ->orWhereHas('tags', function ($q3) use ($search) {
                        $q3->where('name', 'like', "%$search%");
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
        $length = $filters['length'] ?? 12;
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
     * Create a new post with the given data.
     *
     * This method creates a post record in the database and attaches any associated tags.
     *
     * @param array $data The data to create the post with, including optional 'tags' for tag associations.
     * @return Post The newly created post instance.
     */

    public function createPost(array $data): Post
    {
        $post = Post::create($data);
        if (!empty($data['tags'])) {
            $post->tags()->attach($data['tags']);
        }

        return $post;
    }

    /**
     * Update an existing post with the given data.
     *
     * This method updates the post record in the database with the provided data
     * and synchronizes any associated tags.
     *
     * @param Post $post The post instance to update.
     * @param array $data The data to update the post with, including optional 'tags' for tag associations.
     * @return Post The updated post instance.
     */

    public function updatePost(Post $post, array $data): Post
    {
        $post->update($data);
        if (isset($data['tags'])) {
            $post->tags()->sync($data['tags']);
        }

        return $post;
    }

    /**
     * Delete a post and its associated image, if any.
     *
     * @param  Post  $post
     * @return void
     */
    public function deletePost(Post $post): void
    {
        $post->delete();
    }
}
