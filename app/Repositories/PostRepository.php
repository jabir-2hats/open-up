<?php

namespace App\Repositories;

use App\Models\Post;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

class PostRepository
{

    /**
     * Retrieves a list of posts with optional filtering, ordering, and pagination.
     * 
     * The supported filters are:
     * - search['value']: a search string to filter posts by title, author name, or tag name.
     * - startDate, endDate: publication date range.
     * - tags: an array of tag IDs.
     * - status: a status value (Active, Inactive).
     * - commentsCount: the number of comments a post should have.
     * - commentsCountOperator: an operator (=, <, >) to compare the comments count with.
     * 
     * The supported ordering columns are:
     * - title
     * - author.name
     * - published_at
     * - status
     * - comments_count
     * 
     * @param array $filters
     * @return array
     */
    public function getPosts(array $filters = []): array
    {
        $query = Post::with(['author', 'tags'])->withCount('comments');

        // Filtering
        $query = $this->filterPosts($query, $filters);

        // Ordering
        $query = $this->orderPosts($query, $filters);

        $recordsTotal = Post::count();
        $recordsFiltered = $query->count();

        // Pagination
        $start = $filters['start'] ?? 0;
        $length = $filters['length'] ?? 10;
        $posts = $query->skip($start)->take($length)->get();

        return [
            'posts' => $posts,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
        ];
    }

    /**
     * Filter posts based on given filters.
     *
     * Supported filters are:
     * - search['value']: a search string to filter posts by title, author name, or tag name.
     * - start_date, end_date: publication date range.
     * - tags: an array of tag IDs.
     * - status: a status value (Active, Inactive).
     * - comments_count: the number of comments a post should have.
     * - comments_count_operator: an operator (=, <, >) to compare the comments count with.
     *
     * @param array $filters
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function filterPosts(Builder $query, array $filters): Builder
    {
        if (!empty($filters['search']['value'])) {
            $search = $filters['search']['value'];
            $query->search($search);
        }

        if (!empty($filters['start_date']) && !empty($filters['end_date'])) {
            $query->publishedBetween($filters['start_date'], $filters['end_date']);
        }

        if (!empty($filters['tags']) && is_array($filters['tags'])) {
            $query->withTags($filters['tags']);
        }

        if (!empty($filters['status'])) {
            $query->byStatus($filters['status']);
        }

        if (($filters['comments_count'] !== null || $filters['comments_count'] === 0) && !empty($filters['comments_count_operator'])) {
            $query->hasComments($filters['comments_count_operator'], $filters['comments_count']);
        }

        return $query;
    }

    /**
     * Order posts based on given filters.
     *
     * Supported filters are:
     * - order[0]['column']: the column to sort by (1: title, 2: author name, 3: published at, 4: status, 5: comments count).
     * - order[0]['dir']: the direction of the sort (asc/desc).
     *
     * If no order is specified, the posts are sorted by the creation date in descending order.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param array $filters
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function orderPosts(Builder $query, array $filters): Builder
    {
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
                $columnName = $columns[$columnIndex];
                $query->sortBy($columnName, $direction);
            }
        } else {
            $query->latest('posts.created_at');
        }

        return $query;
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
