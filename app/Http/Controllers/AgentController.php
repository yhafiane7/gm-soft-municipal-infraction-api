<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Agent;
use App\Models\Infraction;
use Illuminate\Support\Facades\Validator;

class AgentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $AllAgent = Agent::all();

        return response()->json($AllAgent) ;
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
        $validator = Validator::make($request->all(),[
            'nom'=>'required|max:50',
            'prenom'=>'required|max:50',
            'tel'=>'required|max:10|unique:agent,tel',
            'cin'=>'required|max:12|unique:agent,cin'
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }
        Agent::create([
            'nom'=>$request->nom,
            'prenom'=>$request->prenom,
            'tel'=>$request->tel,
            'cin'=>$request->cin,
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
        $Agent = Agent::find($id);

        if (!$Agent) {
            return response()->json(['Error' => 'Agent Introuvable']);
        }

        return response()->json($Agent);
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
        $Agent = Agent::find($id);

        if (!$Agent) {
            return response()->json(['Error' => 'Agent Introuvable']);
        }else{
            $Agent->update($request->all());
        }

        return response()->json($Agent);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        
        $infraction = Infraction::firstWhere("agent_id", $id);
        $agent = Agent::find($id);
    
        if (!$agent) {
            // si Agent introuvable retourner un message
            return response()->json(['Message' => 'Agent introuvable']);
        }
    
        if ($infraction) {
             // si Agent se trouve dans une infraction retourner un message
            $infractionId = $infraction->id;
            return response()->json(['Message' => "L'Agent se trouve dans l'infraction: ".$infractionId]);
        } else {
            // si nous l'avons trouver on le supprime et retourner un message
            $agent->delete();
            return response()->json(['Message' => 'Agent supprimée']);
        }
    }
}
