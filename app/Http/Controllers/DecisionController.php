<?php

namespace App\Http\Controllers;

use App\Models\Decision;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * @OA\Tag(
 *     name="Decisions",
 *     description="Decision management endpoints"
 * )
 */

class DecisionController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/decision",
     *     operationId="getDecisions",
     *     tags={"Decisions"},
     *     summary="Get all decisions",
     *     description="Retrieve a list of all decisions in the system",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="date", type="string", format="date", example="2023-12-15"),
     *                 @OA\Property(property="decisionprise", type="string", example="Fine of 150 DH"),
     *                 @OA\Property(property="infraction_id", type="integer", example=1),
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
        $decisions = Decision::all();
        return response()->json($decisions);
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
     *     path="/api/decision",
     *     operationId="createDecision",
     *     tags={"Decisions"},
     *     summary="Create a new decision",
     *     description="Create a new decision for an infraction",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"date", "decisionprise", "infraction_id"},
     *             @OA\Property(property="date", type="string", format="date", example="2023-12-15"),
     *             @OA\Property(property="decisionprise", type="string", example="Fine of 150 DH", minLength=5, maxLength=200),
     *             @OA\Property(property="infraction_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Decision created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Decision created successfully"),
     *             @OA\Property(property="data", type="object", properties={
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="date", type="string", format="date", example="2023-12-15"),
     *                 @OA\Property(property="decisionprise", type="string", example="Fine of 150 DH"),
     *                 @OA\Property(property="infraction_id", type="integer", example=1),
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
            'date' => 'required|date|before_or_equal:today',
            'decisionprise' => 'required|string|max:200|min:5',
            'infraction_id' => 'required|integer|exists:infraction,id',
        ], [
            'date.before_or_equal' => 'The decision date cannot be in the future.',
            'decisionprise.min' => 'The decision must be at least 5 characters.',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $decision = Decision::create([
            'date' => $request->date,
            'decisionprise' => trim($request->decisionprise),
            'infraction_id' => $request->infraction_id,
        ]);

        return response()->json([
            'message' => 'Decision created successfully',
            'data' => $decision
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
        $decision = Decision::find($id);

        if (!$decision) {
            return response()->json(['error' => 'Decision not found'], 404);
        }

        return response()->json($decision);
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
        $decision = Decision::find($id);

        if (!$decision) {
            return response()->json(['error' => 'Decision not found'], 404);
        }

        $decision->update($request->all());

        return response()->json([
            'message' => 'Decision updated successfully',
            'data' => $decision
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
        $decision = Decision::find($id);

        if (!$decision) {
            return response()->json(['error' => 'Decision not found'], 404);
        }

        $decision->delete();

        return response()->json(['message' => 'Decision deleted successfully']);
    }
}
