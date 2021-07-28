<?php

namespace App\Http\Requests;

use App\Models\Category;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCategory extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $category = Category::findOrFail(request()->segment(2));

        return [
            'name' => ['required', 'max:32', Rule::unique('categories')->ignore($category)],
            'description' => 'required|max:255',
            'tagfile' => ['required', 'max:36', Rule::unique('categories')->ignore($category)],
        ];
    }
}
