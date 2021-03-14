<?php

namespace App\Http\Controllers\SetupSystem;

use App\Models\Modulo;
use App\Models\Rol;
use App\Models\RolModulo;
use App\Models\ModuloAccion;
use App\Models\RolMaccion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Exception;
use App\Http\Resources\SetupSystem\Rol as RolResource;
use App\Http\Requests\SetupSystem\Rol as RequestsRol;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\SetupSystem\ModuloAccion as ModuloAccionResource;

class RolController extends Controller
{
    public function index(Request $request)
    {
        $jResponse = [];
        try{
            $per_page = $request->per_page;
            $jResponse = RolResource::collection(Rol::orderBy('rol_id')->filter($request->all())->paginate($per_page));
        }catch(Exception $e){                    
            return $this->errorResponse($e->getMessage(), 400);
        }
        return $jResponse;
        // return $this->successResponse($jResponse, 200);
    }

    public function store(RequestsRol $request)
    {
        $validated = $request->validated();
        $jResponse = [];
        DB::beginTransaction();
        try{
            $rol = Rol::create($validated);
            $jResponse = new RolResource($rol);
            DB::commit();
        }catch(Exception $e){
            DB::rollback();
            return $this->errorResponse($e->getMessage(), 400);
        }
        return $this->successResponse($jResponse, 201);
    }

    public function storeModulos(Request $request, $rolId)
    {
        $jResponse = [];
        $modulos_add = $request->get('modulos_add');
        // $modulos_remove = $request->get('modulos_remove');
        DB::beginTransaction();
        try{
            RolModulo::where('rol_id', '=', $rolId)->delete();
            foreach ($modulos_add as $modulo) {
                $rolModulo = [];
                $rolModulo['modulo_id'] = $modulo['modulo_id'];
                $rolModulo['rol_id'] = $rolId;
                $rolModulo['activo'] = true;
                RolModulo::create($rolModulo);
            }
            // foreach ($modulos_remove as $modulo) {
            //     RolModulo::find($modulo['modulo_id'])->delete();
            // }
            DB::commit();
        }catch(Exception $e){
            DB::rollback();                  
            return $this->errorResponse($e->getMessage(), 400);
        }
        return $this->successResponse($jResponse, 200);
    }

    private function rulesRol(){
        return [
            'nombre' => 'required|max:150|min:1',
            'activo' => 'required',
        ];
    }

    public function show($rol_id)
    {
        $jResponse = [];
        try{
            $jResponse = Rol::find($rol_id);
        }catch(Exception $e){                    
            return $this->errorResponse($e->getMessage(), 400);
        }
        return $this->successResponse($jResponse, 200);
    }

    public function update(RequestsRol $request, $id)
    {
        $validated = $request->validated();
        $jResponse = [];
        DB::beginTransaction();
        try{
            
            $rol = Rol::findOrFail($id);
            $rol->update($validated);
            $jResponse = new RolResource($rol);
            DB::commit();
        }catch(Exception $e){
            DB::rollback();
            return $this->errorResponse($e->getMessage(), 400);
        }
        return $this->successResponse($jResponse, 200);
    }

    public function destroy($rol_id)
    {
        $jResponse = [];
        DB::beginTransaction();
        try {
            Rol::find($rol_id)->delete();
            DB::commit();
        }catch(Exception $e){
            DB::rollback();                   
            return $this->errorResponse($e->getMessage(), 400);
        }
        return $this->successResponse($jResponse, 200);
    }

    public function activate(Request $request,$rol_id) {
        $jResponse = [];
        $activo = $request->get('activo');
        try {
            $rol = Rol::find($rol_id);
            $rol->activo = $activo;
            $rol->save();
            $jResponse = $rol;
        }catch(Exception $e){                    
            return $this->errorResponse($e->getMessage(), 400);
        }
        return $this->successResponse($jResponse, 200); 
    }

    private function getRecursiveModulos($lista, $rol_id) {
        $lista_out=[];
        foreach ($lista as $item) {
            $children_out = new \stdClass();
            $children_out = $item;
            $children_out->asignado = RolModulo::where('rol_id', '=', $rol_id)
                ->where('modulo_id', '=',  $item->modulo_id)
                ->where('activo', '=',  true)->exists();
            $children = Modulo::where('parent_id', '=', $item->modulo_id)->orderBy('order')->get();
            if(count($children) > 0) {
                $children_out->children = $this->getRecursiveModulos($children, $rol_id);
            }
            array_push($lista_out, $children_out);
        }
        return $lista_out;
    }

    public function getModulos(Request $request, $rol_id)
    {
        $jResponse = [];
        try {
            $modulosParents = Modulo::whereNull('parent_id')->orderBy('order')->get();
            $jResponse = $this->getRecursiveModulos($modulosParents,$rol_id );
        }catch(Exception $e){                    
            return $this->errorResponse($e->getMessage(), 400);
        }
        return $this->successResponse($jResponse, 200); 
    }

    public function getAcciones(Request $request, $rol_id, $modulo_id)
    {
        $jResponse = [];
        try {
            $jResponse = ModuloAccionResource::collection(ModuloAccion::
                                        select('*')
                                        ->selectRaw('? as rol_id', [$rol_id])
                                        ->where('modulo_id', $modulo_id)->get());
        }catch(Exception $e){                    
            return $this->errorResponse($e->getMessage(), 400);
        }
        return $this->successResponse($jResponse, 200); 
    }

    public function storeAcciones(Request $request, $rol_id, $modulo_id)
    {
        $jResponse = [];
        $modulo_accions_add = $request->get('modulo_accions_add');
        DB::beginTransaction();
        try{

            $modulo_accion_ids = ModuloAccion::where('modulo_id', $modulo_id)->pluck('modulo_accion_id')->toArray();
            RolMaccion::where('rol_id', '=', $rol_id)
                ->whereIn('modulo_accion_id', $modulo_accion_ids)->delete();

            foreach ($modulo_accions_add as $modulo_accion_id) {
                $rolModuloAccion = [];
                $rolModuloAccion['modulo_accion_id'] = $modulo_accion_id;
                $rolModuloAccion['rol_id'] = $rol_id;
                $rolModuloAccion['activo'] = true;
                RolMaccion::create($rolModuloAccion);
            }
            DB::commit();
        }catch(Exception $e){
            DB::rollback();               
            return $this->errorResponse($e->getMessage(), 400);
        }
        return $this->successResponse($jResponse, 200);
    }


    
}
