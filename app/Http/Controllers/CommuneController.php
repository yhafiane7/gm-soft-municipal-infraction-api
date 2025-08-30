<?php

namespace App\Http\Controllers;

use App\Models\Commune;
use App\Models\Infraction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CommuneController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
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
