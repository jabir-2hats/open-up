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

    public function getTags()
    {
        return Tag::get();
    }

    // Static method removed. Use dependency injection and pass tags from controller to view instead.
}
