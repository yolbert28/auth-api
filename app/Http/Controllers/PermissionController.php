<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;
use Symfony\Component\HttpFoundation\Response;

class PermissionController extends Controller
{
    public function index(){
        return response()->json(
            Permission::all(),
            Response::HTTP_OK
        );
    }

    public function store(Request $request){
        $validation = Validator::make(
            $request->all(),
            ['name' => ['required', 'min:3', 'max:30', 'unique:Permissions']],
            [
                'name.required' => 'El nombre es requirido',
                'name.min' => 'El nombre debe tener un minimo de 3 caracteres',
                'name.max' => 'El nombre debe tener un maximo de 30 caracteres',
                'name.unique' => 'El nombre ya esta siendo utilizado',
            ]
        );

        if($validation->fails()){
            return response()->json(
                ["message" => $validation->errors()->first()],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        $permission = Permission::create($request->only('name'));

        return response()->json(
            [
                "message" => "Permiso creado con exito",
                "permission" => $permission
            ],
            Response::HTTP_CREATED
        );
    }

    public function show($id){

        $permission = Permission::find($id);

        if(!$permission){
            return response()->json(
                ["message" => "El permiso no existe"],
                Response::HTTP_NOT_FOUND
            );
        }

        return response()->json(
            [
                "permission" => $permission
            ],
            Response::HTTP_OK
        );
    }

    public function update(Request $request, $id){
        $validation = Validator::make(
            $request->all(),
            ['name' => ['required', 'min:3', 'max:30', 'unique:Permissions']],
            [
                'name.required' => 'El nombre es requirido',
                'name.min' => 'El nombre debe tener un minimo de 3 caracteres',
                'name.max' => 'El nombre debe tener un maximo de 30 caracteres',
                'name.unique' => 'El nombre ya esta siendo utilizado',
            ]
        );

        if($validation->fails()){
            return response()->json(
                ["message" => $validation->errors()->first()],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        $permission = Permission::find($id);

        if(!$permission){
            return response()->json(
                ["message" => "El permiso no existe"],
                Response::HTTP_NOT_FOUND
            );
        }

        $permission->update($request->only('name'));

        return response()->json(
            [
                "message" => "Permiso Actualizado con exito",
                "permission" => $permission
            ],
            Response::HTTP_OK
        );
    }

    public function destroy($id){

        $permission = Permission::find($id);
        
        if(!$permission){
            return response()->json(
                ["message" => "El permiso no existe"],
                Response::HTTP_NOT_FOUND
            );
        }

        $permission->delete();

        return response()->json(
            [
                "message" => "Permiso eliminado con exito"
            ],
            Response::HTTP_OK
        );
    }
}
