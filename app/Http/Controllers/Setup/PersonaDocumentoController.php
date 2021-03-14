<?php

namespace App\Http\Controllers\Setup;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\PersonaDocumento;
use Exception;
use App\Http\Resources\Setup\PersonaDocumento as PersonaDocumentoResource;
use App\Http\Requests\Setup\PersonaDocumento as RequestsPersonaDocumento;
use App\Http\Requests\Setup\PersonaDocumentoNew as RequestsPersonaDocumentoNew;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PersonaDocumentoController extends Controller
{

    public function index(Request $request)
    {
        $jResponse = [];
        $user_logged = $request->user();
        try{
            $jResponse = PersonaDocumentoResource::collection(PersonaDocumento::filter($request->all()));
        }catch(Exception $e){                    
            return $this->errorResponse($e->getMessage(), 400);
        }
        return $this->successResponse($jResponse, 200);
    }
        
    public function getByPersonaId(Request $request, $id)
    {
        $jResponse = [];
        try{
            $jResponse = PersonaDocumentoResource::collection(PersonaDocumento::where('persona_id', $id)->get());
        }catch(Exception $e){                    
            return $this->errorResponse($e->getMessage(), 400);
        }
        return $this->successResponse($jResponse, 200);
    }

    public function store(RequestsPersonaDocumentoNew $request)
    {
        $validated = $request->validated();
        $jResponse = [];
        DB::beginTransaction();
        try{

            $existe = PersonaDocumento::where('persona_id',$validated['persona_id'])->where('tipo_documento_id',$validated['tipo_documento_id'])->exists();
            if($existe) {
                throw new Exception('Alto! La persona ya tiene un documento de tipo: '.$validated['persona_id'], 1);
            }

            $personaDocumento = PersonaDocumento::create($validated);
            if ($request->file('img_file')) {
                $imagePath = $request->file('img_file');
                $imageName = $personaDocumento->num_doc.'.'.$imagePath->extension();
                $exists = Storage::exists('persona-documento/'.$imageName);
                if($exists) {
                    Storage::delete(['persona-documento/'.$personaDocumento->num_doc.'.jpg', 'persona-documento/'.$personaDocumento->num_doc.'.png', 'persona-documento/'.$personaDocumento->num_doc.'.jpeg']);
                }

                $path = $request->file('img_file')->storeAs('persona-documento', $imageName);
                // storeAs('nombre_subcarpeta', 'archivo', 'nombre_disco')
                $validated['img_url'] = env('APP_URL').'/storage/'.$path;
            } else {
                // $validated['img_url'] = $img_url;
                $nameImagenSplit = explode('/',$validated['img_url']);
                $nameImagen = end($nameImagenSplit);
                if($nameImagen === 'empty.jpeg')  {
                    Storage::delete(['persona-documento/'.$personaDocumento->num_doc.'.jpg', 'persona-documento/'.$personaDocumento->num_doc.'.png', 'persona-documento/'.$personaDocumento->num_doc.'.jpeg']);
                } else if (isset($nameImagen)){
                    $validated['img_url'] = env('APP_URL').'/storage/utils/empty.jpeg';;
                }
            }
            $personaDocumento->update(['img_url' => $validated['img_url']]);
            $jResponse = new PersonaDocumentoResource($personaDocumento);
            DB::commit();
        }catch(Exception $e){
            DB::rollback();
            return $this->errorResponse($e->getMessage(), 400);
        }
        return $this->successResponse($jResponse, 200);
    }

    public function update(RequestsPersonaDocumento $request, $num_doc)
    {
        $validated = $request->validated();
        $jResponse = [];
        DB::beginTransaction();
        try{
            $existe = PersonaDocumento::where('persona_id',$validated['persona_id'])
                ->where('tipo_documento_id',$validated['persona_id'])
                ->where('num_doc','<>', $num_doc)
                ->exists();
            if($existe) {
                throw new Exception('Alto! La persona ya tiene un documento de tipo: '.$validated['persona_id'], 1);
            }

            if ($request->file('img_file')) {
                $imagePath = $request->file('img_file');
                $imageName = $num_doc.'.'.$imagePath->extension();
                $exists = Storage::exists('imagenes/'.$imageName);
                if($exists) {
                    Storage::delete(['persona-documento/'.$num_doc.'.jpg', 'persona-documento/'.$num_doc.'.png', 'persona-documento/'.$num_doc.'.jpeg']);
                }
                $path = $request->file('img_file')->storeAs('persona-documento', $imageName);
                // storeAs('nombre_subcarpeta', 'archivo', 'nombre_disco')
                $validated['img_url'] = env('APP_URL').'/storage/'.$path;
            } else {
                $nameImagenSplit = explode('/',$validated['img_url']);
                $nameImagen = end($nameImagenSplit);
                if($nameImagen === 'empty.jpeg')  {
                    Storage::delete(['persona-documento/'.$num_doc.'.jpg', 'persona-documento/'.$num_doc.'.png',  'persona-documento/'.$num_doc.'.jpeg']);
                }
                //  else if (isset($nameImagen)){
                //     $validated['img_url'] = env('APP_URL').'/storage/utils/empty.jpeg';;
                // }
            }
            $personaDocumento = PersonaDocumento::findOrFail($num_doc);
            $personaDocumento->update($validated);
            $jResponse = new PersonaDocumentoResource($personaDocumento);
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
            $personaDocumento = PersonaDocumento::findOrFail($id);
            Storage::delete(['persona-documento/'.$personaDocumento->num_doc.'.jpg', 'persona-documento/'.$personaDocumento->num_doc.'.png', 'persona-documento/'.$personaDocumento->num_doc.'.jpeg']);
            $personaDocumento->delete();
            DB::commit();
        }catch(Exception $e){ 
            DB::rollback();                   
            return $this->errorResponse($e->getMessage(), 400);
        }
        return $this->successResponse($jResponse, 200);
    }
}
