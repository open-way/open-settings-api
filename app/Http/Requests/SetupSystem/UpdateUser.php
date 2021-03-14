<?php

namespace App\Http\Requests\SetupSystem;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class UpdateUser extends FormRequest
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
            // 'email' => 'required|string|email|unique:users',
            'email' => 'required|max:191|email|unique:users,email,'.$this->id,
        ];
    }

    public function failedValidation(Validator $validator) { 
        // write your bussiness logic here otherwise it will give same old JSON response
       throw new HttpResponseException(response()->json(['error' => ['message' => $validator->errors()->first(), 'code' => '422']], 422)); 
    }
}
