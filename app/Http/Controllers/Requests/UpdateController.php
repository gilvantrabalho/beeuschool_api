<?php

namespace App\Http\Controllers\Requests;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UpdateController extends Controller
{
    public static function update($id, $model, Request $request, $fields, $rules = [], $messages = [])
    {
        try {
            $validateFields = array_combine($fields, $rules);
            $validator = Validator::make($request->only($fields), $validateFields, $messages);

            if ($validator->fails()) {
                return response()->json([
                    'error' => true,
                    'errors' => $validator->errors()
                ]);
            }

            $resp = $model->whereId($id)
                ->update($request->only($fields));

            return response()->json([
                'error' => false,
                'message' => 'Registro editado com sucesso!',
                'register' => $resp
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => $e->getMessage()
            ]);
        }
    }
}
