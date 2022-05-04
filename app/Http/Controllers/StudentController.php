<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return QueryBuilder::for(Student::class)
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
                ]
            );
            return response(["success" => true, "data" => Student::create($request->all()), "errorMessage" => null], 201);
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
        $student = Student::find($id);

        if (!$student) return response(["success" => false, "data" => null, "errorMessage" => "Student not found."], 404);

        return response(["success" => true, "data" => $student, "errorMessage" => null]);
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function classrooms($id)
    {
        $student = Student::find($id);
        $classrooms = QueryBuilder::for($student->classrooms())
            ->allowedFilters(['name', "code"])
            ->defaultSort('name')
            ->allowedSorts(['name', "code",])
            ->jsonPaginate();

        if (!$student) return response(["success" => false, "data" => null, "errorMessage" => "Student not found."], 404);

        foreach ($student->classrooms as $classroom) {
            echo $classroom->pivot->created_at;
        }

        return response($classrooms);
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
                ]
            );

            $student = Student::find($id);
            if (!$student) return response(["success" => false, "data" => null, "errorMessage" => "Student not found."], 404);

            $student->update($request->all());
            return response(["success" => true, "data" => $student, "errorMessage" => null]);
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
        $student =  Student::destroy($id);
        if (!$student) return response(["success" => false, "data" => null, "errorMessage" => "Student not found."], 404);

        return response(["success" => true, "data" => 1, "errorMessage" => null]);
    }
}
