<?php

namespace App\Http\Controllers;

use App\Models\Decision;
use App\Models\Infraction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class InfractionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
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
