<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use Illuminate\Http\Request;

class DomainController extends Controller
{
    public function checkAvailability($domain)
    {
        $tenant = Tenant::find($domain);

        if (!$tenant) {
            return response(["success" => false, "data" => null, "errorMessage" => "Domain does not exist."], 404);
        }

        return response(["success" => true, "data" => $tenant, "errorMessage" => null]);
    }
}
