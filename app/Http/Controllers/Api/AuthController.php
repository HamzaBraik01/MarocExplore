<?php
// app/Http/Controllers/Api/AuthController.php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\UserResource; // <-- Importer UserResource

class AuthController extends Controller
{
    /**
     * @OA\Post(
     *      path="/register",
     *      operationId="registerUser",
     *      tags={"Authentification"},
     *      summary="Enregistrer un nouvel utilisateur",
     *      description="Crée un nouveau compte utilisateur.",
     *      @OA\RequestBody(
     *          required=true,
     *          // --------> ASSUREZ-VOUS QU'IL N'Y A AUCUN COMMENTAIRE SUR LA LIGNE SUIVANTE <---------
     *          description="Données d'enregistrement",
     *          @OA\JsonContent(ref="#/components/schemas/RegisterRequest")
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Utilisateur créé avec succès",
     *          @OA\JsonContent(ref="#/components/schemas/User")
     *       ),
     *      @OA\Response(
     *          response=422,
     *          description="Erreur de validation"
     *      )
     * )
     */
    public function register(Request $request) // Idéalement, utiliser un FormRequest ici
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return new UserResource($user);
    }

    // ... reste du code (login, logout, user) ...

    /**
     * @OA\Get(
     *      path="/user",
     *      operationId="getAuthenticatedUser",
     *      tags={"Authentification"},
     *      summary="Récupérer l'utilisateur connecté",
     *      description="Retourne les informations de l'utilisateur associé au token.",
     *      security={{"sanctum":{}}},
     *      @OA\Response(
     *          response=200,
     *          description="Opération réussie",
     *          @OA\JsonContent(ref="#/components/schemas/User")
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Non authentifié"
     *      )
     * )
     */
    public function user(Request $request)
    {
        return new UserResource($request->user());
    }
}