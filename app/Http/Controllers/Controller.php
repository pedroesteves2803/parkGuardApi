<?php

namespace App\Http\Controllers;

/**
 * @OA\Info(
 *     title="Estacionamento API",
 *     version="1.0.0"
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     description="Enter JWT token to access secured endpoints"
 * )
 */
abstract class Controller
{
}
