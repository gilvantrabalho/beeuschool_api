<?php

namespace App\Http\Controllers\Requests;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ShowController extends Controller
{
    public static function show($id, $model)
    {
        $msg = null;
        $error = false;
        $results = null;

        $results = $model->find($id);
        if (!is_null($results)) {
            $msg = "Registro encontrado com sucesso!";
        } else {
            $error = true;
            $msg = "Registro não encontrado!";
        }

        return response()->json([
            'error' => $error,
            'register' => $results,
            'message' => $msg
        ]);
    }
}
