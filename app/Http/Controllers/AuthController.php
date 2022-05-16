<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Register a tenant user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $request->validate(
            [
                "name" => "required|string|unique:users,name",
                "email" => "required|string|unique:users,email",
                "password" => "required|string|confirmed",
                "domain" => "required|string|unique:domains,domain"
            ]
        );

        $user_data = $request->only(["name", "email", "password"]);
        $user_data["password"] = bcrypt($user_data["password"]);
        $user = User::create($user_data);
        $token = $user->createToken(env("TOKEN_SECRET"))->plainTextToken;

        $domain = $request->get("domain");
        $tenant = Tenant::create(['id' => $domain, "user_id" => $user->id]);
        $tenant->domains()->create(['domain' => $domain . "." . env('HOSTNAME')]);

        return response(["success" => true, "data" => ["tenant" => $tenant, "user" => $user, "token" => $token], "errorMessage" => null]);
    }

    /**
     * Login a user to get a token.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $fields = $request->validate(
            [
                "email" => "required|string",
                "password" => "required|string",
            ]
        );

        // Get user with matching email
        $user = User::where("email", $fields["email"])->first();

        // Check password
        if (!$user || !Hash::check($fields["password"], $user->password)) {
            return response(["success" => false, "data" => null, "errorMessage" => "Wrong credentials"]);
        }

        $token = $user->createToken(env("TOKEN_SECRET"))->plainTextToken;

        $response = [
            "success" => true,
            "data" => [
                "user" => $user,
                "token" => $token
            ],
            "errorMessage" => null
        ];

        return response($response, 201);
    }

    /**
     * Get currently logged in user.
     */
    public function user(Request $request)
    {
        $user =  User::with(["tenant" => function ($query) {
            $query->with('domains');
        }])->find($request->user()->id);
        return $user;
    }

    /**
     * Logout a user and delete all of the users token.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        $request
            ->user()
            ->tokens()
            ->delete();

        return response([
            "success" => true,
            "data" => [
                "logged_out" => true
            ],
            "errorMessage" => null
        ]);
    }
}
