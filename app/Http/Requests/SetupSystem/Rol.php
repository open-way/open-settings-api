<?php

namespace App\Http\Requests\SetupSystem;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class Rol extends FormRequest
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
            'activo' => 'required|max:1',
            'nombre' => 'required|max:150',
        ];
    }

    public function failedValidation(Validator $validator) { 
        // write your bussiness logic here otherwise it will give same old JSON response
       throw new HttpResponseException(response()->json(['error' => ['message' => $validator->errors()->first(), 'code' => '422']], 422)); 
    }
}
