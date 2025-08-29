<?php

namespace App\Http\Controllers;

use App\Models\Decision;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DecisionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $AllDecision = Decision::all();
        return $AllDecision;
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
            'date' => 'required|date',
            'decisionprise' => 'required|max:200',
            'infraction_id' => 'required|exists:infraction,id',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }
    
        Decision::create([
            'date' => $request->date,
            'decisionprise' => $request->decisionprise,
            'infraction_id' => $request->infraction_id,
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
        $Decision = Decision::find($id);

        if (!$Decision) {
            return response()->json(['Error' => 'Décision Introuvable']);
        }

        return response()->json($Decision);
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
        $Decision = Decision::find($id);

        if (!$Decision) {
            return response()->json(['Error' => 'Agent Introuvable']);
        }else{
            $time=strtotime($request->date);
            $time=date('Y-m-d',$time);
            $Decision->update($request->all());
        }

        return response()->json($Decision);
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
        //rechercher l 'Decision
        $Decision = Decision::find($id);
        if (!$Decision) {
            // si Decision introuvable retourner un message
            return response()->json(['Message' => 'Décision Introuvable']);
        }
        // si nous l'avons trouver on le supprime et retourner un message
        $Decision->delete();
        return response()->json(['Message' => 'Décision Supprimé']);
    }
}
