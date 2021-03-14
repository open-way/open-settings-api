<?php

namespace App\Http\Controllers\Setup;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\PersonaVirtual;
use Exception;
use App\Http\Resources\Setup\PersonaVirtual as PersonaVirtualResource;
use App\Http\Requests\Setup\PersonaVirtual as RequestsPersonaVirtual;
use Illuminate\Support\Facades\DB;

class PersonaVirtualController extends Controller
{

    public function getByPersonaId(Request $request, $id)
    {
        $jResponse = [];
        try{
            $jResponse = PersonaVirtualResource::collection(PersonaVirtual::where('persona_id', $id)->get());
        }catch(Exception $e){                    
            return $this->errorResponse($e->getMessage(), 400);
        }
        return $this->successResponse($jResponse, 200);
    }

    public function store(RequestsPersonaVirtual $request)
    {
        $validated = $request->validated();
        $jResponse = [];
        DB::beginTransaction();
        try{
            $personaVirtual = PersonaVirtual::create($validated);
            $jResponse = new PersonaVirtualResource($personaVirtual);
            DB::commit();
        }catch(Exception $e){
            DB::rollback();
            return $this->errorResponse($e->getMessage(), 400);
        }
        return $this->successResponse($jResponse, 200);
    }

    public function update(RequestsPersonaVirtual $request, $id)
    {
        $validated = $request->validated();
        $jResponse = [];
        DB::beginTransaction();
        try{
            
            $personaVirtual = PersonaVirtual::findOrFail($id);
            $personaVirtual->update($validated);
            $jResponse = new PersonaVirtualResource($personaVirtual);
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
            $personaVirtual = PersonaVirtual::findOrFail($id);
            $personaVirtual->delete();
            DB::commit();
        }catch(Exception $e){ 
            DB::rollback();                   
            return $this->errorResponse($e->getMessage(), 400);
        }
        return $this->successResponse($jResponse, 200);
    }
}
