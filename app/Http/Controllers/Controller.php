<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="MarocExplore API Documentation",
 *      description="Documentation de l'API pour la plateforme MarocExplore permettant de gérer des itinéraires touristiques.",
 *      @OA\Contact(
 *          email="contact@maroc-explore.ma" // Mettez votre email
 *      ),
 *      @OA\License(
 *          name="Licence MIT", // Ou autre licence
 *          url="https://opensource.org/licenses/MIT"
 *      )
 * )
 *
 * @OA\Server(
 *      url=L5_SWAGGER_CONST_HOST, // Sera remplacé par l'URL dans config ou .env
 *      description="Serveur API MarocExplore"
 * )
 *
 * @OA\SecurityScheme(
 *      securityScheme="sanctum",
 *      type="http",
 *      description="Authentification Sanctum via Bearer token. Entrez 'Bearer {token}'",
 *      name="Authorization",
 *      in="header",
 *      scheme="bearer"
 * )
 */
class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}
