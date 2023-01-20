<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Controllers\VoucherController;
use App\Models\Plan;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class PlanController extends Controller
{
    public function index(): JsonResponse {
        return response()->json([
            'plans' => Plan::all()
        ]);
    }

    public function store(Request $request): JsonResponse {
        try {
            $validator = Validator::make($request->only([
                'name','value', 'description'
            ]), [
                'name' => 'required|string',
                'value' => 'required',
                'description' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'error' => true,
                    'message' => $validator->errors()
                ]);
            }

            $plan = new Plan([
                'name' => $request->name,
                'value' => str_replace(',', '.', $request->value),
                'description' => $request->description
            ]);
            $plan->save();

            return response()->json([
                'error' => false,
                'message' => 'Plano cadastrado com sucesso!',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function destroy(Plan $plan): JsonResponse {
        try {
            $plan->delete();
            return response()->json([
                'error' => false,
                'message' => 'Plano deletado com sucesso!'
            ]);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }
}
