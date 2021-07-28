<?php

namespace App\Http\Requests;

use App\Models\Group;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateGroup extends FormRequest
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
        $group = Group::findOrFail(request()->segment(2));

        return [
            'name' => ['required', 'max:32', Rule::unique('groups')->ignore($group)],
            'description' => 'required|max:255',
            'tagfile' => ['required', 'max:36', Rule::unique('groups')->ignore($group)],
        ];
    }
}
