<?php

namespace App\Http\Controllers;

use App\Models\GradingSystem;
use Exception;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class GradingSystemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return QueryBuilder::for(GradingSystem::class)
            ->allowedFilters(['name',])
            ->defaultSort('name')
            ->allowedSorts(['name',])
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
                    "name" => "required|string|unique:grading_systems,name",
                ]
            );
            return response(["success" => true, "data" => GradingSystem::create($request->all()), "errorMessage" => null], 201);
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
        $grading_system = GradingSystem::find($id);

        if (!$grading_system) return response(["success" => false, "data" => null, "errorMessage" => "Grading System not found."], 404);

        return response(["success" => true, "data" => $grading_system, "errorMessage" => null]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function gradingSystemCategories($id)
    {
        $grading_system = GradingSystem::find($id);
        if (!$grading_system) return response(["success" => false, "data" => null, "errorMessage" => "Grading System not found."], 404);


        $grading_system_categories = QueryBuilder::for($grading_system->grading_system_categories())
            ->allowedFilters(['name'])
            ->defaultSort('name')
            ->allowedSorts(["name", "percentage"])
            ->jsonPaginate();

        return response($grading_system_categories);
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
                    "name" => "string|unique:grading_systems,name," . $id,
                ]
            );

            $grading_system = GradingSystem::find($id);

            if (!$grading_system) return response(["success" => false, "data" => null, "errorMessage" => "Grading System not found."], 404);

            $grading_system->update($request->all());
            return response(["success" => true, "data" => $grading_system, "errorMessage" => null]);
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
        $grading_system =  GradingSystem::destroy($id);
        if (!$grading_system) return response(["success" => false, "data" => null, "errorMessage" => "Grading System not found."], 404);

        return response(["success" => true, "data" => 1, "errorMessage" => null]);
    }
}
