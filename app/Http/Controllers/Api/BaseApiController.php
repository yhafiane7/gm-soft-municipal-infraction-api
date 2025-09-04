<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

/**
 * @OA\Info(
 *     title="INFRACTION-COMMUNE-BACKEND API",
 *     version="1.0.0",
 *     description="API for managing infractions, communes, decisions, and users in the commune system",
 *     @OA\Contact(
 *         email="support@infraction-commune.com",
 *         name="API Support"
 *     ),
 *     @OA\License(
 *         name="MIT",
 *         url="https://opensource.org/licenses/MIT"
 *     )
 * )
 * 
 * @OA\Server(
 *     url=L5_SWAGGER_CONST_HOST,
 *     description="API Server"
 * )
 * 
 * @OA\Tag(
 *     name="Users",
 *     description="User management endpoints"
 * )
 * 
 * @OA\Tag(
 *     name="Infractions",
 *     description="Infraction management endpoints"
 * )
 * 
 * @OA\Tag(
 *     name="Decisions",
 *     description="Decision management endpoints"
 * )
 * 
 * @OA\Tag(
 *     name="Communes",
 *     description="Commune management endpoints"
 * )
 * 
 * @OA\Tag(
 *     name="Agents",
 *     description="Agent management endpoints"
 * )
 * 
 * @OA\Tag(
 *     name="Categories",
 *     description="Category management endpoints"
 * )
 * 
 * @OA\Tag(
 *     name="Violants",
 *     description="Violant management endpoints"
 * )
 */
abstract class BaseApiController extends Controller
{
    /**
     * Success response
     */
    protected function successResponse($data, $message = 'Success', $code = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data
        ], $code);
    }

    /**
     * Error response
     */
    protected function errorResponse($message, $code = 400, $errors = null): JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $message,
        ];

        if ($errors) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $code);
    }

    /**
     * Not found response
     */
    protected function notFoundResponse($message = 'Resource not found'): JsonResponse
    {
        return $this->errorResponse($message, 404);
    }

    /**
     * Validation error response
     */
    protected function validationErrorResponse($errors): JsonResponse
    {
        return $this->errorResponse('Validation failed', 400, $errors);
    }
}
