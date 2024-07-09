<?php

namespace App\Http\Controllers;

use App\Http\Resources\RoleResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Symfony\Component\HttpFoundation\Response;

class RoleController extends Controller
{
    
    public function index(){
        return response()->json(Role::all(), Response::HTTP_OK);
    }

    public function store(Request $request){
        $validation = Validator::make(
            $request->all(),
            ['name' => ['required', 'min:3', 'max:30', 'unique:Roles']],
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

        $role = Role::create($request->only('name'));

        return response()->json(
            [
                "message" => "Rol creado con exito",
                "role" => $role
            ],
            Response::HTTP_CREATED
        );
    }

    public function show($id){

        $role = role::find($id);

        if(!$role){
            return response()->json(
                ["message" => "El rol no existe"],
                Response::HTTP_NOT_FOUND
            );
        }

        return response()->json(
            [
                "role" => new RoleResource($role)
            ],
            Response::HTTP_OK
        );
    }

    public function update(Request $request, $id){
        $validation = Validator::make(
            $request->all(),
            ['name' => ['required', 'min:3', 'max:30', 'unique:Roles']],
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

        $role = Role::find($id);

        if(!$role){
            return response()->json(
                ["message" => "El rol no existe"],
                Response::HTTP_NOT_FOUND
            );
        }

        $role->update($request->only('name'));

        return response()->json(
            [
                "message" => "Rol Actualizado con exito",
                "role" => new RoleResource($role)
            ],
            Response::HTTP_OK
        );
    }

    public function destroy($id){

        $role = role::find($id);
        
        if(!$role){
            return response()->json(
                ["message" => "El rol no existe"],
                Response::HTTP_NOT_FOUND
            );
        }

        $role->delete();

        return response()->json(
            [
                "message" => "Rol eliminado con exito"
            ],
            Response::HTTP_OK
        );
    }

    public function assignRole(Request $request){
        $validation = Validator::make(
            $request->all(),
            [
                'user_id' => ['required'],
                'role_id' => ['required']
            ],
            [
                'user_id.required' => 'El id del usuario es requirido',
                'role_id.required' => 'El id del rol es requirido'
            ]
        );

        if($validation->fails()){
            return response()->json(
                ["message" => $validation->errors()->first()],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        $role = Role::findById($request['role_id']);

        if(!$role){
            return response()->json([
                "message" => "El rol no fue encontrado"
            ], Response::HTTP_NOT_FOUND);
        }

        $user = User::find($request['user_id']);

        if(!$user){
            return response()->json([
                "message" => "El usuario no fue encontrado"
            ], Response::HTTP_NOT_FOUND);
        }

        $user->assignRole($role);

        return response()->json([
            "message" => "Rol añadido al usuario exitosamente"
        ], Response::HTTP_OK);
    }

    public function removeRole(Request $request){
        $validation = Validator::make(
            $request->all(),
            [
                'user_id' => ['required'],
                'role_id' => ['required']
            ],
            [
                'user_id.required' => 'El id del usuario es requirido',
                'role_id.required' => 'El id del rol es requirido'
            ]
        );

        if($validation->fails()){
            return response()->json(
                ["message" => $validation->errors()->first()],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        $role = Role::find($request['role_id']);

        if(!$role){
            return response()->json([
                "message" => "El rol no fue encontrado"
            ], Response::HTTP_NOT_FOUND);
        }

        $user = User::find($request['user_id']);

        if(!$user){
            return response()->json([
                "message" => "El usuario no fue encontrado"
            ], Response::HTTP_NOT_FOUND);
        }

        if(!$user->hasRole($role)){
            return response()->json([
                "message" => "El usuario no posee el rol"
            ], Response::HTTP_BAD_REQUEST);
        }

        $user->removeRole($role);

        return response()->json([
            "message" => "Rol removido del usuario exitosamente"
        ], Response::HTTP_OK);
    }

    public function givePermission(Request $request){
        $validation = Validator::make(
            $request->all(),
            [
                'role_id' => ['required'],
                'permission_id' => ['required']
            ],
            [
                'role_id.required' => 'El id del rol es requirido',
                'permission_id.required' => 'El id del permiso es requirido'
            ]
        );

        if($validation->fails()){
            return response()->json(
                ["message" => $validation->errors()->first()],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        $role = Role::find($request['role_id']);

        if(!$role){
            return response()->json([
                "message" => "El rol no fue encontrado"
            ], Response::HTTP_NOT_FOUND);
        }

        $permission = Permission::find($request['permission_id']);
        
        if(!$permission){
            return response()->json([
                "message" => "El permiso no fue encontrado"
            ], Response::HTTP_NOT_FOUND);
        }

        if($role->hasPermissionTo($permission)){
            return response()->json([
                "message" => "El rol ya tiene el permiso"
            ], Response::HTTP_BAD_REQUEST);
        }

        $role->givePermissionTo($permission);

        return response()->json([
            "message" => "Permiso añadido al rol exitosamente"
        ], Response::HTTP_OK);
    }

    public function revokePermission(Request $request){
        $validation = Validator::make(
            $request->all(),
            [
                'role_id' => ['required'],
                'permission_id' => ['required']
            ],
            [
                'role_id.required' => 'El id del rol es requirido',
                'permission_id.required' => 'El id del permiso es requirido'
            ]
        );

        if($validation->fails()){
            return response()->json(
                ["message" => $validation->errors()->first()],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        $role = Role::find($request['role_id']);

        if(!$role){
            return response()->json([
                "message" => "El rol no fue encontrado"
            ], Response::HTTP_NOT_FOUND);
        }

        $permission = Permission::find($request['permission_id']);
        
        if(!$permission){
            return response()->json([
                "message" => "El permiso no fue encontrado"
            ], Response::HTTP_NOT_FOUND);
        }

        if(!$role->hasPermissionTo($permission)){
            return response()->json([
                "message" => "El rol no tiene el permiso"
            ], Response::HTTP_BAD_REQUEST);
        }

        $role->revokePermissionTo($permission);

        return response()->json([
            "message" => "El permiso se removió del rol exitosamente"
        ], Response::HTTP_OK);
    }
}
