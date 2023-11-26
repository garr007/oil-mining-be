<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Http\Resources\UserResource;
use App\Models\Employee;
use App\Models\Position;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    /**
     * Registers a user
     *
     * @return \Illuminate\Http\JsonResponse|void
     */
    public function register(RegisterUserRequest $request)
    {
        // unique validation is handled*
        $data = $request->validated();

        $user = new User($data);
        $user->uuid = User::newUuid();
        $user->password = Hash::make($data['password']);

        // if user is not created
        if (!$user->save()) {
            return $this->errResponse($request, "AuthController, saving user to db failed");
        }

        return $this->response(
            new UserResource($user),
            "user created",
            Response::HTTP_CREATED
        );
    }


    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginUserRequest $request)
    {
        $data = $request->validated();

        $user = User::with('employee.position.division')->where('email', $data['email'])->first();
        if (!$user || !Hash::check($data['password'], $user->password)) {
            throw new UnauthorizedHttpException('', 'wrong email/password');
        }

        $emp = $user->employee;

        // default
        $customClaims = [
            "is_admin" => $user->is_admin,
            "division" => null,
            "is_manager" => false,
        ];

        // if it's employee
        if ($emp) {
            if ($emp->position) {
                $customClaims["is_manager"] = strcasecmp($emp->position->name, Position::ROLE_MANAGER) == 0 ?
                    true : false;

                if ($emp->position->division) {
                    $customClaims["division"] = $emp->position->division->name;
                }
            }
        }

        $token = auth()->claims($customClaims)->tokenById($user->id);

        return $this->response(
            [
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => auth()->factory()->getTTL() * 60
            ],
            "Login success",
            Response::HTTP_OK
        );
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return $this->response(new UserResource(auth()->user()), "me", Response::HTTP_OK);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return $this->response(null, "Successfully logged out", Response::HTTP_OK);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->response(
            [
                'access_token' => auth()->refresh(),
                'token_type' => 'bearer',
                'expires_in' => auth()->factory()->getTTL() * 60
            ],
            "refresh token success",
            Response::HTTP_OK
        );
    }
}
