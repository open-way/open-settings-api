<?php

namespace App\Http\Controllers\SetupSystem;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;
use App\Models\ModuloAccion;
use App\Http\Resources\SetupSystem\ModuloAccion as ModuloAccionResource;
use App\Http\Requests\SetupSystem\ModuloAccion as RequestsModuloAccion;
use Illuminate\Support\Facades\DB;

class ModuloAccionController extends Controller
{
    public function index(Request $request)
    {
        $jResponse = [];
        $user_logged = $request->user();
        try{
            $modulo_id = $request->modulo_id;
            $jResponse = ModuloAccion::where('modulo_id', $modulo_id)->get();
        }catch(Exception $e){                    
            return $this->errorResponse($e->getMessage(), 400);
        }
        return $this->successResponse($jResponse, 200);
    }

    public function store(RequestsModuloAccion $request)
    // public function store(Request $request)
    {
        $validated = $request->validated();
        $jResponse = [];
        DB::beginTransaction();
        try{
            $moduloAccion = ModuloAccion::create($validated);
            $jResponse = new ModuloAccionResource($moduloAccion);
            DB::commit();
        }catch(Exception $e){
            DB::rollback();
            return $this->errorResponse($e->getMessage(), 400);
        }
        return $this->successResponse($jResponse, 201);
    }

    public function update(RequestsModuloAccion $request, $id)
    {
        $validated = $request->validated();
        $jResponse = [];
        DB::beginTransaction();
        try{
            
            $moduloAccion = ModuloAccion::findOrFail($id);
            $moduloAccion->update($validated);
            $jResponse = new ModuloAccionResource($moduloAccion);
            DB::commit();
        }catch(Exception $e){
            DB::rollback();
            return $this->errorResponse($e->getMessage(), 400);
        }
        return $this->successResponse($jResponse, 200);
    }

    public function destroy($id)
    {
        $jResponse = [];
        DB::beginTransaction();
        try{
            $moduloAccion = ModuloAccion::findOrFail($id);
            $moduloAccion->delete();
            DB::commit();
        }catch(Exception $e){ 
            DB::rollback();                   
            return $this->errorResponse($e->getMessage(), 400);
        }
        return $this->successResponse($jResponse, 200);
    }

}
