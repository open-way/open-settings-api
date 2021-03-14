<?php

namespace App\Http\Controllers\Setup;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;
use App\Models\TipoVirtual;

class TipoVirtualController extends Controller
{
    public function index(Request $request)
    {
        $jResponse = [];
        try{
            $jResponse = TipoVirtual::all();
        }catch(Exception $e){                    
            return $this->errorResponse($e->getMessage(), 400);
        }
        return $this->successResponse($jResponse, 200);
    }
}
