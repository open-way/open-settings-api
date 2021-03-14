<?php

namespace App\Http\Controllers\SetupSystem;

use App\Http\Data\ModuloData;
use App\Models\Modulo;
use App\Models\ModuloAccion;
use App\Models\RolMaccion;
use App\Models\UserRol;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Exception;
use App\Http\Resources\SetupSystem\Modulo as ModuloResource;

// $modulo = Modulo::where('codigo', $modulo_codigo)->first();
//             // $userRols = UserRol::where('user_id', $user_logged->id)->pluck('rol_id')->toArray();
//             $rol_ids = UserRol::where('user_id', $user_logged->id)->pluck('rol_id')->get();
//             $modulo_accion_ids = RolMaccion::where('activo', true)
//                                 ->whereIn('rol_id', $rol_ids)->pluck('modulo_accion_id')->get();
//             $jResponse = ModuloAccion::whereIn('modulo_accion_id', $modulo_accion_ids)->get();

class ModuloController extends Controller
{

    public function index(Request $request)
    {
        $jResponse = [];
        $user_logged = $request->user();
        try{
            $jResponse = ModuloResource::collection(Modulo::orderBy('order')->filter($request->all())->get());
            // $jResponse = Modulo::orderBy('order')->get();
        }catch(Exception $e){                    
            return $this->errorResponse($e->getMessage(), 400);
        }
        return $this->successResponse($jResponse, 200);
    }

    public function getParents(Request $request)
    {
        $jResponse = [];
        $user_logged = $request->user();
        try{
            $jResponse = Modulo::whereNull('parent_id')->orderBy('order')->get();
        }catch(Exception $e){                    
            return $this->errorResponse($e->getMessage(), 400);
        }
        return $this->successResponse($jResponse, 200);
    }

    public function getParentAndChildsToSidenav(Request $request, $modulo_codigo)
    {
        $jResponse = [];
        $user_logged = $request->user();
        try{
            $moduloParent = ModuloData::getByCodigo($modulo_codigo);
            if(!$moduloParent) {
                throw new Exception('No existe un módulo con el cófigo '.$modulo_codigo.'.', 1);
            }
            $modulosParents = ModuloData::getModulosParents($user_logged->id, $moduloParent->modulo_id);
            $jResponse = $this->getRecursiveModulos($modulosParents, $user_logged->id);
        }catch(Exception $e){                    
            return $this->errorResponse($e->getMessage(), 400);
        }
        return $this->successResponse($jResponse, 200);
    }

    public function getModuloAccions(Request $request, $modulo_codigo)
    {
        $jResponse = [];
        $user_logged = $request->user();
        try{
            $rol_ids = UserRol::where('user_id', $user_logged->id)->pluck('rol_id')->toArray();
            $modulo_accion_ids = RolMaccion::where('activo', true)
                            ->whereIn('rol_id', $rol_ids)->pluck('modulo_accion_id')->toArray();

            $modulo = Modulo::where('codigo', $modulo_codigo)->first();
            $jResponse = ModuloAccion::where('modulo_id', $modulo->modulo_id)
                                    ->whereIn('modulo_accion_id', $modulo_accion_ids)->get();
        }catch(Exception $e){                    
            return $this->errorResponse($e->getMessage(), 400);
        }
        return $this->successResponse($jResponse, 200);
    }


    public function getParentAndChilds(Request $request)
    {
        $jResponse = [];
        try{
            $modulosParents = ModuloData::getModulosParentsAll();
            $jResponse = $this->getRecursiveModulosAll($modulosParents);
        }catch(Exception $e){                    
            return $this->errorResponse($e->getMessage(), 400);
        }
        return $this->successResponse($jResponse, 200);
    }

    private function getRecursiveModulos($lista, $user_id) {
        $lista_out=[];
        foreach ($lista as $item) {
            $children_out = new \stdClass();
            $children_out = $item;
            $children = ModuloData::getModulosChildren($item->modulo_id, $user_id);
            if(count($children) > 0) {
                $children_out->children = $this->getRecursiveModulos($children, $user_id);
            }
            array_push($lista_out, $children_out);
        }
        return $lista_out;
    }

    private function getRecursiveModulosAll($lista) {
        $lista_out=[];
        foreach ($lista as $item) {
            $children_out = new \stdClass();
            $children_out = $item;
            $children_out->count_accions = ModuloAccion::where('modulo_id', $item->modulo_id)->count();
            $children = ModuloData::getModulosChildrenAll($item->modulo_id);
            if(count($children) > 0) {
                $children_out->children = $this->getRecursiveModulosAll($children);
            }
            array_push($lista_out, $children_out);
        }
        return $lista_out;
    }

    private function rulesModulo(){
        return [
            'title' => 'required',
            'link' => 'required',
            'order' => 'required',
            'icon' => '',
            'parent_id' => '',
            'group' => 'required',
            'home' => 'required',
            'is_mobile' => 'required',
            'activo' => 'required',
            'codigo' => 'required',
        ];
    }

    public function store(Request $request)
    {
        $jResponse = [];
        $data = Input::all();
        $validador = Validator::make($data,  $this->rulesModulo());
        if($validador->fails()) {
            return $this->errorResponse($validador->errors()->first(), 300);
        }
        try{
            $jResponse = Modulo::create($data);
        }catch(Exception $e){                    
            return $this->errorResponse($e->getMessage(), 400);
        }
        return $this->successResponse($jResponse, 201);
    }

    public function show($modulo_id)
    {
        $jResponse = [];
        try{
            $jResponse = ModuloData::getById($modulo_id);
        }catch(Exception $e){                    
            return $this->errorResponse($e->getMessage(), 400);
        }
        return $this->successResponse($jResponse, 200);
    }

    public function update(Request $request, $modulo_id)
    {
        $jResponse = [];
        $data = Input::all();
        $validador = Validator::make($data,  $this->rulesModulo());
        if($validador->fails()) {
            return $this->errorResponse($validador->errors()->first(), 300);
        }
        try{
            $modulo = Modulo::find($modulo_id);
            $modulo->title = $data["title"];
            $modulo->link = $data["link"];
            $modulo->order = $data["order"];
            $modulo->icon = $data["icon"];
            $modulo->parent_id = $data["parent_id"];
            $modulo->group = $data["group"];
            $modulo->home = $data["home"];
            $modulo->is_mobile = $data["is_mobile"];
            $modulo->activo = $data["activo"];
            $modulo->codigo = $data["codigo"];
            $modulo->save();

        }catch(Exception $e){                    
            return $this->errorResponse($e->getMessage(), 400);
        }
        return $this->successResponse($modulo, 200);
    }

    public function destroy($modulo_id)
    {
        $jResponse = [];
        try{
            $modulo = Modulo::find($modulo_id);
            $modulo->delete();
        }catch(Exception $e){                    
            return $this->errorResponse($e->getMessage(), 400);
        }
        return $this->successResponse($jResponse, 200);
    }
}
