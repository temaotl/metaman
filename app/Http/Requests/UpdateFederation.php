<?php

namespace App\Http\Requests;

use App\Models\Federation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateFederation extends FormRequest
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
        $federation = Federation::withTrashed()->findOrFail(request()->segment(2));

        return [
            'name' => ['sometimes', 'required', 'max:32', Rule::unique('federations')->ignore($federation)],
            'description' => ['sometimes', 'required', 'max:255'],
            'xml_id' => ['sometimes', 'required', 'max:128', Rule::unique('federations')->ignore($federation)],
            'xml_name' => ['sometimes', 'required', 'max:128', Rule::unique('federations')->ignore($federation)],
            'filters' => ['sometimes', 'required', 'max:255', Rule::unique('federations')->ignore($federation)],
        ];
    }
}
