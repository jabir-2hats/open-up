<?php

namespace App\Services;

use App\Models\Tag;

class TagService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Retrieve all tags from the database.
     *
     * @return \Illuminate\Database\Eloquent\Collection<int, Tag>
     */
    public function getTags()
    {
        return Tag::pluck('name', 'id');
    }

    // Static method removed. Use dependency injection and pass tags from controller to view instead.
}
