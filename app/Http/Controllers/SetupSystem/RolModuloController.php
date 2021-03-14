<?php

namespace App\Http\Controllers\SetupSystem;

use App\Models\RolModulo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Exception;

class RolModuloController extends Controller
{
    public function index()
    {
        //
    }

    public function store(Request $request)
    {
        $jResponse = [];
        $data = Input::all();
        $validador = Validator::make($data,  $this->rulesRolModulo());
        if($validador->fails()) {
            return $this->errorResponse($validador->errors()->first(), 300);
        }
        try{
            $jResponse = RolModulo::create($data);
        }catch(Exception $e){                    
            return $this->errorResponse($e->getMessage(), 400);
        }
        return $this->successResponse($jResponse, 201);
    }

    private function rulesRolModulo(){
        return [
            'modulo_id' => 'required',
            'rol_id' => 'required',
            'activo' => 'required',
        ];
    }

    public function show($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    // public function destroyByRolIdAndModuloId(Request $request, $id)
    // {
    //     $jResponse = [];
    //     $modulo_id = $request->modulo_id;
    //     $rol_id = $request->rol_id;
    //     try{
    //         RolModulo::where('modulo_id', '=', $modulo_id)->where('rol_id', '=', $rol_id)->delete();
    //     }catch(Exception $e){                    
    //         return $this->errorResponse($e->getMessage(), 400);
    //     }
    //     return $this->successResponse($jResponse, 200);
    // }

    public function destroy($id)
    {
        // RolModulo::find($id)->delete();
        return response()->json([], 200);
    }
}
