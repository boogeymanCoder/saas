<?php

declare(strict_types=1);

use App\Http\Controllers\ClassroomController;
use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

use App\Http\Controllers\StudentController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TeacherController;

/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
|
| Here you can register the tenant routes for your application.
| These routes are loaded by the TenantRouteServiceProvider.
|
| Feel free to customize them however you want. Good luck!
|
*/

Route::middleware([
    'web',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
])->group(function () {
    Route::get('/', function () {
        return 'WEB: This is your multi-tenant application. The id of the current tenant is ' . tenant('id');
    });
});


Route::middleware([
    'api',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
])->prefix('/api')->group(function () {
    Route::get('/tenant', function () {
        return 'API: This is your multi-tenant application. The id of the current tenant is ' . tenant('id');
    });

    Route::resource("students", StudentController::class);
    Route::resource("teachers", TeacherController::class);
    // TODO /teachers/classes
    Route::resource("subjects", SubjectController::class);
    Route::resource("classrooms", ClassroomController::class);
    Route::get('/classrooms/{id}/students', [ClassroomController::class, "students"]);
    Route::delete('/classrooms/{id}/students/{student_id}', [ClassroomController::class, "removeStudent"]);
});
