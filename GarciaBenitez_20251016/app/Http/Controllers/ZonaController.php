<?php

namespace App\Http\Controllers;

use App\Models\Zona;
use Illuminate\Http\Request;

class ZonaController extends Controller
{
    public function obtenerZonas(): \Illuminate\Http\JsonResponse
    {
        $Zona = new Zona();

        $satisfactorio = false;
        $estado = 0;
        $mensaje = "";
        $errores = [];
        $valores = [];

        $valores = $Zona::all();

        // Se encontraron datos
        if(!empty($valores)) {
            $satisfactorio = true;
            $estado = 200;
            $mensaje = "Valores encontrados";
            $errores = [
                "code" => 200,
                "msg" => ""
            ];
        }
        // No se encontraron datos
        else {
            $satisfactorio = false;
            $estado = 404;
            $mensaje = "No se han encontrado valores";
            $errores = [
                "code" => 404,
                "msg" => "Datos no encontrados"
            ];
        }

        // Creamos la variable de salida
        $respuesta = [
            "success" => $satisfactorio,
            "status" => $estado,
            "msg" => $mensaje,
            "data" => $valores,
            "errors" => $errores,
            "total" => sizeof($valores)
        ];

        // Se retorna el mensaje al usuario
        return response()->Json($respuesta, 200);
    }

    public function obtenerZona($idzona){

        $satisfactorio = false;
        $estado = 0;
        $mensaje = "";
        $errores = [];
        $valores = [];

        if($idzona > 0) {
            $Zona = new Zona();
            $valores = $Zona->where('id_zona', $idzona)->get();

            if(!empty($valores)) {
                $satisfactorio = true;
                $estado = 200;
                $mensaje = "Valores encontrados";
                $errores = [
                    "code" => 200,
                    "msg" => ""
                ];
            }
            // No se encontraron datos
            else {
                $satisfactorio = false;
                $estado = 404;
                $mensaje = "No se han encontrado valores";
                $errores = [
                    "code" => 404,
                    "msg" => "Datos no encontrados"
                ];
            }
        }
        else {
            // No se ha enviado un valor para el parámetro $idzona
            $satisfactorio = false;
            $estado = 400;
            $mensaje = "No se ha enviado el parámetro obligatorio";
            $errores = [
                "code" => 400,
                "msg" => "El identificador de la zona está vacío"
            ];
        }

        $respuesta = [
            "success" => $satisfactorio,
            "status" => $estado,
            "msg" => $mensaje,
            "data" => $valores,
            "errors" => $errores,
            "total" => sizeof($valores)
        ];

        return response()->Json($respuesta, 200);
    }
}
