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
    /**
     *  @OA\Get(
     *      path="/api/role/",
     *      tags={"Roles"},
     *      summary="Get a list of role",
     *      description="Get a list of role",
     *      security={{"bearer": {} }},
     *      @OA\Response(
     *          response=200,
     *          description="Ok",
     *          @OA\JsonContent(
     *              type="array",
     *              @OA\Items(
     *                  type="object",
     *                  @OA\Property(
     *                      property="id",
     *                      type="integer",
     *                      description="Id of the role"
     *                  ),
     *                  @OA\Property(
     *                      property="name",
     *                      type="string",
     *                      description="Name of the role"
     *                  ),
     *                  @OA\Property(
     *                      property="guard_name",
     *                      type="string",
     *                      description="name of the guard"
     *                  ),
     *                 @OA\Property(
     *                     property="created_at",
     *                     type="string",
     *                     description="created date of the client"
     *                 ),
     *                 @OA\Property(
     *                     property="updated_at",
     *                     type="string",
     *                     description="updated date of the client"
     *                 )
     *              )
     *          )
     *      ),
     *       @OA\Response(
     *           response=403, 
     *           description="Forbidden",
     *           @OA\JsonContent(
     *               type="object",
     *               @OA\Property(
     *                   property="message",
     *                   type="string",
     *                   description="message"
     *               )
     *           )
     *       )
     *  )
     */
    public function index(){
        return response()->json(Role::all(), Response::HTTP_OK);
    }

    /**
     *  @OA\Post(
     *      path="/api/role/",
     *      tags={"Roles"},
     *      summary="Create a role",
     *      description="Create a role",
     *      security={{"bearer": {} }},
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"name"},
     *              @OA\Property(property="name", type="string", example="worker")
     *          )
     *      ),
     *       @OA\Response(
     *           response=201,
     *           description="Created",
     *           @OA\JsonContent(
     *               type="object",
     *               @OA\Property(
     *                   property="message",
     *                   type="string",
     *                   description="message"
     *               ),
     *               @OA\Property(
     *                   property="role",
     *                   type="object",
     *                  @OA\Property(
     *                      property="id",
     *                      type="integer",
     *                      description="Id of the role"
     *                  ),
     *                  @OA\Property(
     *                      property="name",
     *                      type="string",
     *                      description="Name of the role"
     *                  ),
     *                  @OA\Property(
     *                      property="guard_name",
     *                      type="string",
     *                      description="name of the guard"
     *                  ),
     *                 @OA\Property(
     *                     property="created_at",
     *                     type="string",
     *                     description="created date of the client"
     *                 ),
     *                 @OA\Property(
     *                     property="updated_at",
     *                     type="string",
     *                     description="updated date of the client"
     *                 ) 
     *               )
     *           )
     *       ),
     *       @OA\Response(
     *           response=422,
     *           description="Unprocessable Content",
     *           @OA\JsonContent(
     *               type="object",
     *               @OA\Property(
     *                   property="message",
     *                   type="string",
     *                   description="message"
     *               )
     *           )
     *       ),
     *       @OA\Response(
     *           response=403, 
     *           description="Forbidden",
     *           @OA\JsonContent(
     *               type="object",
     *               @OA\Property(
     *                   property="message",
     *                   type="string",
     *                   description="message"
     *               )
     *           )
     *       )
     *  )
     */
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

    /**
     *  @OA\Get(
     *      path="/api/role/{id}",
     *      tags={"Roles"},
     *      summary="Get a role",
     *      description="Get a role",
     *      security={{"bearer": {} }},
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          @OA\Schema(type="integer")
     *      ),
     *       @OA\Response(
     *           response=200,
     *           description="Ok",
     *           @OA\JsonContent(
     *               type="object",
     *               @OA\Property(
     *                   property="role",
     *                   type="object",
     *                       @OA\Property(
     *                           property="id",
     *                           type="integer",
     *                           description="Id of the role"
     *                       ),
     *                       @OA\Property(
     *                           property="name",
     *                           type="string",
     *                           description="Name of the role"
     *                       ),
     *                       @OA\Property(
     *                           property="guard_name",
     *                           type="string",
     *                           description="name of the guard"
     *                       ),
     *                      @OA\Property(
     *                          property="created_at",
     *                          type="string",
     *                          description="created date of the client"
     *                      ),
     *                      @OA\Property(
     *                          property="updated_at",
     *                          type="string",
     *                          description="updated date of the client"
     *                      ), 
     *                      @OA\Property(
     *                          property="permissions",
     *                          type="array",
     *                          @OA\Items(
     *                              type="object",
     *                              @OA\Property(
     *                                  property="id",
     *                                  type="integer",
     *                                  description="Id of the permission"
     *                              ),
     *                              @OA\Property(
     *                                  property="name",
     *                                  type="string",
     *                                  description="Name of the permission"
     *                              ),
     *                              @OA\Property(
     *                                  property="guard_name",
     *                                  type="string",
     *                                  description="name of the guard"
     *                              ),
     *                             @OA\Property(
     *                                 property="created_at",
     *                                 type="string",
     *                                 description="created date of the permission"
     *                             ),
     *                             @OA\Property(
     *                                 property="updated_at",
     *                                 type="string",
     *                                 description="updated date of the permission"
     *                             )
     *                          )
     *                      ) 
     *               )
     *           )
     *       ),
     *       @OA\Response(
     *           response=404,
     *           description="Not found",
     *           @OA\JsonContent(
     *               type="object",
     *               @OA\Property(
     *                   property="message",
     *                   type="string",
     *                   description="message"
     *               )
     *           )
     *       ),
     *       @OA\Response(
     *           response=403, 
     *           description="Forbidden",
     *           @OA\JsonContent(
     *               type="object",
     *               @OA\Property(
     *                   property="message",
     *                   type="string",
     *                   description="message"
     *               )
     *           )
     *       )
     *  )
     */
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

    /**
     *  @OA\Put(
     *      path="/api/role/{id}",
     *      tags={"Roles"},
     *      summary="Update a role",
     *      description="Update a role",
     *      security={{"bearer": {} }},
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"name"},
     *              @OA\Property(property="name", type="string", example="worker")
     *          )
     *      ),
     *       @OA\Response(
     *           response=200,
     *           description="Ok",
     *           @OA\JsonContent(
     *               type="object",
     *               @OA\Property(
     *                   property="message",
     *                   type="string",
     *                   description="message"
     *               ),
     *               @OA\Property(
     *                   property="role",
     *                   type="object",
     *                       @OA\Property(
     *                           property="id",
     *                           type="integer",
     *                           description="Id of the role"
     *                       ),
     *                       @OA\Property(
     *                           property="name",
     *                           type="string",
     *                           description="Name of the role"
     *                       ),
     *                       @OA\Property(
     *                           property="guard_name",
     *                           type="string",
     *                           description="name of the guard"
     *                       ),
     *                       @OA\Property(
     *                           property="created_at",
     *                           type="string",
     *                           description="created date of the client"
     *                       ),
     *                       @OA\Property(
     *                           property="updated_at",
     *                           type="string",
     *                           description="updated date of the client"
     *                       ), 
     *                       @OA\Property(
     *                           property="permissions",
     *                           type="array",
     *                           @OA\Items(
     *                               type="object",
     *                               @OA\Property(
     *                                   property="id",
     *                                   type="integer",
     *                                   description="Id of the permission"
     *                               ),
     *                               @OA\Property(
     *                                   property="name",
     *                                   type="string",
     *                                   description="Name of the permission"
     *                               ),
     *                               @OA\Property(
     *                                   property="guard_name",
     *                                   type="string",
     *                                   description="name of the guard"
     *                               ),
     *                              @OA\Property(
     *                                  property="created_at",
     *                                  type="string",
     *                                  description="created date of the permission"
     *                              ),
     *                              @OA\Property(
     *                                  property="updated_at",
     *                                  type="string",
     *                                  description="updated date of the permission"
     *                              )
     *                           )
     *                       )
     *               )
     *           )
     *       ),
     *       @OA\Response(
     *           response=422,
     *           description="Unprocessable Content",
     *           @OA\JsonContent(
     *               type="object",
     *               @OA\Property(
     *                   property="message",
     *                   type="string",
     *                   description="message"
     *               )
     *           )
     *       ),
     *       @OA\Response(
     *           response=404,
     *           description="Not found",
     *           @OA\JsonContent(
     *               type="object",
     *               @OA\Property(
     *                   property="message",
     *                   type="string",
     *                   description="message"
     *               )
     *           )
     *       ),
     *       @OA\Response(
     *           response=403, 
     *           description="Forbidden",
     *           @OA\JsonContent(
     *               type="object",
     *               @OA\Property(
     *                   property="message",
     *                   type="string",
     *                   description="message"
     *               )
     *           )
     *       )
     *  )
     */
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

    /**
     *  @OA\Delete(
     *      path="/api/role/{id}",
     *      tags={"Roles"},
     *      summary="Delete a role",
     *      description="Delete a role",
     *      security={{"bearer": {} }},
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          @OA\Schema(type="integer")
     *      ),
     *       @OA\Response(
     *           response=200,
     *           description="Ok",
     *           @OA\JsonContent(
     *               type="object",
     *               @OA\Property(
     *                   property="message",
     *                   type="string",
     *                   description="message"
     *               )
     *               )
     *           )
     *       ),
     *       @OA\Response(
     *           response=404,
     *           description="Not found",
     *           @OA\JsonContent(
     *               type="object",
     *               @OA\Property(
     *                   property="message",
     *                   type="string",
     *                   description="message"
     *               )
     *           )
     *       ),
     *       @OA\Response(
     *           response=403, 
     *           description="Forbidden",
     *           @OA\JsonContent(
     *               type="object",
     *               @OA\Property(
     *                   property="message",
     *                   type="string",
     *                   description="message"
     *               )
     *           )
     *       )
     *  )
     */
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

    
    /**
     *  @OA\Post(
     *      path="/api/role/assignRole",
     *      tags={"Roles"},
     *      summary="Assign a role to a user",
     *      description="Assign a role to a user",
     *      security={{"bearer": {} }},
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"user_id","role_id"},
     *              @OA\Property(property="user_id", type="string", example="1"),
     *              @OA\Property(property="role_id", type="string", example="1")
     *          )
     *      ),
     *       @OA\Response(
     *           response=200,
     *           description="Ok",
     *           @OA\JsonContent(
     *               type="object",
     *               @OA\Property(
     *                   property="message",
     *                   type="string",
     *                   description="message"
     *               )
     *           )
     *       ),
     *       @OA\Response(
     *           response=422,
     *           description="Unprocessable Content",
     *           @OA\JsonContent(
     *               type="object",
     *               @OA\Property(
     *                   property="message",
     *                   type="string",
     *                   description="message"
     *               )
     *           )
     *       ),
     *       @OA\Response(
     *           response=400,
     *           description="Bad Request",
     *           @OA\JsonContent(
     *               type="object",
     *               @OA\Property(
     *                   property="message",
     *                   type="string",
     *                   description="message"
     *               )
     *           )
     *       ),
     *       @OA\Response(
     *           response=404,
     *           description="Not found",
     *           @OA\JsonContent(
     *               type="object",
     *               @OA\Property(
     *                   property="message",
     *                   type="string",
     *                   description="message"
     *               )
     *           )
     *       ),
     *       @OA\Response(
     *           response=403, 
     *           description="Forbidden",
     *           @OA\JsonContent(
     *               type="object",
     *               @OA\Property(
     *                   property="message",
     *                   type="string",
     *                   description="message"
     *               )
     *           )
     *       )
     *  )
     */
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

    
    /**
     *  @OA\Post(
     *      path="/api/role/removeRole",
     *      tags={"Roles"},
     *      summary="Remove a role to a user",
     *      description="Remove a role to a user",
     *      security={{"bearer": {} }},
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"user_id","role_id"},
     *              @OA\Property(property="user_id", type="string", example="1"),
     *              @OA\Property(property="role_id", type="string", example="1")
     *          )
     *      ),
     *       @OA\Response(
     *           response=200,
     *           description="Ok",
     *           @OA\JsonContent(
     *               type="object",
     *               @OA\Property(
     *                   property="message",
     *                   type="string",
     *                   description="message"
     *               )
     *           )
     *       ),
     *       @OA\Response(
     *           response=422,
     *           description="Unprocessable Content",
     *           @OA\JsonContent(
     *               type="object",
     *               @OA\Property(
     *                   property="message",
     *                   type="string",
     *                   description="message"
     *               )
     *           )
     *       ),
     *       @OA\Response(
     *           response=400,
     *           description="Bad Request",
     *           @OA\JsonContent(
     *               type="object",
     *               @OA\Property(
     *                   property="message",
     *                   type="string",
     *                   description="message"
     *               )
     *           )
     *       ),
     *       @OA\Response(
     *           response=404,
     *           description="Not found",
     *           @OA\JsonContent(
     *               type="object",
     *               @OA\Property(
     *                   property="message",
     *                   type="string",
     *                   description="message"
     *               )
     *           )
     *       ),
     *       @OA\Response(
     *           response=403, 
     *           description="Forbidden",
     *           @OA\JsonContent(
     *               type="object",
     *               @OA\Property(
     *                   property="message",
     *                   type="string",
     *                   description="message"
     *               )
     *           )
     *       )
     *  )
     */
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

    /**
     *  @OA\Post(
     *      path="/api/role/givePermission",
     *      tags={"Roles"},
     *      summary="Give a permission to a role",
     *      description="Give a permission to a role",
     *      security={{"bearer": {} }},
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"role_id", "permission_id"},
     *              @OA\Property(property="role_id", type="string", example="1"),
     *              @OA\Property(property="permission_id", type="string", example="2")
     *          )
     *      ),
     *      @OA\Response(
     *           response=200,
     *           description="Ok",
     *           @OA\JsonContent(
     *               type="object",
     *               @OA\Property(
     *                   property="message",
     *                   type="string",
     *                   description="message"
     *               )
     *           )
     *       ),
     *       @OA\Response(
     *           response=422,
     *           description="Unprocessable Content",
     *           @OA\JsonContent(
     *               type="object",
     *               @OA\Property(
     *                   property="message",
     *                   type="string",
     *                   description="message"
     *               )
     *           )
     *       ),
     *       @OA\Response(
     *           response=400,
     *           description="Bad Request",
     *           @OA\JsonContent(
     *               type="object",
     *               @OA\Property(
     *                   property="message",
     *                   type="string",
     *                   description="message"
     *               )
     *           )
     *       ),
     *       @OA\Response(
     *           response=404,
     *           description="Not found",
     *           @OA\JsonContent(
     *               type="object",
     *               @OA\Property(
     *                   property="message",
     *                   type="string",
     *                   description="message"
     *               )
     *           )
     *       ),
     *       @OA\Response(
     *           response=403, 
     *           description="Forbidden",
     *           @OA\JsonContent(
     *               type="object",
     *               @OA\Property(
     *                   property="message",
     *                   type="string",
     *                   description="message"
     *               )
     *           )
     *       )
     *  )
     */
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

    /**
     *  @OA\Post(
     *      path="/api/role/revokePermission",
     *      tags={"Roles"},
     *      summary="Revoke a permission to a role",
     *      description="Revoke a permission to a role",
     *      security={{"bearer": {} }},
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"role_id", "permission_id"},
     *              @OA\Property(property="role_id", type="string", example="1"),
     *              @OA\Property(property="permission_id", type="string", example="2")
     *          )
     *      ),
     *       @OA\Response(
     *           response=200,
     *           description="Ok",
     *           @OA\JsonContent(
     *               type="object",
     *               @OA\Property(
     *                   property="message",
     *                   type="string",
     *                   description="message"
     *               )
     *           )
     *       ),
     *       @OA\Response(
     *           response=422,
     *           description="Unprocessable Content",
     *           @OA\JsonContent(
     *               type="object",
     *               @OA\Property(
     *                   property="message",
     *                   type="string",
     *                   description="message"
     *               )
     *           )
     *       ),
     *       @OA\Response(
     *           response=400,
     *           description="Bad Request",
     *           @OA\JsonContent(
     *               type="object",
     *               @OA\Property(
     *                   property="message",
     *                   type="string",
     *                   description="message"
     *               )
     *           )
     *       ),
     *       @OA\Response(
     *           response=404,
     *           description="Not found",
     *           @OA\JsonContent(
     *               type="object",
     *               @OA\Property(
     *                   property="message",
     *                   type="string",
     *                   description="message"
     *               )
     *           )
     *       ),
     *       @OA\Response(
     *           response=403, 
     *           description="Forbidden",
     *           @OA\JsonContent(
     *               type="object",
     *               @OA\Property(
     *                   property="message",
     *                   type="string",
     *                   description="message"
     *               )
     *           )
     *       )
     *  )
     */
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
