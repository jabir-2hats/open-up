<?php

namespace App\Http\Requests\Post;

use Illuminate\Foundation\Http\FormRequest;

class GetPostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * 
     *  @return bool
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
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'status' => 'nullable|in:Active,Inactive',
            'comments_count' => 'nullable|integer',
            'comments_count_operator' => 'nullable|in:=,<,>,=<,>=,!=',
            'tags' => 'nullable|array',
            'tags.*' => 'nullable|exists:tags,id'
        ];
    }
}
