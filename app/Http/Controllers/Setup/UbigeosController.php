<?php

namespace App\Http\Controllers\Setup;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\PersonaVirtual;
use App\Models\Ubigeo;
use Exception;

class UbigeosController extends Controller
{

    public function getUbigeosParents(Request $request)
    {
        $jResponse = [];
        try{
            $jResponse = Ubigeo::where('parent_id', null)->orderBy('nombre')->get();
        }catch(Exception $e){                    
            return $this->errorResponse($e->getMessage(), 400);
        }
        return $this->successResponse($jResponse, 200);
    }

    public function getUbigeosByParentId(Request $request, $parent_id)
    {
        $jResponse = [];
        try{
            $jResponse = Ubigeo::where('parent_id', $parent_id)->orderBy('nombre')->get();
        }catch(Exception $e){                    
            return $this->errorResponse($e->getMessage(), 400);
        }
        return $this->successResponse($jResponse, 200);
    }

}
