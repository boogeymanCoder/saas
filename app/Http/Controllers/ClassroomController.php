<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\Student;
use App\Models\Teacher;
use Exception;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ClassroomController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return QueryBuilder::for(Classroom::class)
            ->allowedFilters(['name', "code"])
            ->defaultSort('name')
            ->allowedSorts(['name', "code",])
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
                    "name" => "required|string|unique:classrooms,name",
                    "code" => "required|string|unique:classrooms,code",
                    "teacher_id" => "required|exists:teachers,id",
                    "subject_id" => "required|exists:subjects,id",
                ]
            );
            $classroom = Classroom::create($request->all());
            return response(["success" => true, "data" => $classroom, "errorMessage" => null], 201);
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
        $classroom = Classroom::with(['teacher', 'subject'])->find($id);

        if (!$classroom) return response(["success" => false, "data" => null, "errorMessage" => "Classroom not found."], 404);

        return response(["success" => true, "data" => $classroom, "errorMessage" => null]);
    }

    /**
     * Display the students of this classroom.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function students($id)
    {
        $classroom = Classroom::find($id);
        if (!$classroom) return response(["success" => false, "data" => null, "errorMessage" => "Classroom not found."], 404);
        $students = QueryBuilder::for($classroom->students())
            ->allowedFilters(['first_name', "middle_name", "last_name", "address", "birthday", AllowedFilter::exact('gender'), "number", 'email'])
            ->defaultSort('first_name')
            ->allowedSorts(['first_name', "middle_name", "last_name", "address", "birthday", "number", 'email'])
            ->jsonPaginate();

        foreach ($classroom->students as $student) {
            echo $student->pivot->created_at;
        }

        return response(["success" => true, "data" => $students, "errorMessage" => null]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function removeStudent($id, $student_id)
    {
        $classroom =  Classroom::find($id);
        if (!$classroom) return response(["success" => false, "data" => null, "errorMessage" => "Classroom not found."], 404);

        $student = $classroom->students()->find($student_id);
        if (!$student) return response(["success" => false, "data" => null, "errorMessage" => "Student not found on classroom."], 404);

        $classroom->students()->detach($student_id);

        return response(["success" => true, "data" => 1, "errorMessage" => null]);
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
                    "name" => "string|unique:classrooms,name," . $id,
                    "code" => "string|unique:classrooms,code," . $id,
                ]
            );

            $classroom = Classroom::find($id);

            if (!$classroom) return response(["success" => false, "data" => null, "errorMessage" => "Classroom not found."], 404);

            $classroom->update($request->all());
            return response(["success" => true, "data" => $classroom, "errorMessage" => null]);
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
    public function addStudent($id, $student_id)
    {
        $classroom =  Classroom::find($id);
        $student =  Student::find($student_id);
        if (!$classroom) return response(["success" => false, "data" => null, "errorMessage" => "Classroom not found."], 404);
        if (!$student) return response(["success" => false, "data" => null, "errorMessage" => "Classroom not found."], 404);

        $student_exists = $classroom->students()->find($student_id);
        if ($student_exists) return response(["success" => false, "data" => null, "errorMessage" => "Student already inside the Classroom."], 400);

        $classroom->students()->attach($student);

        return response(["success" => true, "data" => 1, "errorMessage" => null]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $classroom =  Classroom::destroy($id);
        if (!$classroom) return response(["success" => false, "data" => null, "errorMessage" => "Classroom not found."], 404);

        return response(["success" => true, "data" => 1, "errorMessage" => null]);
    }
}
