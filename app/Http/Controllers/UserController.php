<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


/**
 * @OA\Tag(
 *     name="Users",
 *     description="User management endpoints"
 * )
 */

class UserController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/user",
     *     operationId="getUsers",
     *     tags={"Users"},
     *     summary="Get all users",
     *     description="Retrieve a list of all users in the system",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
                 @OA\Property(property="id", type="integer", example=1),
                 @OA\Property(property="nom", type="string", example="Doe"),
                 @OA\Property(property="prenom", type="string", example="John"),
                 @OA\Property(property="Tel", type="string", example="+1234567890"),
                 @OA\Property(property="role", type="string", example="user"),
                 @OA\Property(property="login", type="string", example="johndoe"),
                 @OA\Property(property="created_at", type="string", format="date-time"),
                 @OA\Property(property="updated_at", type="string", format="date-time")
             )
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
        $users = User::all();
        return response()->json($users);
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
     *     path="/api/user",
     *     operationId="createUser",
     *     tags={"Users"},
     *     summary="Create a new user",
     *     description="Create a new user with the provided information",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
             required={"nom", "prenom", "Tel", "role", "login"},
             @OA\Property(property="nom", type="string", example="Doe", maxLength=255),
             @OA\Property(property="prenom", type="string", example="John", maxLength=255),
             @OA\Property(property="Tel", type="string", example="+1234567890", maxLength=50),
             @OA\Property(property="role", type="string", example="user", maxLength=255),
             @OA\Property(property="login", type="string", example="johndoe", maxLength=50)
         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="User created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="User created successfully"),
     *             @OA\Property(property="data", type="object", properties={
                 @OA\Property(property="id", type="integer", example=1),
                 @OA\Property(property="nom", type="string", example="Doe"),
                 @OA\Property(property="prenom", type="string", example="John"),
                 @OA\Property(property="Tel", type="string", example="+1234567890"),
                 @OA\Property(property="role", type="string", example="user"),
                 @OA\Property(property="login", type="string", example="johndoe"),
                 @OA\Property(property="created_at", type="string", format="date-time"),
                 @OA\Property(property="updated_at", type="string", format="date-time")
             })
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
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'Tel' => 'required|string|max:50',
            'role' => 'required|string|max:255',
            'login' => 'required|string|max:50|unique:users',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $user = User::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'Tel' => $request->Tel,
            'role' => $request->role,
            'login' => $request->login,
        ]);

        return response()->json([
            'message' => 'User created successfully',
            'data' => $user
        ], 201);
    }

    /**
     * @OA\Get(
     *     path="/api/user/{id}",
     *     operationId="getUser",
     *     tags={"Users"},
     *     summary="Get user by ID",
     *     description="Retrieve a specific user by their ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="User ID",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
             @OA\Property(property="id", type="integer", example=1),
             @OA\Property(property="nom", type="string", example="Doe"),
             @OA\Property(property="prenom", type="string", example="John"),
             @OA\Property(property="Tel", type="string", example="+1234567890"),
             @OA\Property(property="role", type="string", example="user"),
             @OA\Property(property="login", type="string", example="johndoe"),
             @OA\Property(property="created_at", type="string", format="date-time"),
             @OA\Property(property="updated_at", type="string", format="date-time")
         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="User not found")
     *         )
     *     )
     * )
     */
    public function show($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        return response()->json($user);
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
     * @OA\Put(
     *     path="/api/user/{id}",
     *     operationId="updateUser",
     *     tags={"Users"},
     *     summary="Update a user",
     *     description="Update an existing user with the provided information",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="User ID",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=false,
     *         @OA\JsonContent(
     *             @OA\Property(property="nom", type="string", example="Doe", maxLength=255),
     *             @OA\Property(property="prenom", type="string", example="John", maxLength=255),
     *             @OA\Property(property="Tel", type="string", example="+1234567890", maxLength=50),
     *             @OA\Property(property="role", type="string", example="user", maxLength=255),
     *             @OA\Property(property="login", type="string", example="johndoe", maxLength=50)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="User updated successfully"),
     *             @OA\Property(property="data", type="object", properties={
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="nom", type="string", example="Doe"),
     *                 @OA\Property(property="prenom", type="string", example="John"),
     *                 @OA\Property(property="Tel", type="string", example="+1234567890"),
     *                 @OA\Property(property="role", type="string", example="user"),
     *                 @OA\Property(property="login", type="string", example="johndoe"),
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
     *         response=404,
     *         description="User not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="User not found")
     *         )
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'nom' => 'sometimes|required|string|max:255',
            'prenom' => 'sometimes|required|string|max:255',
            'Tel' => 'sometimes|required|string|max:50',
            'role' => 'sometimes|required|string|max:255',
            'login' => 'sometimes|required|string|max:50|unique:users,login,' . $id,
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $updateData = $request->only(['nom', 'prenom', 'Tel', 'role', 'login']);



        $user->update($updateData);

        return response()->json([
            'message' => 'User updated successfully',
            'data' => $user
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/api/user/{id}",
     *     operationId="deleteUser",
     *     tags={"Users"},
     *     summary="Delete a user",
     *     description="Delete a user from the system",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="User ID",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="User deleted successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="User not found")
     *         )
     *     )
     * )
     */
    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $user->delete();

        return response()->json(['message' => 'User deleted successfully']);
    }
}
