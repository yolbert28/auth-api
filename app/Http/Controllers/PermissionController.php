<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;
use Symfony\Component\HttpFoundation\Response;

class PermissionController extends Controller
{
    /**
     *  @OA\Get(
     *      path="/api/permission/",
     *      tags={"Permissions"},
     *      summary="Get a list of permissions",
     *      description="Get a list of permissions",
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
     *                      description="Id of the permission"
     *                  ),
     *                  @OA\Property(
     *                      property="name",
     *                      type="string",
     *                      description="Name of the permission"
     *                  ),
     *                  @OA\Property(
     *                      property="guard_name",
     *                      type="string",
     *                      description="name of the guard"
     *                  ),
     *                 @OA\Property(
     *                     property="created_at",
     *                     type="string",
     *                     description="created date of the permission"
     *                 ),
     *                 @OA\Property(
     *                     property="updated_at",
     *                     type="string",
     *                     description="updated date of the permission"
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
        return response()->json(
            Permission::all(),
            Response::HTTP_OK
        );
    }

    /**
     *  @OA\Post(
     *      path="/api/permission/",
     *      tags={"Permissions"},
     *      summary="Create a permission",
     *      description="Create a permission",
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
     *                   property="permission",
     *                   type="object",
     *                  @OA\Property(
     *                      property="id",
     *                      type="integer",
     *                      description="Id of the permission"
     *                  ),
     *                  @OA\Property(
     *                      property="name",
     *                      type="string",
     *                      description="Name of the permission"
     *                  ),
     *                  @OA\Property(
     *                      property="guard_name",
     *                      type="string",
     *                      description="name of the guard"
     *                  ),
     *                 @OA\Property(
     *                     property="created_at",
     *                     type="string",
     *                     description="created date of the permission"
     *                 ),
     *                 @OA\Property(
     *                     property="updated_at",
     *                     type="string",
     *                     description="updated date of the permission"
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

    /**
     *  @OA\Get(
     *      path="/api/permission/{id}",
     *      tags={"Permissions"},
     *      summary="Get a permission",
     *      description="Get a permission",
     *      security={{"bearer": {} }},
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Ok",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  property="permission",
     *                  type="object",
     *                  @OA\Property(
     *                      property="id",
     *                      type="integer",
     *                      description="Id of the permission"
     *                  ),
     *                  @OA\Property(
     *                      property="name",
     *                      type="string",
     *                      description="Name of the permission"
     *                  ),
     *                  @OA\Property(
     *                      property="guard_name",
     *                      type="string",
     *                      description="name of the guard"
     *                  ),
     *                 @OA\Property(
     *                     property="created_at",
     *                     type="string",
     *                     description="created date of the permission"
     *                 ),
     *                 @OA\Property(
     *                     property="updated_at",
     *                     type="string",
     *                     description="updated date of the permission"
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

    /**
     *  @OA\Put(
     *      path="/api/permission/{id}",
     *      tags={"Permissions"},
     *      summary="Update a permission",
     *      description="Update a permission",
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
     *                   property="permission",
     *                   type="object",
     *                       @OA\Property(
     *                           property="id",
     *                           type="integer",
     *                           description="Id of the permission"
     *                       ),
     *                       @OA\Property(
     *                           property="name",
     *                           type="string",
     *                           description="Name of the permission"
     *                       ),
     *                       @OA\Property(
     *                           property="guard_name",
     *                           type="string",
     *                           description="name of the guard"
     *                       ),
     *                      @OA\Property(
     *                          property="created_at",
     *                          type="string",
     *                          description="created date of the permission"
     *                      ),
     *                      @OA\Property(
     *                          property="updated_at",
     *                          type="string",
     *                          description="updated date of the permission"
     *                      )
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

    /**
     *  @OA\Delete(
     *      path="/api/permission/{id}",
     *      tags={"Permissions"},
     *      summary="Delete a permission",
     *      description="Delete a permission",
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
