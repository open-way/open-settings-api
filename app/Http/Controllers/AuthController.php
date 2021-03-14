<?php

namespace App\Http\Controllers;

use App\Http\Data\PersonaData;
use App\Http\Data\PeriodoVisitaData;
use App\Http\Data\UserLogged;
use App\Models\Persona;
use App\Models\PersonaDocumento;
use App\Models\PersonaVirtual;
use App\Models\UserRol;
// use App\User;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
// use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Egulias\EmailValidator\EmailValidator;
use Egulias\EmailValidator\Validation\RFCValidation;
use Exception;
use App\Http\Resources\Setup\Persona as PersonaResource;
use App\Http\Resources\Setup\User as UserResource;
use App\Http\Requests\Auth\ForgotPassword as RequestsForgotPassword;
use App\Http\Requests\Auth\ResetPassword as RequestsResetPassword;
use App\Http\Requests\Auth\Login as RequestsLogin;
use App\Http\Requests\Auth\Signup as RequestsSignup;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Events\Registered;
// use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\Events\Verified; 

// https://medium.com/@cvallejo/sistema-de-autenticaci%C3%B3n-api-rest-con-laravel-5-6-parte-4-7365cc22d78b
class AuthController extends Controller
{

    public function signup (RequestsSignup $request) {
        $validated = $request->validated();
        $message = "";
        DB::beginTransaction();
        try {
        // try{
            $persona = new Persona([
                'nombres' => $request->name,
                'ap_paterno' => $request->ap_paterno,
            ]);
            $persona->save();

            $persona_documento = new PersonaDocumento([
                'num_doc' => $request->num_doc,
                'img_url' => '',
                'persona_id' => $persona->persona_id,
                'tipo_documento_id' => 1,
            ]);
            $persona_documento->save();
            
            $persona_virtual = new PersonaVirtual([
                'direccion' => $request->email,
                'activo' => true,
                'persona_id' => $persona->persona_id,
                'tipo_virtual_id' => 1,
            ]);
            $persona_virtual->save();
    
            $cantidad_usuarios = User::all()->count();
            
            // $user = User::create([
            //     'id' => $persona->persona_id,
            //     'name' => $request->name,
            //     'email' => $request->email,
            //     'password' => bcrypt($request->password),
            //     'activation_token'  => str_random(60),
            // ]);
            $user = new User([
                'id'     => $persona->persona_id,
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => bcrypt($request->password),
                'activation_token'  => str_random(60),
            ]);
            $user->save();
    
            if ($cantidad_usuarios === 0) {
                $user = User::findOrFail($persona->persona_id);
                // $user->update(['is_superuser' => true, 'active' => true]);
                $user->update(['is_superuser' => true]);
    
                $userRol = new UserRol([
                    'user_id' => $persona->persona_id,
                    'rol_id' => 1,
                    'activo' => true
                ]);
                $userRol->save();
            }
            $tokenResult = $user->createToken('Personal Access Token');
            $token = $tokenResult->token;
            $token->save();
            // if ($request->remember_me) {
            // $token->expires_at = Carbon::now()->addWeeks(1);
            // }
            event(new Registered($user));
            $message = __('passwords.sent_validated');
            // $jResponse = $user;
            DB::commit();
        }catch(Exception $e){
            DB::rollback();                 
            return $this->errorResponse($e->getMessage(), 400);
        }

        return $this->successResponse(
            [
                'access_token' => $tokenResult->accessToken,
                'token_type'   => 'Bearer',
                'message'   => $message,
                'expires_at'   => Carbon::parse(
                    $tokenResult->token->expires_at)
                    ->toDateTimeString(),
            ], 201);
        
        // return $this->successResponse($jResponse, 201);
    }

    public function showResetForm(Request $request, $token){
        return view('auth.reset-password', ['token' => $token]);
    }

    // public function emailVerification(EmailVerificationRequest $request){
    public function emailVerification(Request $request){
        $jResponse = [];
        DB::beginTransaction();
        try {
            // dd($request->route('id'));
            $user = User::find($request->route('id'));
            // dd($user);
            if (!hash_equals((string) $request->route('hash'), sha1($user->getEmailForVerification()))) {
                throw new AuthorizationException;
            }

            if ($user->markEmailAsVerified()) {
                event(new Verified($user));
            }
            $jResponse = 'Your email address has verified.';
            DB::commit();
            // return redirect($this->redirectPath())->with('verified', true);
        } catch (Exception $ex) {
            DB::rollback();                 
            return $this->errorResponse($ex->getMessage(), 400);
        }
        return $this->successResponse($jResponse, 200);
        // $request->fulfill();
    }

