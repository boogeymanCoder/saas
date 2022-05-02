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
    public function index(Request $request)
    {
        $request->validate([
            "sort_column" =>
            "in:first_name,middle_name,last_name,address,birthday,gender,number,email",
            "sort_order" => "in:asc,desc",
        ]);

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

        return response(Student::create($request->all()), 201);
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

        return $student;
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
        $request->validate(
            [
                "gender" => "required|string|in:Male,Female",
            ]
        );

        $student = Student::find($id);

        $student->update($request->all());
        return $student;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return Student::destroy($id);
    }
}
