<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller {

    public function nuevoUsuario(Request $request): \Illuminate\Http\JsonResponse
    {
        $satisfactorio = false;
        $estado = 0;
        $mensaje = "";
        $errores = [];
        $valores = [];

        try {
            $validated = $request->validate([
                'name' => 'required|string|max:100',
                'email' => 'required|string|email|max:100|unique:users,email',
                'password' => 'required|string|min:8|max:20'
            ]);

            // Creación de usuario
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);

            $token = $user->createToken('auth_token')->plainTextToken;

            $valores = [
                'access_token' => $token,
                'token_type' => 'bearer'
            ];

            $satisfactorio = true;
            $estado = 201;
            $mensaje = "Usuario creado correctamente";
            $errores = [
                "code" => 201,
                "msg" => ""
            ];
        }
        catch (ValidationException $e){
            $satisfactorio = false;
            $estado = 422;
            $mensaje = "Error en los datos enviados";
            $errores = [
                "code" => 422,
                "msg" => $e->getMessage()
            ];
        }
        catch (\Exception $e){
            $satisfactorio = false;
            $estado = 500;
            $mensaje = "Error al crear usuario";
            $errores = [
                "code" => 500,
                "msg" => $e->getMessage()
            ];
        }

        $respuesta = [
            "success" => $satisfactorio,
            "status" => $estado,
            "msg" => $mensaje,
            "data" => $valores,
            "errors" => $errores,
            "count" => sizeof($valores)
        ];

        return response()->json($respuesta, 200);
    }

    public function login(Request $request): \Illuminate\Http\JsonResponse
    {
        $satisfactorio = false;
        $estado = 0;
        $mensaje = "";
        $errores = [];
        $valores = [];

        try {
            $validated = $request->validate([
                'email'=>'required|string|email|max:100',
                'password'=>'required|string|min:8|max:20',
            ]);

            $user = \App\Models\User::where('email', $validated['email'])->first();

            if(!$user || !\Illuminate\Support\Facades\Hash::check($validated['password'], $user->password)){
                $satisfactorio = false;
                $estado = 401;
                $mensaje = "No se reconocen las credenciales";
                $errores = [
                    "code" => 401,
                    "msg" => "El correo o la contraseña son incorrectos"
                ];

                $respuesta = [
                    "success" => $satisfactorio,
                    "status" => $estado,
                    "msg" => $mensaje,
                    "data" => $valores,
                    "errors" => $errores,
                    "count" => sizeof($valores)
                ];

                return response()->json($respuesta, 200);
            }

            $token = $user->createToken('auth_token')->plainTextToken;

            $valores = [
                'access_token' => $token,
                'token_type' => 'Bearer'
            ];

            $satisfactorio = true;
            $estado = 200;
            $mensaje = "Inicio de sesión exitoso";
            $errores = [
                "code" => 200,
                "msg" => ""
            ];
        }
        catch (\Illuminate\Validation\ValidationException $e){
            $satisfactorio = false;
            $estado = 422;
            $mensaje = "Error en los datos enviados";
            $errores = [
                "code" => 422,
                "msg" => $e->getMessage()
            ];
        }
        catch (\Exception $e) {
            $satisfactorio = false;
            $estado = 500;
            $mensaje = "Error en el servidor";
            $errores = [
                "code" => 500,
                "msg" => $e->getMessage()
            ];
        }

        $respuesta = [
            "success" => $satisfactorio,
            "status" => $estado,
            "msg" => $mensaje,
            "data" => $valores,
            "errors" => $errores,
            "count" => sizeof($valores)
        ];

        return response()->json($respuesta, 200);
    }

    public function usuario(Request $request): \Illuminate\Http\JsonResponse
    {
        $satisfactorio = false;
        $estado = 0;
        $mensaje = "";
        $errores = [];
        $valores = [];

        try {
            $user = $request->user();

            if($user) {
                $valores = [
                    "id" => $user->id,
                    "name" => $user->name,
                    "email" => $user->email,
                    "email_verified_at" => $user->email_verified_at,
                    "created_at" => $user->created_at,
                    "updated_at" => $user->updated_at
                ];

                $satisfactorio = true;
                $estado = 200;
                $mensaje = "Usuario autenticado encontrado";
                $errores = [
                    "code" => 200,
                    "msg" => ""
                ];
            }
            else {
                $satisfactorio = false;
                $estado = 401;
                $mensaje = "Token inválido o usuario no autenticado";
                $errores = [
                    "code" => 401,
                    "msg" => "El token no corresponde a un usuario válido"
                ];
            }
        }
        catch (\Exception $e) {
            $satisfactorio = false;
            $estado = 500;
            $mensaje = "Error al obtener los datos del usuario";
            $errores = [
                "code" => 500,
                "msg" => $e->getMessage()
            ];
        }

        $respuesta = [
            "success" => $satisfactorio,
            "status" => $estado,
            "msg" => $mensaje,
            "data" => $valores,
            "errors" => $errores,
            "count" => sizeof($valores)
        ];

        return response()->json($respuesta, 200);
    }
}
