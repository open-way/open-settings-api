<?php

namespace App\Http\Controllers\Setup;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\PersonaDireccion;
use Exception;
use App\Http\Resources\Setup\PersonaDireccion as PersonaDireccionResource;
use App\Http\Requests\Setup\PersonaDireccion as RequestsPersonaDireccion;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PersonaDireccionController extends Controller
{
    public function getByPersonaId(Request $request, $id)
    {
        $jResponse = [];
        try{
            $jResponse = PersonaDireccionResource::collection(PersonaDireccion::where('persona_id', $id)->get());
        }catch(Exception $e){                    
            return $this->errorResponse($e->getMessage(), 400);
        }
        return $this->successResponse($jResponse, 200);
    }

    public function store(Request $request)
    {
        $validated = $request->all();
        $jResponse = [];
        DB::beginTransaction();
        try{
            $personaDireccion = PersonaDireccion::create($validated);
            $jResponse = new PersonaDireccionResource($personaDireccion);
            DB::commit();
        }catch(Exception $e){
            DB::rollback();
            return $this->errorResponse($e->getMessage(), 400);
        }
        return $this->successResponse($jResponse, 200);
    }

    public function update(RequestsPersonaDireccion $request, $id)
    {
        $validated = $request->validated();
        $jResponse = [];
        DB::beginTransaction();
        try{
            
            $personaDireccion = PersonaDireccion::findOrFail($id);
            $personaDireccion->update($validated);
            $jResponse = new PersonaDireccionResource($personaDireccion);
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
            $personaDireccion = PersonaDireccion::findOrFail($id);
            $personaDireccion->delete();
            DB::commit();
        }catch(Exception $e){ 
            DB::rollback();                   
            return $this->errorResponse($e->getMessage(), 400);
        }
        return $this->successResponse($jResponse, 200);
    }
}
