<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\BaseController as BaseController;
use App\Models\User;
use App\Models\SubscriptionPlan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;

class RegisterController extends BaseController
{
    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);

         if ($validator->fails()) {
            $errors = $validator->errors();

            // Si el email ya ha sido tomado
            if ($errors->has('email')) {
                return $this->sendError('The email has already been taken.', [], 422);
            }

            // Retornar otros errores si existen
            return $this->sendError('Validation Error.', $errors, 422);
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);

        // Asignar el plan "Free" al nuevo usuario
        $freePlan = SubscriptionPlan::where('name', 'Free')->first();
        if ($freePlan) {
            $user->subscriptions()->create([
                'plan_id' => $freePlan->id,
                'starts_at' => Carbon::now(), // Fecha de inicio
                'ends_at' => Carbon::now()->addDays($freePlan->duration), // Fecha de finalización
                'task_limit' => $freePlan->task_limit,
            ]);
        }

        // Crear el token para el nuevo usuario
        $success['token'] = $user->createToken(env('APP_NAME'), ['*'], now()->addDay())->plainTextToken;
        $success['name'] = $user->name;

        return $this->sendResponse($success, 'User registered successfully.');
    }


    /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request): JsonResponse
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            //$user->tokens()->delete();
            $success['token'] =  $user->createToken(env('APP_NAME'),['*'], now()->addDay())->plainTextToken;
            $success['name'] =  $user->name;

            return $this->sendResponse($success, 'User login successfully.');
        } else {
            return $this->sendError('Unauthorised Credentials.', ['error' => 'Unauthorised Credentials']);
        }
    }

    public function userData()
    {
        // Obtener el usuario autenticado
        $user = User::find(Auth::id());

        // Verificar si el usuario existe
        if (!$user) {
            return $this->sendError('User not found.', [], 404);
        }

        // Devolver solo los datos relevantes del usuario
        return $this->sendResponse([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'currentSubscription'=>$user->currentSubscription(),
            // Puedes agregar más campos aquí si es necesario
        ], 'User Data retrieved successfully.', 200);
    }

}
