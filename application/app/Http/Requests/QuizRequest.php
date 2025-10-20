<?php

namespace App\Http\Requests;

use App\Rules\FileTypeValidate;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class QuizRequest extends FormRequest
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
            'name' => "required|regex:/^[a-z\-_\s]+$/i",
            'course_id' => "required|numeric",
            'time' => "numeric|nullable",
            'pass_mark' => "required",
            'total_question' => "required",
            'active_quiz' => "required|" . Rule::in(['1', '0']),
            'description' => "required"
        ];

        if (request()->method() == "POST") {
            $rules['image'] = ['required', 'max:3072', 'image', new FileTypeValidate(['jpg', 'jpeg', 'png', 'JPG', 'JPEG', 'PNG'])];
        }
        
        if (request()->method() == "PUT") {
            $rules['image'] = ['max:3072','image', new FileTypeValidate(['jpg', 'jpeg', 'png', 'JPG', 'JPEG', 'PNG'])];
        }
        return $rules;
    }
}
