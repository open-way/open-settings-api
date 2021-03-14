<?php

namespace App\Http\Controllers\Setup;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\PersonaTelefono;
use Exception;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\Setup\PersonaTelefono as PersonaTelefonoResource;
use App\Http\Requests\Setup\PersonaTelefono as RequestsPersonaTelefono;

class PersonaTelefonoController extends Controller
{
    public function getByPersonaId(Request $request, $id)
    {
        $jResponse = [];
        try{
            // $jResponse = ;
            $jResponse = PersonaTelefonoResource::collection(PersonaTelefono::where('persona_id', $id)->get());

        }catch(Exception $e){                    
            return $this->errorResponse($e->getMessage(), 400);
        }
        return $this->successResponse($jResponse, 200);
    }

    public function store(RequestsPersonaTelefono $request)
    {
        $validated = $request->validated();
        $jResponse = [];
        DB::beginTransaction();
        try{
            $personaTelefono = PersonaTelefono::create($validated);
            $jResponse = new PersonaTelefonoResource($personaTelefono);
            DB::commit();
        }catch(Exception $e){
            DB::rollback();
            return $this->errorResponse($e->getMessage(), 400);
        }
        return $this->successResponse($jResponse, 200);
    }

    public function update(RequestsPersonaTelefono $request, $id)
    {
        $validated = $request->validated();
        $jResponse = [];
        DB::beginTransaction();
        try{
            
            $personaTelefono = PersonaTelefono::findOrFail($id);
            $personaTelefono->update($validated);
            $jResponse = new PersonaTelefonoResource($personaTelefono);
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
            $personaTelefono = PersonaTelefono::findOrFail($id);
            $personaTelefono->delete();
            DB::commit();
        }catch(Exception $e){ 
            DB::rollback();                   
            return $this->errorResponse($e->getMessage(), 400);
        }
        return $this->successResponse($jResponse, 200);
    }
}
