<?php

namespace App\Http\Controllers\Requests;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class DeleteController extends Controller
{
    public static function destroy($model): JsonResponse {
        try {
            $model->delete();
            return response()->json([
                'error' => false,
                'message' => 'Registro deletado com sucesso!'
            ]);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }
}
