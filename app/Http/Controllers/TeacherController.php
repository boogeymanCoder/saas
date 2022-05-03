<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use Exception;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class TeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return QueryBuilder::for(Teacher::class)
            ->allowedFilters(['first_name', "middle_name", "last_name", "address", "birthday", AllowedFilter::exact('gender'), "number", 'email'])
            ->defaultSort('first_name')
            ->allowedSorts(['first_name', "middle_name", "last_name", "address", "birthday", "number", 'email'])
            ->jsonPaginate();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $request->validate(
                [
                    "first_name" => "required|string",
                    "last_name" => "required|string",
                    "address" => "required|string",
                    "birthday" => "required|date",
                    "gender" => "required||string|in:Male,Female",
                    "number" => "required|string",
                    "email" => "required|string",
                    "password" => "required|string|confirmed",
                ]
            );
            return response(["success" => true, "data" => Teacher::create($request->all()), "errorMessage" => null], 201);
        } catch (Exception $exception) {
            return response(["success" => false, "data" => null, "errorMessage" => $exception->getMessage()], 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $teacher = Teacher::find($id);

        if (!$teacher) return response(["success" => false, "data" => null, "errorMessage" => "Teacher not found."], 404);

        return response(["success" => true, "data" => $teacher, "errorMessage" => null]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $request->validate(
                [
                    "gender" => "string|in:Male,Female",
                    "password" => "string|confirmed"
                ]
            );

            $teacher = Teacher::find($id);
            if (!$teacher) return response(["success" => false, "data" => null, "errorMessage" => "Teacher not found."], 404);

            $teacher->update($request->all());
            return response(["success" => true, "data" => $teacher, "errorMessage" => null]);
        } catch (Exception $exception) {
            return response(["success" => false, "data" => null, "errorMessage" => $exception->getMessage()], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $teacher =  Teacher::destroy($id);
        if (!$teacher) return response(["success" => false, "data" => null, "errorMessage" => "Teacher not found."], 404);

        return response(["success" => true, "data" => 1, "errorMessage" => null]);
    }
}
