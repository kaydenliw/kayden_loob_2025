<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreApplicationRequest extends FormRequest
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
            'full_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'position' => 'required|string|max:255',
            'work_experience' => 'required|string|max:1000',
            'job_listing_id' => 'required|integer|exists:job_listings,id',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'full_name.required' => 'Full name is required',
            'phone.required' => 'Phone number is required',
            'email.email' => 'Please enter a valid email address',
            'position.required' => 'Position is required',
            'work_experience.required' => 'Work experience is required',
            'job_listing_id.required' => 'Job listing is required',
            'job_listing_id.exists' => 'Invalid job listing selected',
        ];
    }
}
