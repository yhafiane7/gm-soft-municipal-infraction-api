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
        //
        $AllInfraction = Infraction::all();
        return $AllInfraction;
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
        //
        $validator = Validator::make(
            $request->all(),
            [
                'nom' => 'required|max:50',
                'date' => 'required|date',
                'adresse' => 'required|max:255',
                'commune_id' => 'required|integer|exists:commune,id',
                'violant_id' => 'required|integer|exists:violant,id',
                'agent_id' => 'required|integer|exists:agent,id',
                'categorie_id' => 'required|integer|exists:categorie,id',
                'latitude' => 'required',
                'longitude' => 'required',
            ]
        );
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }
        Infraction::create([
            'nom' => $request->nom,
            'date' => $request->date,
            'adresse' => $request->adresse,
            'commune_id' => $request->commune_id,
            'violant_id' => $request->violant_id,
            'agent_id' => $request->agent_id,
            'categorie_id' => $request->categorie_id,
            'latitude' => floatval($request->latitude),
            'longitude' => floatval($request->longitude),
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $Infraction = Infraction::find($id);

        if (!$Infraction) {
            return response()->json(['Error' => 'Infraction Introuvable']);
        }

        return response()->json($Infraction);
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
        //
        $Infraction = Infraction::find($id);

        if (!$Infraction) {
            return response()->json(['Error' => 'Infraction Introuvable']);
        }else{
            $Infraction->update($request->all());
        }

        return response()->json($Infraction);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $decision= Decision::firstWhere("infraction_id",$id);
        //rechercher l 'Infraction
        $Infraction = Infraction::find($id);
        if (!$Infraction) {
            // si Infraction introuvable retourner un erreur
            return response()->json(['Message' => 'Infraction Introuvable']);
        }
        if ($decision) {
            // si Infraction se trouve dans une infraction retourner un message
           $decisionId = $decision->id;
           return response()->json(['Message' => "La Infraction se trouve dans la décision: ".$decisionId]);
       } else {
        // si nous l'avons trouver on le supprime et retourner un message
        $Infraction->delete();
        return response()->json(['Message' => 'Infraction Supprimé']);
    }
}
}
