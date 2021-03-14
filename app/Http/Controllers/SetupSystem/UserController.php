<?php

namespace App\Http\Controllers\SetupSystem;

use App\Http\Data\UserData;
use App\Models\Persona;
use App\Models\UserRol;
use App\Models\User;
use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Exception;
use App\Http\Controllers\Controller;
use App\Http\Resources\Setup\User as UserResource;
use App\Http\Requests\Setup\User as RequestsUser;
use App\Http\Requests\Setup\UserNew as RequestsUserNew;
use App\Http\Requests\SetupSystem\UpdateUser as RequestsUpdateUser;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Auth\Events\Registered;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $jResponse = [];
        try{
            $per_page = $request->per_page;
            $jResponse = UserResource::collection(User::filter($request->all())->paginate($per_page));
        }catch(Exception $e){                    
            return $this->errorResponse($e->getMessage(), 400);
        }
        // return $this->successResponse($jResponse, 201);
        return $jResponse;
    }

    // public function store(Request $request)
    // {
    //     $jResponse = [];
    //     $validador = Validator::make(Input::all(), [
    //         'num_doc' => 'required|numeric|unique:personas',
    //         'name' => 'required|string',
    //         'ap_paterno' => 'required',
    //         'email' => 'required|string|email|unique:users',
    //         'password' => 'required|string|confirmed',
    //     ]);
    //     if($validador->fails()) {
    //         return $this->errorResponse($validador->errors()->first(), 300);
    //     }

    //     try{
    //         $persona = new Persona([
    //             'nombres' => $request->name,
    //             'ap_paterno' => $request->ap_paterno,
    //             'num_doc' => $request->num_doc,
    //             'email' => $request->email,
    //         ]);
    //         $persona->save();

    //         $user = new User([
    //             'name'     => $request->name,
    //             'id'     => $persona->id,
    //             'email'    => $request->email,
    //             'password' => bcrypt($request->password),
    //             'activation_token'  => str_random(60),
    //         ]);

    //         $user->save();
    //         $jResponse = $user;
    //     }catch(Exception $e){                    
    //         return $this->errorResponse($e->getMessage(), 400);
    //     }
    //     return $this->successResponse($jResponse, 201);
    // }

    public function store(RequestsUserNew $request)
    {
        $validated = $request->validated();
        $jResponse = [];
        DB::beginTransaction();

        try{
            $persona = Persona::findOrFail($validated['id']);
            $validated['name'] = $persona->nombres.' '.$persona->ap_paterno.' '.$persona->ap_materno;
            $validated['password'] = bcrypt($request->password);
            // $validated['activation_token'] = str_random(60);
            $user = User::create($validated);

            event(new Registered($user));
            $message = __('passwords.sent_validated');
            $jResponse = new UserResource($user);
            DB::commit();
        }catch(Exception $e){
            DB::rollback();
            return $this->errorResponse($e->getMessage(), 400);
        }
        return $this->successResponse($jResponse, 201);
    }

    public function show(Request $request, $id) {
        $jResponse = [];
        try{
            $jResponse = new UserResource(User::findOrFail($id));
        }catch(Exception $e){                    
            return $this->errorResponse($e->getMessage(), 400);
        }
        return $this->successResponse($jResponse, 200);
    }

    public function updateEmail(RequestsUpdateUser $request, $id)
    {
        $validated = $request->validated();
        $jResponse = [];
        DB::beginTransaction();
        try{
            $user = User::findOrFail($id);
            $user->email = $validated['email'];
            $user->email_verified_at = null;
            $user->save();
            $user->sendEmailVerificationNotification();
            $jResponse = new UserResource($user);
            DB::commit();
        }catch(Exception $e){
            DB::rollback();
            return $this->errorResponse($e->getMessage(), 400);
        }
        return $this->successResponse($jResponse, 200);
    }

    public function update(RequestsUser $request, $id)
    {
        $validated = $request->validated();
        $jResponse = [];
        DB::beginTransaction();
        try{
            if ($request->file('img_file')) {
                $imagePath = $request->file('img_file');
                $imageName = $id.'.'.$imagePath->extension();
                $exists = Storage::exists('imagenes/'.$imageName);
                if($exists) {
                    Storage::delete(['user/'.$id.'.jpg', 'user/'.$id.'.png', 'user/'.$id.'.jpeg']);
                }
                $path = $request->file('img_file')->storeAs('user', $imageName);
                // storeAs('nombre_subcarpeta', 'archivo', 'nombre_disco')
                $validated['img_url'] = env('APP_URL').'/storage/'.$path;
            } else {
                $nameImagenSplit = explode('/',$validated['img_url']);
                $nameImagen = end($nameImagenSplit);
                if($nameImagen === 'empty.jpeg')  {
                    Storage::delete(['user/'.$id.'.jpg', 'user/'.$id.'.png',  'user/'.$id.'.jpeg']);
                }
            }
            // $persona = Persona::findOrFail($id);
            // $persona->img_url = $validated['img_url'];
            // $persona->save();

            $user = User::findOrFail($id);
            $user->is_superuser = $validated['is_superuser'];
            $user->active = $validated['active'];
            $user->name = $validated['name'];
            // $user->email = $validated['email'];
            $user->img_url = $validated['img_url'];
            // $user->password = bcrypt($request->password);
            // $user->activation_token = bcrypt($request->password);
            $user->save();
            $jResponse = new UserResource($user);
            DB::commit();
        }catch(Exception $e){
            DB::rollback();
            return $this->errorResponse($e->getMessage(), 400);
        }
        return $this->successResponse($jResponse, 200);
    }

    public function verificationNotification(Request $request, $id)
    {
        $jResponse = [];
        DB::beginTransaction();
        try {
            $user = User::findOrFail($id);
            if ($user->hasVerifiedEmail()) {
                return $this->errorResponse(__('passwords.email_verified'), 300);
            }
            $user->sendEmailVerificationNotification();
            $jResponse = "Verification link sent!";
        } catch (Exception $ex) {
            DB::rollback();                 
            return $this->errorResponse($ex->getMessage(), 400);
        }
        return $this->successResponse($jResponse, 200);
    }

    public function destroy($id)
    {
        $jResponse = [];
        DB::beginTransaction();
        try{
            $user = User::find($id);
            if($user->is_superuser===1) {
                return $this->errorResponse('El usuario no puede ser eliminado porque es un super usuario.', 400);
            } else {
                Storage::delete(['user/'.$id.'.jpg', 'user/'.$id.'.png',  'user/'.$id.'.jpeg']);
                $user->delete();
            }
            DB::commit();
        }catch(Exception $e){
            DB::rollback();                 
            return $this->errorResponse($e->getMessage(), 400);
        }
        return $this->successResponse($jResponse, 200);
    }

    public function storeRols(Request $request, $userId)
    {
        $jResponse = [];
        $rols_add = $request->get('rols_add');
        DB::beginTransaction();
        try{
            // Eliminar todos
            UserRol::where('user_id', '=', $userId)->delete();

            // Volver a agregar
            foreach ($rols_add as $rols) {
                $userRol = new UserRol([
                    'rol_id' => $rols['rol_id'],
                    'user_id' =>$userId,
                    'activo' =>true,
                ]);
                $userRol->save();
            }
            DB::commit();
        }catch(Exception $e){
            DB::rollback();                 
            return $this->errorResponse($e->getMessage(), 400);
        }
        return $this->successResponse($jResponse, 200);
    }

    public function getRols(Request $request, $userId)
    {
        $jResponse = [];
        try{
            $jResponse = UserData::getRols($userId);
        }catch(Exception $e){
            return $this->errorResponse($e->getMessage(), 400);
        }
        return $this->successResponse($jResponse, 201);
    }

}
