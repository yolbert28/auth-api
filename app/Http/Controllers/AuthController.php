<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterUserRequest;
use App\Models\User;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class AuthController extends Controller implements HasMiddleware
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public static function middleware(): array
    {
        return [
            new Middleware('auth:api', except: ['login','register'])
        ];
    }

    /**
     *  @OA\Post(
     *      path="/api/auth/login",
     *      tags={"Auth"},
     *      summary="Login",
     *      description="Login",
     *      security={{"bearer": {} }},
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"email", "password"},
     *              @OA\Property(property="email", type="string", example="user@exam.com"),
     *              @OA\Property(property="password", type="string", example="password")
     *          )
     *      ),
     *       @OA\Response(
     *           response=200,
     *           description="Ok",
     *           @OA\JsonContent(
     *               type="object",
     *               @OA\Property(
     *                   property="access_token",
     *                   type="string",
     *                   description="access token"
     *               ),
     *               @OA\Property(
     *                   property="token_type",
     *                   type="string",
     *                   description="token type"
     *               ),
     *               @OA\Property(
     *                   property="expires_in",
     *                   type="string",
     *                   description="expiration time"
     *               )
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
     *       )
     *  )
     */
    public function login()
    {
        $credentials = request(['email', 'password']);

        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     *  @OA\Post(
     *      path="/api/auth/register",
     *      tags={"Auth"},
     *      summary="Register",
     *      description="Register",
     *      security={{"bearer": {} }},
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"name","email", "password", "password_confirmation"},
     *              @OA\Property(property="name", type="string", example="user"),
     *              @OA\Property(property="email", type="string", example="user@exam.com"),
     *              @OA\Property(property="password", type="string", example="password"),
     *              @OA\Property(property="password_confirmation", type="string", example="password")
     *          )
     *      ),
     *       @OA\Response(
     *           response=200,
     *           description="Ok",
     *           @OA\JsonContent(
     *               type="object",
     *               @OA\Property(
     *                   property="access_token",
     *                   type="string",
     *                   description="access token"
     *               ),
     *               @OA\Property(
     *                   property="token_type",
     *                   type="string",
     *                   description="token type"
     *               ),
     *               @OA\Property(
     *                   property="expires_in",
     *                   type="string",
     *                   description="expiration time"
     *               )
     *               )
     *           )
     *       )
     *  )
     */
    public function register(RegisterUserRequest $request){
        $validatedData = $request->validated();

        $user = User::create($validatedData);

        $token = auth()->login($user);

        return $this->respondWithToken($token);
    }

    /**
     *  @OA\Get(
     *      path="/api/auth/me",
     *      tags={"Auth"},
     *      summary="User information",
     *      description="User information",
     *      security={{"bearer": {} }},
     *      @OA\Response(
     *          response=200,
     *          description="Ok",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                      property="id",
     *                      type="integer",
     *                      description="Id of the user"
     *                  ),
     *                  @OA\Property(
     *                      property="name",
     *                      type="string",
     *                      description="Name of the user"
     *                  ),
     *                  @OA\Property(
     *                      property="email",
     *                      type="string",
     *                      description="email of the user"
     *                  ),
     *                  @OA\Property(
     *                      property="email_verified_at",
     *                      type="string"
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
     *          )
     *      ),
     *       @OA\Response(
     *           response=401,
     *           description="Unauthorized",
     *           @OA\JsonContent(
     *               type="object",
     *               @OA\Property(
     *                   property="error",
     *                   type="string",
     *                   description="message"
     *               )
     *           )
     *       )
     *  )
     */
    public function me()
    {
        return response()->json(auth()->user(), 200);
    }

    /**
     *  @OA\Post(
     *      path="/api/auth/logout",
     *      tags={"Auth"},
     *      summary="Logout",
     *      description="Logout",
     *      security={{"bearer": {} }},
     *      @OA\Response(
     *          response=200,
     *          description="Ok",
     *          @OA\JsonContent(
     *               type="object",
     *               @OA\Property(
     *                   property="message",
     *                   type="string",
     *                   description="message"
     *               )
     *           )
     *      ),
     *       @OA\Response(
     *           response=401,
     *           description="Unauthorized",
     *           @OA\JsonContent(
     *               type="object",
     *               @OA\Property(
     *                   property="error",
     *                   type="string",
     *                   description="message"
     *               )
     *           )
     *       )
     *  )
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out'], 200);
    }

    /**
     *  @OA\Post(
     *      path="/api/auth/refresh",
     *      tags={"Auth"},
     *      summary="Refresh",
     *      description="Refresh",
     *      security={{"bearer": {} }},
     *       @OA\Response(
     *           response=200,
     *           description="Ok",
     *           @OA\JsonContent(
     *               type="object",
     *               @OA\Property(
     *                   property="access_token",
     *                   type="string",
     *                   description="access token"
     *               ),
     *               @OA\Property(
     *                   property="token_type",
     *                   type="string",
     *                   description="token type"
     *               ),
     *               @OA\Property(
     *                   property="expires_in",
     *                   type="string",
     *                   description="expiration time"
     *               )
     *               )
     *           )
     *       ),
     *       @OA\Response(
     *           response=401,
     *           description="Unauthorized",
     *           @OA\JsonContent(
     *               type="object",
     *               @OA\Property(
     *                   property="error",
     *                   type="string",
     *                   description="message"
     *               )
     *           )
     *       )
     *  )
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}
