<?php

namespace App\Http\Controllers\User\Api;

use App\Http\Controllers\Controller;
use App\Models\FAQ;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="FAQ",
 *     description="API endpoints for FAQ management"
 * )
 */
class FAQController extends Controller
{
    /**
     * @OA\Get(
     *     path="/faqs",
     *     summary="Get list of all FAQs",
     *     description="Returns list of all frequently asked questions",
     *     operationId="getFAQs",
     *     tags={"FAQ"},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="status",
     *                 type="string",
     *                 example="success"
     *             ),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id_faq", type="integer", example=1),
     *                     @OA\Property(property="judul", type="string", example="How do I reset my password?"),
     *                     @OA\Property(property="deskripsi", type="string", example="You can reset your password by clicking on the 'Forgot Password' link on the login page."),
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No FAQs found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="No FAQs found")
     *         )
     *     )
     * )
     */
    public function index()
    {
        $faqs = FAQ::all();

        if ($faqs->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'No FAQs found'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $faqs
        ]);
    }

    /**
     * @OA\Get(
     *     path="/faqs/{id}",
     *     summary="Get specific FAQ",
     *     description="Returns a specific FAQ by its ID",
     *     operationId="getFAQ",
     *     tags={"FAQ"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of FAQ to return",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="status",
     *                 type="string",
     *                 example="success"
     *             ),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id_faq", type="integer", example=1),
     *                 @OA\Property(property="judul", type="string", example="How do I reset my password?"),
     *                 @OA\Property(property="deskripsi", type="string", example="You can reset your password by clicking on the 'Forgot Password' link on the login page."),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="FAQ not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="FAQ not found")
     *         )
     *     )
     * )
     */
    public function show($id)
    {
        $faq = FAQ::find($id);

        if (!$faq) {
            return response()->json([
                'status' => 'error',
                'message' => 'FAQ not found'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $faq
        ]);
    }
}
