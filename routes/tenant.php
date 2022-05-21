<?php

declare(strict_types=1);

use App\Http\Controllers\AuthController;
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
    Route::post('/login', [AuthController::class, "login"]);


    Route::group(["middleware" => ["auth:sanctum"]], function () {
        Route::get('/user', [AuthController::class, "user"]);
        Route::post('/logout', [AuthController::class, "logout"]);
        Route::put('/update', [AuthController::class, "update"]);

        Route::resource("students", StudentController::class);
        Route::get('/students/{id}/classrooms', [StudentController::class, "classrooms"]);

        Route::resource("teachers", TeacherController::class);
        Route::get('/teachers/{id}/classrooms', [TeacherController::class, "classrooms"]);

        Route::resource("subjects", SubjectController::class);
        Route::get('/subjects/{id}/classrooms', [SubjectController::class, "classrooms"]);

        Route::resource("classrooms", ClassroomController::class);
        Route::get('/classrooms/{id}/students', [ClassroomController::class, "students"]);
        Route::delete('/classrooms/{id}/students/{student_id}', [ClassroomController::class, "removeStudent"]);
        Route::put('/classrooms/{id}/students/{student_id}', [ClassroomController::class, "addStudent"]);
        Route::put('/classrooms/{id}/teacher/{teacher_id}', [ClassroomController::class, "updateTeacher"]);
        Route::put('/classrooms/{id}/subject/{subject_id}', [ClassroomController::class, "updateSubject"]);
    });

    Route::get('/tenant', function () {
        return 'API: This is your multi-tenant application. The id of the current tenant is ' . tenant('id');
    });
});
