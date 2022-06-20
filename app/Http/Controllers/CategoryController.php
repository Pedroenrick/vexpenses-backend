<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\DynamicRules;
use App\Repositories\CategoryRepository;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function __construct(Category $category)
    {
        $this->category = $category;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request): \Illuminate\Http\JsonResponse
    {
        $categoryRepository = new CategoryRepository($this->category);

        if ($request->has('params_contacts')) {
            $paramsContact = "contacts:id,{$request->params_contacts}";
            $categoryRepository->getRelatedAttributes($paramsContact);
        }

        if ($request->has('filters')) {
            $categoryRepository->filter($request->filters);
        }

        if ($request->has('params')) {
            $categoryRepository->selectAttributes($request->params);
        }

        return response()->json($categoryRepository->getResult(), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate($this->category->rules());

        try {
            $this->category->create($request->all());

            return response()->json([
                "message" => "Category created successfully"
            ], 201);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Int $id): \Illuminate\Http\JsonResponse
    {
        try {
            $category = $this->category->with('contacts')->findOrFail($id);

            return response()->json($category);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Category not found!'], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Int $id): \Illuminate\Http\JsonResponse
    {
        try {
            $category = $this->category->findOrFail($id);

            if ($request->method() == "PATCH") {
                $request->validate(DynamicRules::validateRules($this->category->rules(), $request->all()));
            } else {
                $request->validate($category->rules());
            }

            $category->update($request->all());

            return response()->json($category);
        } catch (\Exception $e) {
            return response()->json([
                "message" => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Int $id): \Illuminate\Http\JsonResponse
    {
        try {
            $category = $this->category->findOrFail($id);

            $category->delete();

            return response()->json(['success' => 'Category deleted!']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Category not found!'], 404);
        }
    }
}
