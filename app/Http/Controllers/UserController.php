<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index(Request $request): UserCollection
    {
        $query = $request->get('query');

        if ($query) {

            $users = User::where('name', 'like', '%' . $query . '%')
                        ->orWhere('email', 'like', '%' . $query . '%')
                        ->get();
        } else {
            $users = User::all();
        }


        return new UserCollection($users);

    }

    public function store(UserStoreRequest $request)
    {
        $data = $request->validated();
        $data['password'] = bcrypt($data['password']);
        $user = User::create($data);

        $user['token']= $user->createToken('MyApp')->plainTextToken;

        return response()->json(['message' =>'User creer avec succes ', 'data'=>$user],200);
    }

    public function show(Request $request, User $user): UserResource
    {
        return new UserResource($user);
    }

    public function update(UserUpdateRequest $request, User $user): UserResource
    {
        $user->update($request->validated());

        return new UserResource($user);
    }

    public function destroy(Request $request, User $user): Response
    {
        $user->delete();

        return response()->noContent();
    }
    public function login(Request $request){

        $validator = Validator::make($request->all(),[
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if($validator->fails()){
            return response()->json(['message' =>'le mot de passe ou le nom d utisateur. est invalide'],401);
        }



        if (Auth::attempt(array('email' => $request->input('email'), 'password' => $request->input('password')))) {

            $user = Auth::user();
            $user['token'] =  $user->createToken('MyApp')->plainTextToken;


            return response()->json(['message' =>'User connecte avec succes ', 'data'=>$user],200);

        }else {

            return response()->json(['message' =>'le mot de passe ou le nom d utisateur. est invalide'],401);

        }
    }
    public function logout(Request $request){
        $request->user()->token()->revoke();
        return response()->json(['message' => 'User deconnecte avec succes']);
    }
}
