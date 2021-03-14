<?php

namespace App\Http\Requests\Setup;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class User extends FormRequest
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
        return [
            'is_superuser' => 'required|max:1',
            'active' => 'required|max:1',
            'name' => 'required|max:191',
            // 'email' => 'required|max:191|unique:users',
            // 'email' => 'required|max:191|email|unique:users,email,'.$this->id,
            'img_file' => 'nullable|image|mimes:jpeg,png,jpg|max:8192',
            'img_url' => '',
        ];
    }

    public function failedValidation(Validator $validator) { 
        // write your bussiness logic here otherwise it will give same old JSON response
       throw new HttpResponseException(response()->json(['error' => ['message' => $validator->errors()->first(), 'code' => '422']], 422)); 
    }
}
