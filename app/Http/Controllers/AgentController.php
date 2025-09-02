<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Agent;
use App\Models\Infraction;
use Illuminate\Support\Facades\Validator;

/**
 * @OA\Tag(
 *     name="Agents",
 *     description="Agent management endpoints"
 * )
 */

class AgentController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/agent",
     *     operationId="getAgents",
     *     tags={"Agents"},
     *     summary="Get all agents",
     *     description="Retrieve a list of all agents in the system",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="nom", type="string", example="Smith"),
     *                 @OA\Property(property="prenom", type="string", example="John"),
     *                 @OA\Property(property="tel", type="string", example="1234567890"),
     *                 @OA\Property(property="cin", type="string", example="AB123456"),
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
     * @OA\Post(
     *     path="/api/agent",
     *     operationId="createAgent",
     *     tags={"Agents"},
     *     summary="Create a new agent",
     *     description="Create a new agent with the provided information",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nom", "prenom", "tel", "cin"},
     *             @OA\Property(property="nom", type="string", example="Smith", minLength=2, maxLength=50),
     *             @OA\Property(property="prenom", type="string", example="John", minLength=2, maxLength=50),
     *             @OA\Property(property="tel", type="string", example="1234567890", minLength=10, maxLength=10, pattern="^[0-9]+$"),
     *             @OA\Property(property="cin", type="string", example="AB123456", maxLength=12, pattern="^[A-Z0-9]+$")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Agent created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Agent created successfully"),
     *             @OA\Property(property="data", type="object", properties={
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="nom", type="string", example="Smith"),
     *                 @OA\Property(property="prenom", type="string", example="John"),
     *                 @OA\Property(property="tel", type="string", example="1234567890"),
     *                 @OA\Property(property="cin", type="string", example="AB123456"),
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
