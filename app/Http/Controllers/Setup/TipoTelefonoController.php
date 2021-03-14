<?php

namespace App\Http\Controllers\Setup;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;
use App\Models\TipoTelefono;

class TipoTelefonoController extends Controller
{
    public function index(Request $request)
    {
        $jResponse = [];
        try{
            $jResponse = TipoTelefono::all();
        }catch(Exception $e){                    
            return $this->errorResponse($e->getMessage(), 400);
        }
        return $this->successResponse($jResponse, 200);
    }
}
