<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Link;
use App\Models\Plan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LinkController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'links' => Link::all()
        ]);
    }

    public function store(Request $request): JsonResponse {
        try {
            $validator = Validator::make($request->only([
                'name','url', 'permission', 'description'
            ]), [
                'name' => 'required|string',
                'url' => 'required|string',
                'permission' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'error' => true,
                    'message' => $validator->errors()
                ]);
            }

            $plan = new Link([
                'name' => $request->name,
                'url' => $request->url,
                'permission' => $request->permission,
                'description' => $request->description
            ]);
            $plan->save();

            return response()->json([
                'error' => false,
                'message' => 'Link cadastrado com sucesso!',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function destroy(Link $link): JsonResponse {
        try {
            $link->delete();
            return response()->json([
                'error' => false,
                'message' => 'Link deletado com sucesso!'
            ]);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }
}
