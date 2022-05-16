<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\Request;

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
        $user = User::create($user_data);
        $token = $user->createToken(env("TOKEN_SECRET"))->plainTextToken;

        $domain = $request->get("domain");
        $tenant = Tenant::create(['id' => $domain, "user_id" => $user->id]);
        $tenant->domains()->create(['domain' => $domain . "." . env('HOSTNAME')]);

        return response(["success" => true, "data" => ["tenant" => $tenant, "user" => $user, "token" => $token], "errorMessage" => null]);
    }
}
