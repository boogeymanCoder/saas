<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;

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

        return Student::where([
            ["first_name", "like", "%" . $request->get("first_name") . "%"],
            ["middle_name", "like", "%" . $request->get("middle_name") . "%"],
            ["last_name", "like", "%" . $request->get("last_name") . "%"],
            ["address", "like", "%" . $request->get("address") . "%"],
            ["birthday", "like", "%" . $request->get("birthday") . "%"],
            ["gender", "like", "%" . $request->get("gender") . "%"],
            ["number", "like", "%" . $request->get("number") . "%"],
            ["email", "like", "%" . $request->get("email") . "%"],
        ])
            ->orderBy(
                $request->get("sort_column") ?? "first_name",
                $request->get("sort_order") ?? "asc"
            )
            ->paginate(
                $request->get("pageSize"),
                ["*"],
                "current",
                $request->get("current")
            );
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
                "first_name" => "required",
                "last_name" => "required",
                "address" => "required",
                "birthday" => "required",
                "gender" => "required|in:Male,Female",
                "number" => "required",
                "email" => "required",
            ]
        );

        return Student::create($request->all());
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
                "gender" => "required|in:Male,Female",
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
