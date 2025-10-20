<?php

namespace App\Http\Requests;

use App\Rules\FileTypeValidate;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class CourseRequest extends FormRequest
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
        $rules = [
            // 'name' => "required|regex:/^[a-z\\-_\\s]+$/i",
            // 'name' => 'required|regex:/^[\p{L}\p{N}\s\-_]+$/u',
            'name' => 'required',
            'price' => "required|numeric|gt:discount",
            'discount' => "numeric|nullable|lt:price",
            'categories' => "required|array|min:1",
            'categories.*' => "required|exists:categories,id",
            'learn_description' => "required",
            'curriculum' => "nullable",
            'description' => "required", // Changed from required to nullable
            'course_outline' => "array",
            'course_outline.*' => "required",
            'status' => "required|" . Rule::in(['1', '0']),
            // Add validation for About the Course fields
            'duration' => "nullable|string",
            'assignments_count' => "nullable|integer|min:0",
            'access_duration' => "nullable|string",
            'course_faqs' => "nullable|array",
            'course_faqs.*.question' => "required_with:course_faqs|string",
            'course_faqs.*.answer' => "required_with:course_faqs|string",
            'preview_video' => 'nullable|mimes:mp4,mov,avi,wmv|max:51200', 

        ];

        return $rules;
    }
}