    public function verificationNotification(Request $request)
    {
        $jResponse = [];
        DB::beginTransaction();
        try {

            if ($request->user()->hasVerifiedEmail()) {
                return $this->errorResponse(__('passwords.email_verified'), 300);
            }
            $request->user()->sendEmailVerificationNotification();
            $jResponse = "Verification link sent!";
        } catch (Exception $ex) {
            DB::rollback();                 
            return $this->errorResponse($ex->getMessage(), 400);
        }
        return $this->successResponse($jResponse, 200);
    }

    public function resetPassword(RequestsResetPassword $request){
        $validated = $request->validated();
        $jResponse = [];
        DB::beginTransaction();
        try {
            $status = Password::reset(
                // $request->only('email', 'password', 'password_confirmation', 'token'),
                $validated,
                function ($user, $password) use ($request) {
                    $user->forceFill([
                        'password' => Hash::make($password)
                    ])->save();
        
                    $user->setRememberToken(Str::random(60));
        
                    event(new PasswordReset($user));
                }
            );
            if(!Password::PASSWORD_RESET) {
                throw new Exception(__($status), 1);
            }
            $jResponse = __($status);
            DB::commit();
        } catch (Exception $ex) {
            DB::rollback();                 
            return $this->errorResponse($ex->getMessage(), 400);
        }
        return $this->successResponse($jResponse, 200);
    }

    public function forgotPassword(RequestsForgotPassword $request){
        $validated = $request->validated();
        $jResponse = [];
        DB::beginTransaction();
        try {
            $status = Password::sendResetLink($validated);
            $jResponse = __($status);
            switch ($status) {
                case Password::RESET_LINK_SENT:
                    $jResponse = __($status);
                    goto end;
                case Password::INVALID_USER:
                    throw new Exception(__($status), 1);              
                }
            end:
            DB::commit();
        } catch (Exception $ex) {
            DB::rollback();                 
            return $this->errorResponse($ex->getMessage(), 400);
        }
        return $this->successResponse($jResponse, 200);
    }

    public function passwordRequest(Request $request, $id) {
        
        $jResponse = [];
        $validador = Validator::make($request->all(), [
            'password' => 'required|string|confirmed',
        ]);
        if($validador->fails()) {
            return $this->errorResponse($validador->errors()->first(), 300);
        }
        DB::beginTransaction();
        try{
            $user = User::find($id);
            $user->password = bcrypt($request->password);
            $user->save();
            $jResponse = $user;
            DB::commit();
        }catch(Exception $e){
            DB::rollback();                 
            return $this->errorResponse($e->getMessage(), 400);
        }
        return $this->successResponse($jResponse, 200);
    }

    public function login(RequestsLogin $request) {
        $credentials = $request->validated();
        $jResponse = [];
        DB::beginTransaction();
        try {
            unset($credentials['remember_me']); // Borramos la variable
            // $credentials['active'] = 1;
            // $credentials['active'] = 0;

            // Auth::login($user);
            if (!Auth::attempt($credentials)) {
                return $this->errorResponse('Creadenciales incorrectas', 401);
            }
            $user = $request->user();
            $tokenResult = $user->createToken('Personal Access Token');
            $token = $tokenResult->token;

            if ($request->remember_me) {
                $token->expires_at = Carbon::now()->addWeeks(1);
            }
            $token->save();
            DB::commit();
        }catch(Exception $e){
            DB::rollback();                 
            return $this->errorResponse($e->getMessage(), 400);
        }
        return $this->successResponse([
                            'access_token' => $tokenResult->accessToken,
                            'token_type'   => 'Bearer',
                            'expires_at'   => Carbon::parse(
                                $tokenResult->token->expires_at)
                                ->toDateTimeString(),
                        ], 200);
    }

    public function logout(Request $request) {
        $jResponse = [];
        try{
            $request->user()->token()->revoke();
        }catch(Exception $e){                    
            return $this->errorResponse($e->getMessage(), 400);
        }
        return $this->successResponse($jResponse, 200);
    }

    public function user(Request $request) {
        $jResponse = [];
        $user =  $request->user();
        try{
            $jResponse = new UserResource($user);
        }catch(Exception $e){                    
            return $this->errorResponse($e->getMessage(), 400);
        }
        return $this->successResponse($jResponse, 200);
    }

    public function persona(Request $request) {
        $jResponse = [];
        $user =  $request->user();
        try{
            $jResponse = new PersonaResource(Persona::findOrFail($user->id));
        }catch(Exception $e){                    
            return $this->errorResponse($e->getMessage(), 400);
        }
        return $this->successResponse($jResponse, 200);
    }

    public function signupActivate($token)
    {
        $user = User::where('activation_token', $token)->first();
        if (!$user) {
            return response()->json(['message' => 'El token de activación es inválido'], 404);
        }
        try{
            $user->active = true;
            $user->activation_token = '';
            $user->save();
        }catch(Exception $e){                    
            return response()->json(['message' => $e->getMessage()], 404);
        }
        return $user;
    }
}
