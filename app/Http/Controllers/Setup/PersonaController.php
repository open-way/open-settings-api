<?php

namespace App\Http\Controllers\Setup;

use App\Http\Data\PersonaData;
use App\Models\EstudioBiblico;
use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Http\Requests\Setup\Persona as RequestsPersona;
use App\Http\Resources\Setup\Persona as PersonaResource;
use App\Models\Persona;
use App\Models\PersonaDocumento;
use App\Models\PersonaTelefono;
use App\Models\PersonaVirtual;
use App\Models\PersonaDireccion;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;

class PersonaController extends Controller
{
    public function index(Request $request)
    {
        $jResponse = [];
        try{
            $per_page = $request->per_page;
            $jResponse = PersonaResource::collection(Persona::orderBy('ap_paterno')->filter($request->all())->paginate($per_page));
            // $text_search = $request->text_search;
            // $jResponse = PersonaData::getPersonasBYTextSearch($text_search);
        }catch(Exception $e){                    
            return $this->errorResponse($e->getMessage(), 400);
        }
        // return $this->successResponse($jResponse, 200);
        return $jResponse;
    }

    // private function rulesPersonaUpdate(){
    //     return [
    //         'nombres' => 'required|max:200|min:1',
    //         'ap_paterno' => 'required',
    //         'ap_materno' => '',
    //         'num_doc' => '',
    //         'estado_civil' => '',
    //         'fecha_nac' => '',
    //         'celular' => '',
    //         'sexo' => '',
    //     ];
    // }
    // private function rulesPersona($isNullNumDoc){
    //     if($isNullNumDoc) {
    //         return [
    //             'nombres' => 'required|max:50|min:1',
    //             'ap_paterno' => 'required|max:60',
    //             'num_doc' => '',
    //             'estado_civil' => 'required',
    //             'fecha_nac' => '',
    //             'celular' => '',
    //             'sexo' => 'required',
    //         ];
    //     }
    //     return [
    //         'nombres' => 'required|max:200|min:1',
    //         'ap_paterno' => 'required',
    //         'ap_materno' => '',
    //         'num_doc' => 'unique:personas',
    //         'estado_civil' => 'required',
    //         'fecha_nac' => '',
    //         'celular' => '',
    //         'sexo' => 'required',
    //     ];
    // }


    public function store(RequestsPersona $request)
    {
        $jResponse = [];
        $validated = $request->validated();
        DB::beginTransaction();
        try{
            $persona = Persona::create($validated);
            $jResponse = new PersonaResource($persona);
            DB::commit();
        }catch(Exception $e){           
            DB::rollback();                   
            return $this->errorResponse($e->getMessage(), 400);
        }
        return $this->successResponse($jResponse, 201);
    }

    public function show($id)
    {
        $jResponse = [];
        try{
            $jResponse = new PersonaResource(Persona::findOrFail($id));
        }catch(Exception $e){                    
            return $this->errorResponse($e->getMessage(), 400);
        }
        return $this->successResponse($jResponse, 200);
    }

    public function update(Request $request, $id)
    {
        $jResponse = [];
        $validated = $request->all();
        DB::beginTransaction();
        try{
            // if ($request->file('img_file')) {
            //     $imagePath = $request->file('img_file');
            //     $imageName = $id.'.'.$imagePath->extension();
            //     $exists = Storage::exists('imagenes/'.$imageName);
            //     if($exists) {
            //         Storage::delete(['persona/'.$id.'.jpg', 'persona/'.$id.'.png', 'persona/'.$id.'.jpeg']);
            //     }
            //     $path = $request->file('img_file')->storeAs('persona', $imageName);
            //     // storeAs('nombre_subcarpeta', 'archivo', 'nombre_disco')
            //     $validated['img_url'] = env('APP_URL').'/storage/'.$path;
            // } else {
            //     $nameImagenSplit = explode('/',$validated['img_url']);
            //     $nameImagen = end($nameImagenSplit);
            //     if($nameImagen === 'empty.jpeg')  {
            //         Storage::delete(['persona/'.$id.'.jpg', 'persona/'.$id.'.png',  'persona/'.$id.'.jpeg']);
            //     }
            // }

            $persona = Persona::find($id);
            $persona->update($validated);
            $jResponse = new PersonaResource($persona);
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

            $existe = PersonaDocumento::where('persona_id', $id)->exists();
            if($existe){
                throw new Exception('Alto! La persona tiene documentos registrados, primero elimine éstos.', 1);
            }
            $existe = PersonaTelefono::where('persona_id', $id)->exists();
            if($existe){
                throw new Exception('Alto! La persona tiene contactos registrados, primero elimine éstos.', 1);
            }
            $existe = PersonaVirtual::where('persona_id', $id)->exists();
            if($existe){
                throw new Exception('Alto! La persona tiene direcciones virtuales registradas, primero elimine éstas.', 1);
            }
            $existe = PersonaDireccion::where('persona_id', $id)->exists();
            if($existe){
                throw new Exception('Alto! La persona tiene direcciones registradas, primero elimine éstas.', 1);
            }
            $existe = User::where('id', $id)->exists();
            if($existe){
                throw new Exception('Alto! La persona tiene un usuario registrado, primero elimine éste.', 1);
            }
            $persona = Persona::find($id);
            $persona->delete();
            DB::commit();
        }catch(Exception $e){ 
            DB::rollback();                   
            return $this->errorResponse($e->getMessage(), 400);
        }
        return $this->successResponse($jResponse, 200);
    }
}
