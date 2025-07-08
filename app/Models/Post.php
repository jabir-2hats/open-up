<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Post extends Model
{
    use HasFactory;


    protected $fillable = [
        'title',
        'content',
        'user_id',
        'published_at',
        'status',
        'image_url',
    ];

/**
 * Accessor and mutator for the post's status attribute.
 *
 * The accessor returns the status as 'Active' or 'Inactive' based on the boolean value.
 * The mutator sets the value to true if the input is 'Active', otherwise false.
 *
 * @return \Illuminate\Database\Eloquent\Casts\Attribute
 */

    public function status(): Attribute
    {
        return Attribute::make(
            get: fn($value) => $value ? 'Active' : 'Inactive',
            set: fn($value) => $value === 'Active' ? true : false
        );
    }

    // relations

    
    /**
     * The user who created the post.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the comments associated with the post.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class, 'post_id');
    }

    /**
     * Get the tags associated with the post.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'post_tag', 'post_id', 'tag_id');
    }

    // scopes

    /**
     * Search for posts by title or author name.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $search
     * @return void
     */
    #[Scope]
    public function search(Builder $query, string $search): void
    {
        $query->where(function ($q) use ($search) {
            $q->where('title', 'like', "%$search%")
                ->orWhereHas('author', function ($q2) use ($search) {
                    $q2->where('name', 'like', "%$search%");
                });
        });
    }

    /**
     * Scope a query to only include posts published between the given start and end dates.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $startDate
     * @param  string  $endDate
     * @return void
     */
    #[Scope]
    public function publishedBetween(Builder $query, string $startDate, string $endDate): void
    {
        $query->whereBetween('published_at', [$startDate, $endDate]);
    }

    /**
     * Scope a query to only include posts with all of the given tags.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  array  $tagIds
     * @return void
     */
    #[Scope]
    public function withTags(Builder $query, array $tagIds): void
    {
        $query->whereHas('tags', function ($q) use ($tagIds) {
            $q->whereIn('tags.id', $tagIds);
        }, '=', count($tagIds));
    }

    /**
     * Scope a query to only include posts with the given status.
     *
     * The status must be either 'Active' or 'Inactive'.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $status
     * @return void
     */
    #[Scope]
    public function byStatus(Builder $query, string $status): void
    {
        $query->where('status', $status === 'Active' ? true : false);
    }

    /**
     * Scope a query to only include posts with the given number of comments.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $operator  The operator to use for the comparison.
     *                              One of '=', '>', '<', '>=', '<='.
     * @param  int  $count  The number of comments.
     * @return void
     */
    #[Scope]
    public function hasComments(Builder $query, string $operator, int $count): void
    {
        $query->has('comments', $operator, $count);
    }

    /**
     * Scope a query to only include posts ordered by the given column and direction.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $column  The column to sort by.
     *                              Can be any column of the posts table,
     *                              or 'author.name' to sort by the author's name.
     * @param  string  $direction  The direction of the sort.
     *                              One of 'asc', 'desc'.
     * @return void
     */
    #[Scope]
    public function sortBy(Builder $query, string $column, string $direction): void
    {
        if ($column === 'author.name') {
            $query->join('users', 'posts.user_id', '=', 'users.id')
                ->select('posts.*')
                ->orderBy('users.name', $direction);
        } else {
            // For all other direct columns
            $query->orderBy($column, $direction);
        }
    }
}
