<?php

namespace App\Http\Requests\Setup;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class PersonaDocumentoNew extends FormRequest
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
            'num_doc' => 'required|unique:persona_documentos',
            'persona_id' => 'required',
            'tipo_documento_id' => 'required',
            'img_file' => 'nullable|image|mimes:jpeg,png,jpg|max:8192',
            'img_url' => '',
        ];
    }

    public function failedValidation(Validator $validator) { 
        // write your bussiness logic here otherwise it will give same old JSON response
       throw new HttpResponseException(response()->json(['error' => ['message' => $validator->errors()->first(), 'code' => '422']], 422)); 
    }
}
