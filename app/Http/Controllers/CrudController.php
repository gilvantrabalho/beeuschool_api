<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Requests\DeleteController;
use App\Http\Controllers\Requests\ReadController;
use App\Http\Controllers\Requests\ShowController;
use App\Http\Controllers\Requests\UpdateController;
use App\Models\Crud;
use Illuminate\Http\Request;
use App\Http\Controllers\Requests\RegisterController;

class CrudController extends Controller
{
    public function index()
    {
        return ReadController::read(new Crud());
    }

    public function showById($id)
    {
        return ShowController::show($id, new Crud());
    }

    public function store(Request $request)
    {
        return RegisterController::store(
            new Crud(),
            $request,
            ['name','telephone', 'email'],
            [
                'required|string',
                'required|string',
                'required|string'
            ],
            [
                'name.required' => 'Nome é um campo obrigatório'
            ]
        );
    }

    public function update(Crud $crud, Request $request)
    {
        return UpdateController::update(
            $crud->id,
            new Crud(),
            $request,
            ['name','telephone', 'email'],
            [
                'string',
                'string',
                'string'
            ]
        );
    }

    public function destroy(Crud $crud)
    {
        return DeleteController::destroy($crud);
    }
}
