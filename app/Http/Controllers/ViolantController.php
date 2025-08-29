<?php

namespace App\Http\Controllers;

use App\Models\Infraction;
use App\Models\Violant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ViolantController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $AllViolant = Violant::all();
        return response()->json($AllViolant);
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
        $validator = Validator::make($request->all(),[
            'nom' => 'required|max:50',
            'prenom' => 'required|max:50',
            'cin' => 'required|max:12|unique:violant,cin'
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }
        Violant::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'cin' => $request->cin,
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
        $Violant = Violant::find($id);
        if (!$Violant) {
            return response()->json(['Error' => 'Violant Introuvable'], 404);
        }
        return response()->json($Violant);
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
        $Violant = Violant::find($id);

        if (!$Violant) {
            return response()->json(['Error' => 'Violant Introuvable']);
        } else {
            $Violant->update($request->all());
        }

        return response()->json($Violant);
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
            // si Violant introuvable retourner un message
            return response()->json(['Message' => 'Violant introuvable']);
        }

        if ($infraction) {
            // si Violant se trouve dans une infraction retourner un message
            $infractionId = $infraction->id;
            return response()->json(['Message' => "Le violant se trouve dans l'infraction: ".$infractionId]);
        } else {
            // si nous l'avons trouver on le supprime et retourner un message
            $violant->delete();
            return response()->json(['Message' => 'Violant supprimée']);
        }
    }
}
