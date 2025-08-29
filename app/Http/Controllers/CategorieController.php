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
        $AllCategorie = Categorie::all();

        return response()->json($AllCategorie);
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
            'nom'=>'required|max:50',
            'degre'=>'required|integer',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }
        Categorie::create([
            'nom'=>$request->nom,
            'degre'=>$request->degre,
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
        $Categorie = Categorie::find($id);

        if (!$Categorie) {
            return response()->json(['Error' => 'Categorie Introuvable']);
        }

        return response()->json($Categorie);
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
        $Categorie = Categorie::find($id);

        if (!$Categorie) {
            return response()->json(['Error' => 'Categorie Introuvable']);
        }else{
            $Categorie->update($request->all());
        }

        return response()->json($Categorie);
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
            // si Categorie introuvable retourner un message
            return response()->json(['Message' => 'Catégorie introuvable']);
        }
    
        if ($infraction) {
             // si Categorie se trouve dans une infraction retourner un message
            $infractionId = $infraction->id;
            return response()->json(['Message' => "La Catégorie se trouve dans l'infraction: ".$infractionId]);
        } else {
            // si nous l'avons trouver on le supprime et retourner un message
            $categorie->delete();
            return response()->json(['Message' => 'Catégorie supprimée']);
        }
    }
}
