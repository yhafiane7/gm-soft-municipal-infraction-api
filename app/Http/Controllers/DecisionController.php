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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
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
