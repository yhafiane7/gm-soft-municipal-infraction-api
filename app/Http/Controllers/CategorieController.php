<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use App\Models\Infraction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategorieController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $categorie = Categorie::find($id);

        if (!$categorie) {
            return response()->json(['error' => 'Category not found'], 404);
        }

        $categorie->update($request->all());

        return response()->json([
            'message' => 'Category updated successfully',
            'data' => $categorie
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
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
