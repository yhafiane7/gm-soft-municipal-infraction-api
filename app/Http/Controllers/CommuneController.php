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
        //
        $AllCommune = Commune::all();
        return response()->json($AllCommune);
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
            'pachalik-circon'=>'required|max:200',
            'caidat'=>'required|max:200',
            'nom'=>'required|max:50',
            'latitude'=>'required',
            'longitude'=>'required',
            ]
            );
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 400);
            }
            Commune::create([
                'pachalik-circon'=>$request->input('pachalik-circon'),
                'caidat'=>$request->caidat,
                'nom'=>$request->nom,
                'latitude'=>floatval($request->latitude),
                'longitude'=>floatval($request->longitude),
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
        $Commune = Commune::find($id);

        if (!$Commune) {
            return response()->json(['Error' => 'Commune Introuvable']);
        }

        return response()->json($Commune);
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
        $Commune = Commune::find($id);

        if (!$Commune) {
            return response()->json(['Error' => 'Commune Introuvable']);
        }else{
            $Commune->update($request->all());
        }

        return response()->json($Commune);
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
        // si Commune introuvable retourner un message
        return response()->json(['Message' => 'Commune introuvable']);
    }

    if ($infraction) {
         // si Commune se trouve dans une infraction retourner un message
        $infractionId = $infraction->id;
        return response()->json(['Message' => "La Commune se trouve dans l'infraction: ".$infractionId]);
    } else {
        // si nous l'avons trouver on le supprime et retourner un message
        $commune->delete();
        return response()->json(['Message' => 'Commune supprimée']);
    }
}
}
