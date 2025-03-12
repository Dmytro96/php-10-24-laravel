<?php

namespace App\Http\Requests;

use App\Rules\PhoneNumber;
use Illuminate\Foundation\Http\FormRequest;

class UserCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
    
    public function messages(): array
    {
        return [
            'name.required' => 'це поле обовʼязкове',
            'name.string' => 'то має бути строкою',
            'name.max' => 'не більше 35 символів',
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:35'],
            'lastname' => ['required', 'string', 'max:35'],
            'email' => ['required', 'email', 'max:255', 'unique:users'],
            'phone' => ['required', 'string', 'min:10', 'max:15'], // new PhoneNumber
            'birthdate' => ['nullable', 'date', 'before_or_equal:-18 years'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];
    }
}
