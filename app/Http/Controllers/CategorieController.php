<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use App\Models\Infraction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * @OA\Tag(
 *     name="Categories",
 *     description="Category management endpoints"
 * )
 */

class CategorieController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/categorie",
     *     operationId="getCategories",
     *     tags={"Categories"},
     *     summary="Get all categories",
     *     description="Retrieve a list of all categories in the system",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="nom", type="string", example="Traffic Violation"),
     *                 @OA\Property(property="degre", type="integer", example=3, minimum=1, maximum=5),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error"
     *     )
     * )
     */
    public function index()
    {
        $categories = Categorie::all();
        return response()->json($categories);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * @OA\Post(
     *     path="/api/categorie",
     *     operationId="createCategory",
     *     tags={"Categories"},
     *     summary="Create a new category",
     *     description="Create a new category with the provided information",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nom", "degre"},
     *             @OA\Property(property="nom", type="string", example="Traffic Violation", minLength=2, maxLength=50),
     *             @OA\Property(property="degre", type="integer", example=3, minimum=1, maximum=5)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Category created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Category created successfully"),
     *             @OA\Property(property="data", type="object", properties={
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="nom", type="string", example="Traffic Violation"),
     *                 @OA\Property(property="degre", type="integer", example=3),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time")
     *             })
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="errors", type="object"),
     *             @OA\Property(property="message", type="string", example="Validation failed")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Unprocessable entity"
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:50|min:2',
            'degre' => 'required|integer|between:1,5',
        ], [
            'nom.min' => 'The category name must be at least 2 characters.',
            'degre.between' => 'The degree must be between 1 and 5.',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $categorie = Categorie::create([
            'nom' => trim($request->nom),
            'degre' => intval($request->degre),
        ]);

        return response()->json([
            'message' => 'Category created successfully',
            'data' => $categorie
        ], 201);
    }

    /**
     * @OA\Get(
     *     path="/api/categorie/{id}",
     *     operationId="getCategory",
     *     tags={"Categories"},
     *     summary="Get category by ID",
     *     description="Retrieve a specific category by its ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Category ID",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="nom", type="string", example="Traffic Violation"),
     *             @OA\Property(property="degre", type="integer", example=3),
     *             @OA\Property(property="created_at", type="string", format="date-time"),
     *             @OA\Property(property="updated_at", type="string", format="date-time")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Category not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Category not found")
     *         )
     *     )
     * )
     */
    public function show($id)
    {
        $categorie = Categorie::find($id);

        if (!$categorie) {
            return response()->json(['error' => 'Category not found'], 404);
        }

        return response()->json($categorie);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * @OA\Put(
     *     path="/api/categorie/{id}",
     *     operationId="updateCategory",
     *     tags={"Categories"},
     *     summary="Update a category",
     *     description="Update an existing category with the provided information",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Category ID",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=false,
     *         @OA\JsonContent(
     *             @OA\Property(property="nom", type="string", example="Traffic Violation", minLength=2, maxLength=50),
     *             @OA\Property(property="degre", type="integer", example=3, minimum=1, maximum=5)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Category updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Category updated successfully"),
     *             @OA\Property(property="data", type="object", properties={
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="nom", type="string", example="Traffic Violation"),
     *                 @OA\Property(property="degre", type="integer", example=3),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time")
     *             })
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Category not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Category not found")
     *         )
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $categorie = Categorie::find($id);

        if (!$categorie) {
            return response()->json(['error' => 'Category not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'nom' => 'sometimes|string|max:50|min:2',
            'degre' => 'sometimes|integer|between:1,5',
        ], [
            'nom.min' => 'The category name must be at least 2 characters.',
            'degre.between' => 'The degree must be between 1 and 5.',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $updateData = $request->only(['nom', 'degre']);

        if (isset($updateData['nom'])) {
            $updateData['nom'] = trim($updateData['nom']);
        }

        if (isset($updateData['degre'])) {
            $updateData['degre'] = intval($updateData['degre']);
        }

        $categorie->update($updateData);

        return response()->json([
            'message' => 'Category updated successfully',
            'data' => $categorie
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/api/categorie/{id}",
     *     operationId="deleteCategory",
     *     tags={"Categories"},
     *     summary="Delete a category",
     *     description="Delete a category if it's not referenced in any infraction",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Category ID",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Category deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Category deleted successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Category not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Category not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=409,
     *         description="Conflict - Cannot delete category",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Cannot delete category"),
     *             @OA\Property(property="message", type="string", example="Category is referenced in infraction: 1")
     *         )
     *     )
     * )
     */
    public function destroy($id)
    {
        $infraction = Infraction::firstWhere("categorie_id", $id);
        $categorie = Categorie::find($id);

        if (!$categorie) {
            return response()->json(['error' => 'Category not found'], 404);
        }

        if ($infraction) {
            return response()->json([
                'error' => 'Cannot delete category',
                'message' => "Category is referenced in infraction: " . $infraction->id
            ], 409);
        }

        $categorie->delete();

        return response()->json(['message' => 'Category deleted successfully']);
    }
}
