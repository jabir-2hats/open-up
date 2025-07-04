<?php

namespace App\Http\Requests\Post;

use Illuminate\Foundation\Http\FormRequest;

class GetPostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'nullable|string|max:255',
            'title_order' => 'nullable|in:asc,desc',
            'author' => 'nullable|string|max:255',
            'author_order' => 'nullable|in:asc,desc',
            'published_at' => 'nullable|date',
            'published_at_order' => 'nullable|in:asc,desc',
            'status' => 'nullable|in:Active,Inactive',
            'comments_count' => 'nullable|integer',
            'comments_count_order' => 'nullable|in:asc,desc',
            'tags' => 'nullable|array',
            'tags.*' => 'nullable|exists:tags,id'
        ];
    }
}
