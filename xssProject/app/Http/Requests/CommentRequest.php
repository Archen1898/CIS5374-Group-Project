<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CommentRequest extends FormRequest
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
            'post_id' => 'required|exists:posts,id',
            'comment' => 'required|string',
            'user_id' => 'required|exists:users,id',
        ];
    }

    public function messages(): array
    {
        return [
            'post_id.required' => 'Post ID is required.',
            'post_id.exists' => 'Post ID does not exist.',
            'comment.required' => 'Comment is required.',
            'comment.string' => 'Comment must be a string.',
            'user_id.required' => 'User ID is required.',
            'user_id.exists' => 'User ID does not exist.',
        ];
    }
}
