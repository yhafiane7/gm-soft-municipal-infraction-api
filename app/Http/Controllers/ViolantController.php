<?php

namespace App\Http\Controllers;

use App\Models\Infraction;
use App\Models\Violant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * @OA\Tag(
 *     name="Violants",
 *     description="Violant management endpoints"
 * )
 */

class ViolantController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $violants = Violant::all();
        return response()->json($violants);
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
            'prenom' => 'required|string|max:50|min:2',
            'cin' => 'required|string|max:12|unique:violant,cin|regex:/^[A-Z0-9]+$/'
        ], [
            'nom.min' => 'The last name must be at least 2 characters.',
            'prenom.min' => 'The first name must be at least 2 characters.',
            'cin.regex' => 'The CIN must contain only uppercase letters and numbers.',
            'cin.max' => 'The CIN must be at most 12 characters.',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $violant = Violant::create([
            'nom' => trim($request->nom),
            'prenom' => trim($request->prenom),
            'cin' => strtoupper(trim($request->cin)),
        ]);

        return response()->json([
            'message' => 'Violant created successfully',
            'data' => $violant
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
        $violant = Violant::find($id);

        if (!$violant) {
            return response()->json(['error' => 'Violant not found'], 404);
        }

        return response()->json($violant);
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
        $violant = Violant::find($id);

        if (!$violant) {
            return response()->json(['error' => 'Violant not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'nom' => 'sometimes|string|max:50|min:2',
            'prenom' => 'sometimes|string|max:50|min:2',
            'cin' => 'sometimes|string|max:12|unique:violant,cin,' . $id . '|regex:/^[A-Z0-9]+$/'
        ], [
            'nom.min' => 'The last name must be at least 2 characters.',
            'prenom.min' => 'The first name must be at least 2 characters.',
            'cin.regex' => 'The CIN must contain only uppercase letters and numbers.',
            'cin.max' => 'The CIN must be at most 12 characters.',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $updateData = $request->only(['nom', 'prenom', 'cin']);

        if (isset($updateData['cin'])) {
            $updateData['cin'] = strtoupper(trim($updateData['cin']));
        }

        $violant->update($updateData);

        return response()->json([
            'message' => 'Violant updated successfully',
            'data' => $violant
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
        $infraction = Infraction::firstWhere("violant_id", $id);
        $violant = Violant::find($id);

        if (!$violant) {
            return response()->json(['error' => 'Violant not found'], 404);
        }

        if ($infraction) {
            return response()->json([
                'error' => 'Cannot delete violant',
                'message' => "Violant is referenced in infraction: " . $infraction->id
            ], 409);
        }

        $violant->delete();

        return response()->json(['message' => 'Violant deleted successfully']);
    }
}
