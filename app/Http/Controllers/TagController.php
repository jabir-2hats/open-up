<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Services\TagService;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class TagController extends Controller
{

    public function __construct(protected TagService $tagService) {}

    /**
     * Returns a collection of tags.
     *
     * @return Collection<int, Tag>
     */
    public function getTags(): Collection
    {
        return $this->tagService->getTags();
    }
}
