<?php

namespace App\Http\Requests\Admin\Products;

use App\Enums\Permissions\ProductEnum;
use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can(ProductEnum::PUBLISH->value);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'min:2', 'max:255', 'unique:products,title'],
            'SKU' => ['required', 'string', 'min:2', 'max:255', 'unique:products,SKU'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:1'],
            'discount' => ['nullable', 'numeric', 'min:1', 'max:99'],
            'quantity' => ['required', 'numeric', 'min:1'],
            'thumbnail' => ['required', 'image', 'mimes:jpeg,png,jpg'],
            'images.*' => ['image', 'mimes:jpeg,png,jpg'],
            'categories.*' => ['required', 'numeric', 'exists:categories,id'],
        ];
    }
}
