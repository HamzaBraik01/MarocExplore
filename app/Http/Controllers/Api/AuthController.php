<?php
// app/Http/Controllers/Api/AuthController.php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;

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
    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'], // confirmed -> password_confirmation
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Hashage du mot de passe
        ]);

        // Optionnel: Connecter l'utilisateur et retourner un token immédiatement
        // $token = $user->createToken('api-token')->plainTextToken;
        // return response()->json(['user' => $user, 'token' => $token], 201);

        return response()->json($user, 201); // Ou juste l'utilisateur créé
    }

    /**
     * @OA\Post(
     *      path="/login",
     *      operationId="loginUser",
     *      tags={"Authentification"},
     *      summary="Connecter un utilisateur",
     *      description="Authentifie l'utilisateur et retourne un token API Sanctum.",
     *      @OA\RequestBody(
     *          required=true,
     *          description="Identifiants de connexion",
     *          @OA\JsonContent(ref="#/components/schemas/LoginRequest")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Connexion réussie",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="token", type="string", description="Token API Bearer", example="1|abcdef123...")
     *          )
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Identifiants invalides ou erreur de validation"
     *      )
     * )
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => [__('auth.failed')], // Message d'erreur standard
            ]);
        }

        // Créer un token API pour l'utilisateur
        $token = $user->createToken('api-token-'.$user->id)->plainTextToken; // Nom du token unique

        return response()->json(['token' => $token]);
    }

    /**
     * @OA\Post(
     *      path="/logout",
     *      operationId="logoutUser",
     *      tags={"Authentification"},
     *      summary="Déconnecter l'utilisateur",
     *      description="Révoque le token API utilisé pour la requête.",
     *      security={{"sanctum":{}}},
     *      @OA\Response(
     *          response=200,
     *          description="Déconnexion réussie",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="message", type="string", example="Déconnexion réussie")
     *          )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Non authentifié"
     *      )
     * )
     */
    public function logout(Request $request)
    {
        // Révoquer le token utilisé pour la requête
        $request->user()->currentAccessToken()->delete();

        // Optionnel: Révoquer tous les tokens de l'utilisateur
        // $request->user()->tokens()->delete();

        return response()->json(['message' => 'Déconnexion réussie']);
    }

    /**
     * @OA\Post(
     *      path="/logout",
     *      operationId="logoutUser",
     *      tags={"Authentification"},
     *      summary="Déconnecter l'utilisateur",
     *      description="Révoque le token API utilisé pour la requête.",
     *      security={{"sanctum":{}}},
     *      @OA\Response(
     *          response=200,
     *          description="Déconnexion réussie",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="message", type="string", example="Déconnexion réussie")
     *          )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Non authentifié"
     *      )
     * )
     */
    public function user(Request $request)
    {
        return response()->json($request->user());
    }
}