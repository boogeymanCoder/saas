<?php

namespace App\Http\Controllers;

use App\Models\GradingSystemCategory;
use Exception;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class GradingSystemCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return QueryBuilder::for(GradingSystemCategory::class)
            ->allowedFilters(['name'])
            ->defaultSort('name')
            ->allowedSorts(["name", "percentage"])
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
                    "name" => "required|string",
                    "percentage" => "required|numeric",
                    "grading_system_id" => "required|exists:grading_system,id",
                ]
            );
            return response(["success" => true, "data" => GradingSystemCategory::create($request->all()), "errorMessage" => null], 201);
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
        $grading_system_category = GradingSystemCategory::with("grading_system")->find($id);

        if (!$grading_system_category) return response(["success" => false, "data" => null, "errorMessage" => "Grading System Category not found."], 404);

        return response(["success" => true, "data" => $grading_system_category, "errorMessage" => null]);
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
                    "name" => "string|unique:grading_system_categories,name," . $id,
                    "percentage" => "string",
                ]
            );

            $grading_system_category = GradingSystemCategory::find($id);

            if (!$grading_system_category) return response(["success" => false, "data" => null, "errorMessage" => "Grading System Category not found."], 404);


            $grading_system_category->update($request->all());
            return response(["success" => true, "data" => $grading_system_category, "errorMessage" => null]);
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
        $grading_system_category =  GradingSystemCategory::destroy($id);
        if (!$grading_system_category) return response(["success" => false, "data" => null, "errorMessage" => "Grading System Category not found."], 404);

        return response(["success" => true, "data" => 1, "errorMessage" => null]);
    }
}
