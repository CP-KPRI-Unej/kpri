<?php

namespace App\Http\Controllers\User\Api;

use App\Http\Controllers\Controller;

/**
 * @OA\Info(
 *     title="KPRI Profile API",
 *     version="1.0.0",
 *     description="API Documentation for KPRI Profile Application",
 *     @OA\Contact(
 *         email="admin@kpri.com",
 *         name="KPRI Admin"
 *     )
 * )
 * 
 * @OA\Server(
 *     url="/User/Api",
 *     description="API Server"
 * )
 * 
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT"
 * )
 */
class SwaggerController extends Controller
{
    //
} 