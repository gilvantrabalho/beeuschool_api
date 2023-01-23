<?php

namespace App\Http\Controllers\Requests;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReadController extends Controller
{
    public static function read($model): JsonResponse
    {
        return response()->json([
            'error' => false,
            'registers' => $model->all()
        ]);
    }
}
