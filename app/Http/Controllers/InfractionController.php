<?php

namespace App\Http\Controllers;

use App\Models\Decision;
use App\Models\Infraction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * @OA\Tag(
 *     name="Infractions",
 *     description="Infraction management endpoints"
 * )
 */

class InfractionController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/infraction",
     *     operationId="getInfractions",
     *     tags={"Infractions"},
     *     summary="Get all infractions",
     *     description="Retrieve a list of all infractions in the system",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="nom", type="string", example="Speeding"),
     *                 @OA\Property(property="date", type="string", format="date", example="2023-12-01"),
     *                 @OA\Property(property="adresse", type="string", example="123 Main Street"),
     *                 @OA\Property(property="commune_id", type="integer", example=1),
     *                 @OA\Property(property="violant_id", type="integer", example=1),
     *                 @OA\Property(property="agent_id", type="integer", example=1),
     *                 @OA\Property(property="categorie_id", type="integer", example=1),
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
        $infractions = Infraction::all();
        return response()->json($infractions);
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
     *     path="/api/infraction",
     *     operationId="createInfraction",
     *     tags={"Infractions"},
     *     summary="Create a new infraction",
     *     description="Create a new infraction with the provided information",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nom", "date", "adresse", "commune_id", "violant_id", "agent_id", "categorie_id", "latitude", "longitude"},
     *             @OA\Property(property="nom", type="string", example="Speeding", minLength=2, maxLength=100),
     *             @OA\Property(property="date", type="string", format="date", example="2023-12-01"),
     *             @OA\Property(property="adresse", type="string", example="123 Main Street", minLength=5, maxLength=255),
     *             @OA\Property(property="commune_id", type="integer", example=1),
     *             @OA\Property(property="violant_id", type="integer", example=1),
     *             @OA\Property(property="agent_id", type="integer", example=1),
     *             @OA\Property(property="categorie_id", type="integer", example=1),
     *             @OA\Property(property="latitude", type="number", format="float", example=45.5017, minimum=-90, maximum=90),
     *             @OA\Property(property="longitude", type="number", format="float", example=-73.5673, minimum=-180, maximum=180)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Infraction created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Infraction created successfully"),
     *             @OA\Property(property="data", type="object", properties={
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="nom", type="string", example="Speeding"),
     *                 @OA\Property(property="date", type="string", format="date", example="2023-12-01"),
     *                 @OA\Property(property="adresse", type="string", example="123 Main Street"),
     *                 @OA\Property(property="commune_id", type="integer", example=1),
     *                 @OA\Property(property="violant_id", type="integer", example=1),
     *                 @OA\Property(property="agent_id", type="integer", example=1),
     *                 @OA\Property(property="categorie_id", type="integer", example=1),
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
        $validator = Validator::make(
            $request->all(),
            [
                'nom' => 'required|string|max:100|min:2',
                'date' => 'required|date|before_or_equal:today',
                'adresse' => 'required|string|max:255|min:5',
                'commune_id' => 'required|integer|exists:commune,id',
                'violant_id' => 'required|integer|exists:violant,id',
                'agent_id' => 'required|integer|exists:agent,id',
                'categorie_id' => 'required|integer|exists:categorie,id',
                'latitude' => 'required|numeric|between:-90,90',
                'longitude' => 'required|numeric|between:-180,180',
            ],
            [
                'nom.required' => 'The infraction name is required.',
                'nom.min' => 'The infraction name must be at least 2 characters.',
                'nom.max' => 'The infraction name cannot exceed 100 characters.',
                'date.before_or_equal' => 'The infraction date cannot be in the future.',
                'adresse.min' => 'The address must be at least 5 characters.',
                'latitude.between' => 'Latitude must be between -90 and 90 degrees.',
                'longitude.between' => 'Longitude must be between -180 and 180 degrees.',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $infraction = Infraction::create([
            'nom' => trim($request->nom),
            'date' => $request->date,
            'adresse' => trim($request->adresse),
            'commune_id' => $request->commune_id,
            'violant_id' => $request->violant_id,
            'agent_id' => $request->agent_id,
            'categorie_id' => $request->categorie_id,
            'latitude' => floatval($request->latitude),
            'longitude' => floatval($request->longitude),
        ]);

        return response()->json([
            'message' => 'Infraction created successfully',
            'data' => $infraction
        ], 201);
    }

    /**
     * @OA\Get(
     *     path="/api/infraction/{id}",
     *     operationId="getInfraction",
     *     tags={"Infractions"},
     *     summary="Get infraction by ID",
     *     description="Retrieve a specific infraction by its ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Infraction ID",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="nom", type="string", example="Speeding"),
     *             @OA\Property(property="date", type="string", format="date", example="2023-12-01"),
     *             @OA\Property(property="adresse", type="string", example="123 Main Street"),
     *             @OA\Property(property="commune_id", type="integer", example=1),
     *             @OA\Property(property="violant_id", type="integer", example=1),
     *             @OA\Property(property="agent_id", type="integer", example=1),
     *             @OA\Property(property="categorie_id", type="integer", example=1),
     *             @OA\Property(property="latitude", type="number", format="float", example=45.5017),
     *             @OA\Property(property="longitude", type="number", format="float", example=-73.5673),
     *             @OA\Property(property="created_at", type="string", format="date-time"),
     *             @OA\Property(property="updated_at", type="string", format="date-time")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Infraction not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Infraction not found")
     *         )
     *     )
     * )
     */
    public function show($id)
    {
        $infraction = Infraction::find($id);

        if (!$infraction) {
            return response()->json(['error' => 'Infraction not found'], 404);
        }

        return response()->json($infraction);
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
     *     path="/api/infraction/{id}",
     *     operationId="updateInfraction",
     *     tags={"Infractions"},
     *     summary="Update an infraction",
     *     description="Update an existing infraction with the provided information",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Infraction ID",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=false,
     *         @OA\JsonContent(
     *             @OA\Property(property="nom", type="string", example="Speeding", minLength=2, maxLength=100),
     *             @OA\Property(property="date", type="string", format="date", example="2023-12-01"),
     *             @OA\Property(property="adresse", type="string", example="123 Main Street", minLength=5, maxLength=255),
     *             @OA\Property(property="commune_id", type="integer", example=1),
     *             @OA\Property(property="violant_id", type="integer", example=1),
     *             @OA\Property(property="agent_id", type="integer", example=1),
     *             @OA\Property(property="categorie_id", type="integer", example=1),
     *             @OA\Property(property="latitude", type="number", format="float", example=45.5017, minimum=-90, maximum=90),
     *             @OA\Property(property="longitude", type="number", format="float", example=-73.5673, minimum=-180, maximum=180)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Infraction updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Infraction updated successfully"),
     *             @OA\Property(property="data", type="object", properties={
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="nom", type="string", example="Speeding"),
     *                 @OA\Property(property="date", type="string", format="date", example="2023-12-01"),
     *                 @OA\Property(property="adresse", type="string", example="123 Main Street"),
     *                 @OA\Property(property="commune_id", type="integer", example=1),
     *                 @OA\Property(property="violant_id", type="integer", example=1),
     *                 @OA\Property(property="agent_id", type="integer", example=1),
     *                 @OA\Property(property="categorie_id", type="integer", example=1),
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
     *         response=404,
     *         description="Infraction not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Infraction not found")
     *         )
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $infraction = Infraction::find($id);

        if (!$infraction) {
            return response()->json(['error' => 'Infraction not found'], 404);
        }

        $validator = Validator::make(
            $request->all(),
            [
                'nom' => 'sometimes|required|string|max:100|min:2',
                'date' => 'sometimes|required|date|before_or_equal:today',
                'adresse' => 'sometimes|required|string|max:255|min:5',
                'commune_id' => 'sometimes|required|integer|exists:commune,id',
                'violant_id' => 'sometimes|required|integer|exists:violant,id',
                'agent_id' => 'sometimes|required|integer|exists:agent,id',
                'categorie_id' => 'sometimes|required|integer|exists:categorie,id',
                'latitude' => 'sometimes|required|numeric|between:-90,90',
                'longitude' => 'sometimes|required|numeric|between:-180,180',
            ],
            [
                'nom.min' => 'The infraction name must be at least 2 characters.',
                'nom.max' => 'The infraction name cannot exceed 100 characters.',
                'date.before_or_equal' => 'The infraction date cannot be in the future.',
                'adresse.min' => 'The address must be at least 5 characters.',
                'latitude.between' => 'Latitude must be between -90 and 90 degrees.',
                'longitude.between' => 'Longitude must be between -180 and 180 degrees.',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $updateData = $request->only([
            'nom',
            'date',
            'adresse',
            'commune_id',
            'violant_id',
            'agent_id',
            'categorie_id',
            'latitude',
            'longitude'
        ]);

        // Sanitize string fields
        if (isset($updateData['nom'])) {
            $updateData['nom'] = trim($updateData['nom']);
        }
        if (isset($updateData['adresse'])) {
            $updateData['adresse'] = trim($updateData['adresse']);
        }

        // Convert coordinates to float
        if (isset($updateData['latitude'])) {
            $updateData['latitude'] = floatval($updateData['latitude']);
        }
        if (isset($updateData['longitude'])) {
            $updateData['longitude'] = floatval($updateData['longitude']);
        }

        $infraction->update($updateData);

        return response()->json([
            'message' => 'Infraction updated successfully',
            'data' => $infraction
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/api/infraction/{id}",
     *     operationId="deleteInfraction",
     *     tags={"Infractions"},
     *     summary="Delete an infraction",
     *     description="Delete an infraction if it's not referenced in any decision",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Infraction ID",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Infraction deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Infraction deleted successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Infraction not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Infraction not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=409,
     *         description="Conflict - Cannot delete infraction",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Cannot delete infraction"),
     *             @OA\Property(property="message", type="string", example="Infraction is referenced in decision: 1")
     *         )
     *     )
     * )
     */
    public function destroy($id)
    {
        $decision = Decision::firstWhere("infraction_id", $id);
        $infraction = Infraction::find($id);

        if (!$infraction) {
            return response()->json(['error' => 'Infraction not found'], 404);
        }

        if ($decision) {
            return response()->json([
                'error' => 'Cannot delete infraction',
                'message' => "Infraction is referenced in decision: " . $decision->id
            ], 409);
        }

        $infraction->delete();

        return response()->json(['message' => 'Infraction deleted successfully']);
    }
}
