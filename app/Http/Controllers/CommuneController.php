<?php

namespace App\Http\Controllers;

use App\Models\Commune;
use App\Models\Infraction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * @OA\Tag(
 *     name="Communes",
 *     description="Commune management endpoints"
 * )
 */

class CommuneController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/commune",
     *     operationId="getCommunes",
     *     tags={"Communes"},
     *     summary="Get all communes",
     *     description="Retrieve a list of all communes in the system",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="pachalik-circon", type="string", example="Pachalik Central"),
     *                 @OA\Property(property="caidat", type="string", example="Caidat North"),
     *                 @OA\Property(property="nom", type="string", example="Montreal"),
     *                 @OA\Property(property="latitude", type="number", format="float", example=45.5017),
     *                 @OA\Property(property="longitude", type="number", format="float", example=-73.5673),
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
        $communes = Commune::all();
        return response()->json($communes);
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
     *     path="/api/commune",
     *     operationId="createCommune",
     *     tags={"Communes"},
     *     summary="Create a new commune",
     *     description="Create a new commune with the provided information",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"pachalik-circon", "caidat", "nom", "latitude", "longitude"},
     *             @OA\Property(property="pachalik-circon", type="string", example="Pachalik Central", minLength=2, maxLength=200),
     *             @OA\Property(property="caidat", type="string", example="Caidat North", minLength=2, maxLength=200),
     *             @OA\Property(property="nom", type="string", example="Montreal", minLength=2, maxLength=50),
     *             @OA\Property(property="latitude", type="number", format="float", example=45.5017, minimum=-90, maximum=90),
     *             @OA\Property(property="longitude", type="number", format="float", example=-73.5673, minimum=-180, maximum=180)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Commune created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Commune created successfully"),
     *             @OA\Property(property="data", type="object", properties={
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="pachalik-circon", type="string", example="Pachalik Central"),
     *                 @OA\Property(property="caidat", type="string", example="Caidat North"),
     *                 @OA\Property(property="nom", type="string", example="Montreal"),
     *                 @OA\Property(property="latitude", type="number", format="float", example=45.5017),
     *                 @OA\Property(property="longitude", type="number", format="float", example=-73.5673),
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
            'pachalik-circon' => 'required|string|max:200|min:2',
            'caidat' => 'required|string|max:200|min:2',
            'nom' => 'required|string|max:50|min:2',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ], [
            'pachalik-circon.min' => 'The pachalik-circon must be at least 2 characters.',
            'caidat.min' => 'The caidat must be at least 2 characters.',
            'nom.min' => 'The commune name must be at least 2 characters.',
            'latitude.between' => 'Latitude must be between -90 and 90 degrees.',
            'longitude.between' => 'Longitude must be between -180 and 180 degrees.',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $commune = Commune::create([
            'pachalik-circon' => trim($request->input('pachalik-circon')),
            'caidat' => trim($request->caidat),
            'nom' => trim($request->nom),
            'latitude' => floatval($request->latitude),
            'longitude' => floatval($request->longitude),
        ]);

        return response()->json([
            'message' => 'Commune created successfully',
            'data' => $commune
        ], 201);
    }

    /**
     * @OA\Get(
     *     path="/api/commune/{id}",
     *     operationId="getCommune",
     *     tags={"Communes"},
     *     summary="Get commune by ID",
     *     description="Retrieve a specific commune by its ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Commune ID",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="pachalik-circon", type="string", example="Pachalik Central"),
     *             @OA\Property(property="caidat", type="string", example="Caidat North"),
     *             @OA\Property(property="nom", type="string", example="Montreal"),
     *             @OA\Property(property="latitude", type="number", format="float", example=45.5017),
     *             @OA\Property(property="longitude", type="number", format="float", example=-73.5673),
     *             @OA\Property(property="created_at", type="string", format="date-time"),
     *             @OA\Property(property="updated_at", type="string", format="date-time")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Commune not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Commune not found")
     *         )
     *     )
     * )
     */
    public function show($id)
    {
        $commune = Commune::find($id);

        if (!$commune) {
            return response()->json(['error' => 'Commune not found'], 404);
        }

        return response()->json($commune);
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
     *     path="/api/commune/{id}",
     *     operationId="updateCommune",
     *     tags={"Communes"},
     *     summary="Update a commune",
     *     description="Update an existing commune with the provided information",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Commune ID",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=false,
     *         @OA\JsonContent(
     *             @OA\Property(property="pachalik-circon", type="string", example="Pachalik Central", minLength=2, maxLength=200),
     *             @OA\Property(property="caidat", type="string", example="Caidat North", minLength=2, maxLength=200),
     *             @OA\Property(property="nom", type="string", example="Montreal", minLength=2, maxLength=50),
     *             @OA\Property(property="latitude", type="number", format="float", example=45.5017, minimum=-90, maximum=90),
     *             @OA\Property(property="longitude", type="number", format="float", example=-73.5673, minimum=-180, maximum=180)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Commune updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Commune updated successfully"),
     *             @OA\Property(property="data", type="object", properties={
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="pachalik-circon", type="string", example="Pachalik Central"),
     *                 @OA\Property(property="caidat", type="string", example="Caidat North"),
     *                 @OA\Property(property="nom", type="string", example="Montreal"),
     *                 @OA\Property(property="latitude", type="number", format="float", example=45.5017),
     *                 @OA\Property(property="longitude", type="number", format="float", example=-73.5673),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time")
     *             })
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Commune not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Commune not found")
     *         )
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $commune = Commune::find($id);

        if (!$commune) {
            return response()->json(['error' => 'Commune not found'], 404);
        }

        $commune->update($request->all());

        return response()->json([
            'message' => 'Commune updated successfully',
            'data' => $commune
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/api/commune/{id}",
     *     operationId="deleteCommune",
     *     tags={"Communes"},
     *     summary="Delete a commune",
     *     description="Delete a commune if it's not referenced in any infraction",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Commune ID",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Commune deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Commune deleted successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Commune not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Commune not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=409,
     *         description="Conflict - Cannot delete commune",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Cannot delete commune"),
     *             @OA\Property(property="message", type="string", example="Commune is referenced in infraction: 1")
     *         )
     *     )
     * )
     */
    public function destroy($id)
    {
        $infraction = Infraction::firstWhere("commune_id", $id);
        $commune = Commune::find($id);

        if (!$commune) {
            return response()->json(['error' => 'Commune not found'], 404);
        }

        if ($infraction) {
            return response()->json([
                'error' => 'Cannot delete commune',
                'message' => "Commune is referenced in infraction: " . $infraction->id
            ], 409);
        }

        $commune->delete();

        return response()->json(['message' => 'Commune deleted successfully']);
    }
}
