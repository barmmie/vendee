<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\InvalidCredentialException;
use App\Exceptions\UserAlreadyLoggedInException;
use App\Http\Requests\LoginRequest;
use App\Actions\AuthenticateApiUserAction;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AuthController
{

    public function store(LoginRequest $request, AuthenticateApiUserAction $authenticateUserAction)
    {
        try {
            $token = $authenticateUserAction->execute($request->get('username'), $request->get('password'));

            return response()->json([
                'token' => $token,
                'user' => new UserResource(auth()->user())
            ]);
        } catch (InvalidCredentialException $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        } catch (UserAlreadyLoggedInException $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], Response::HTTP_CONFLICT);
        }
    }

    public function delete(Request $request)
    {
        $request->user()->forceFill([
            'api_token' => null
        ])->save();

        return response()->noContent();
    }

    public function destroy(Request $request)
    {
        $request->user('web')->forceFill([
            'api_token' => null
        ])->save();

        return response()->noContent();
    }
}
