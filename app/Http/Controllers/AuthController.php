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

        $domain = $request->get("domain");
        $tenant = Tenant::create(['id' => $domain,]);
        $tenant->domains()->create(['domain' => $domain . "." . env('HOSTNAME')]);

        // $new_domain = $tenant->domains->with("tenant")->find();

        return response(["success" => true, "data" => $tenant, "errorMessage" => null]);
    }
}
