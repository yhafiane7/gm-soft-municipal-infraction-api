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
        $agents = Agent::all();
        return response()->json($agents);
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
            'nom' => 'required|string|max:50|min:2',
            'prenom' => 'required|string|max:50|min:2',
            'tel' => 'required|string|max:10|min:10|unique:agent,tel|regex:/^[0-9]+$/',
            'cin' => 'required|string|max:12|unique:agent,cin|regex:/^[A-Z0-9]+$/'
        ], [
            'nom.min' => 'The last name must be at least 2 characters.',
            'prenom.min' => 'The first name must be at least 2 characters.',
            'tel.regex' => 'The phone number must contain only digits.',
            'tel.min' => 'The phone number must be exactly 10 digits.',
            'cin.regex' => 'The CIN must contain only uppercase letters and numbers.',
            'cin.max' => 'The CIN must be at most 12 characters.',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $agent = Agent::create([
            'nom' => trim($request->nom),
            'prenom' => trim($request->prenom),
            'tel' => trim($request->tel),
            'cin' => strtoupper(trim($request->cin)),
        ]);

        return response()->json([
            'message' => 'Agent created successfully',
            'data' => $agent
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
        $agent = Agent::find($id);

        if (!$agent) {
            return response()->json(['error' => 'Agent not found'], 404);
        }

        return response()->json($agent);
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
        $agent = Agent::find($id);

        if (!$agent) {
            return response()->json(['error' => 'Agent not found'], 404);
        }

        $agent->update($request->all());

        return response()->json([
            'message' => 'Agent updated successfully',
            'data' => $agent
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
        $infraction = Infraction::firstWhere("agent_id", $id);
        $agent = Agent::find($id);

        if (!$agent) {
            return response()->json(['error' => 'Agent not found'], 404);
        }

        if ($infraction) {
            return response()->json([
                'error' => 'Cannot delete agent',
                'message' => "Agent is referenced in infraction: " . $infraction->id
            ], 409);
        }

        $agent->delete();

        return response()->json(['message' => 'Agent deleted successfully']);
    }
}
