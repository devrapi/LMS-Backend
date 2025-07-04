<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Categories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use function Laravel\Prompts\error;

class CategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $category = Categories::all();
        return response($category,200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = Validator::make($request->all(),
        [
            'name' => 'required|string|max:255',

        ]);

        if ($validate->fails()) {
            return response()->json(['error' => $validate->errors()], 422);
        }

        try {
            $category = Categories::create($request->only(['name']));
            return response()->json([
                'message' => 'categories created successfully',
                'categories' => $category,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'An error occurred while creating/updating the categories',
                'name' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $category = Categories::find($id);

        if (!$category) {
            return response()->json(['error' => 'categories not found'], 404);
        }

        return response()->json($category , 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validate = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
        ]);

        if ($validate->fails()) {
            return response()->json(['error' => $validate->errors()], 422);
        }

        try {
            $categories = Categories::find($id);

            if (!$categories) {
                return response()->json(['error' => 'Categories not found'], 404);
            }

            $categories->update($request->only(['name']));

            return response()->json([
                'message' => 'Categories updated successfully',
                'division' => $categories,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'An error occurred while creating/updating the division',
                'name' => $request->name,
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $division = Categories::find($id);

        if (!$division) {
            return response()->json(['error' => 'Categories not found'], 404);
        }

        try {
            $division->delete();
            return response()->json(['message' => 'Categories deleted successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while deleting the division'], 500);
        }
    }
}
