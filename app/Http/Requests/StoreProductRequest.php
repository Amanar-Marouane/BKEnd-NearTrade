<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
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
            'user_id' => 'required|exists:users,id|uuid',
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'category_id' => 'required|exists:categories,id|uuid',
            'status' => 'required|in:New,Used',
            'price' => 'required|integer|min:1',
            'images' => 'required|array|min:1',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif',
            'location' => 'required|string',
        ];
    }

    public function messages()
    {
        return [
            'user_id.required' => 'User ID is required.',
            'user_id.exists' => 'The specified user does not exist.',
            'user_id.uuid' => 'The User ID must be a valid UUID.',

            'name.required' => 'Product name is required.',
            'name.string' => 'Product name must be a string.',
            'name.max' => 'Product name must not exceed 255 characters.',

            'description.required' => 'Product description is required.',
            'description.string' => 'Product description must be a string.',
            'description.max' => 'Product description must not exceed 1000 characters.',

            'category_id.required' => 'Category is required.',
            'category_id.exists' => 'The specified category does not exist.',
            'category_id.uuid' => 'The Category ID must be a valid UUID.',

            'status.required' => 'Product status is required.',
            'status.in' => 'The status must be either "New" or "Used".',

            'price.required' => 'Product price is required.',
            'price.integer' => 'Price must be a valid integer.',
            'price.min' => 'Price must be at least 1.',

            'images.required' => 'Please upload at least one product image.',
            'images.array' => 'The images must be uploaded as a collection.',
            'images.min' => 'Please upload at least one product image.',
            'images.*.image' => 'The uploaded file must be an image.',
            'images.*.mimes' => 'Only JPEG, PNG, JPG and GIF image formats are allowed.',

            'location.required' => 'Product location is required.',
            'location.string' => 'Product location must be a string.',
        ];
    }
}
