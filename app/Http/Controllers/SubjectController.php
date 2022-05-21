<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Exception;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class SubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return QueryBuilder::for(Subject::class)
            ->allowedFilters([
                'name', "code",
                AllowedFilter::exact('id'),
            ])
            ->defaultSort('name')
            ->allowedSorts(['name', "code",])
            ->withCount('classrooms')
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
                    "name" => "required|string|unique:subjects,name",
                    "code" => "required|string|unique:subjects,code",
                ]
            );
            return response(["success" => true, "data" => Subject::create($request->all()), "errorMessage" => null], 201);
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

        $subject = Subject::find($id);

        if (!$subject) return response(["success" => false, "data" => null, "errorMessage" => "Subject not found."], 404);

        return response(["success" => true, "data" => $subject, "errorMessage" => null]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function classrooms($id)
    {
        $subject = Subject::find($id);

        if (!$subject) return response(["success" => false, "data" => null, "errorMessage" => "Subject not found."], 404);

        $classrooms = QueryBuilder::for($subject->classrooms())
            ->allowedFilters(['name', "code"])
            ->defaultSort('name')
            ->allowedSorts(['name', "code",])
            ->with(["teacher", "subject"])
            ->withCount('students')
            ->jsonPaginate();

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
                    "name" => "string|unique:subjects,name," . $id,
                    "code" => "string|unique:subjects,code," . $id,
                ]
            );

            $subject = Subject::find($id);

            if (!$subject) return response(["success" => false, "data" => null, "errorMessage" => "Subject not found."], 404);

            $subject->update($request->all());
            return response(["success" => true, "data" => $subject, "errorMessage" => null]);
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
        $subject =  Subject::find($id);
        if (!$subject) return response(["success" => false, "data" => null, "errorMessage" => "Subject not found."], 404);

        try {
            Subject::destroy($id);
        } catch (Exception $exception) {
            return response(["success" => false, "data" => null, "errorMessage" => "Deletion failed, the Subject is associated with a class/classes"], 400);
        }

        return response(["success" => true, "data" => 1, "errorMessage" => null]);
    }
}
