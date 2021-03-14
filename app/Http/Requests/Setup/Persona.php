<?php

namespace App\Http\Requests\Setup;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class Persona extends FormRequest
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
            'nombres' => 'required|max:200|min:1',
            'ap_paterno' => 'required',
            'ap_materno' => '',
            // 'num_doc' => 'unique:personas',
            'fecha_nac' => '',
            'sexo' => 'required',
            'estado_civil' => 'required',
            // 'celular' => '',
            'ubigeo_id' => 'required',
        ];
    }

    public function failedValidation(Validator $validator) { 
        //write your bussiness logic here otherwise it will give same old JSON response
       throw new HttpResponseException(response()->json(['error' => ['message' => $validator->errors()->first(), 'code' => '422']], 422)); 
    }
}
