<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\RegisterUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Validation\ValidationException;

class UsersController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api')->except(['store']);
    }

    public function index(Request $request)
    {
        return UserResource::collection(
            User::paginate($request->get('perPage'))
        );
    }

    public function store(RegisterUserRequest $request) 
    {
        return new UserResource(User::create(
            $request->safe()->merge([
                'password' => Hash::make($request->get('password'))
            ])->only(['role', 'username', 'password'])
        ));
    }

    public function show(int $id)
    {
        return new UserResource(User::findOrFail($id));
    }

    public function update(UpdateUserRequest $request, int $id)
    {
        $user = User::findOrFail($id);

        if( ! Hash::check($request->get('old_password'), $user->password)) {
            throw ValidationException::withMessages([
                'password' => 'Invalid password'
            ]);
        }

        $user->update([
            'password' => Hash::make($request->get('password')),
        ]);
        
        return new UserResource($user);
    }

    public function destroy(int $id)
    {
        $user = User::findOrFail($id);

        $user->delete();

        return response()->noContent();
    }
}