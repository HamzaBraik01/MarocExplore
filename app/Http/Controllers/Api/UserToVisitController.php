<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Itinerary;
use App\Http\Resources\ItineraryResource;
use Illuminate\Support\Facades\Auth;

class UserToVisitController extends Controller
{
    /**
     * @OA\Post(
     *      path="/user/to-visit/{itinerary_id}",
     *      operationId="addItineraryToFavorites",
     *      tags={"Favoris (À Visiter)"},
     *      summary="Ajouter un itinéraire aux favoris",
     *      description="Ajoute un itinéraire à la liste 'À visiter' de l'utilisateur connecté.",
     *      security={{"sanctum":{}}},
     *      @OA\Parameter(
     *          name="itinerary_id",
     *          in="path",
     *          required=true,
     *          description="ID de l'itinéraire à ajouter",
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Ajout réussi",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="message", type="string", example="Itinéraire ajouté à votre liste 'A visiter'")
     *          )
     *      ),
     *      @OA\Response(response=401, description="Non authentifié"),
     *      @OA\Response(response=404, description="Itinéraire non trouvé")
     * )
     */
    public function add(Itinerary $itinerary)
    {
        $user = Auth::user();

        // Utilise attach() pour ajouter l'entrée dans la table pivot
        // Ne fait rien si l'association existe déjà (évite les doublons)
        $user->toVisitItineraries()->syncWithoutDetaching([$itinerary->id]);

        return response()->json(['message' => 'Itinéraire ajouté à votre liste "À visiter"']);
    }

    /**
     * @OA\Delete(
     *      path="/user/to-visit/{itinerary_id}",
     *      operationId="removeItineraryFromFavorites",
     *      tags={"Favoris (À Visiter)"},
     *      summary="Retirer un itinéraire des favoris",
     *      description="Retire un itinéraire de la liste 'À visiter' de l'utilisateur connecté.",
     *      security={{"sanctum":{}}},
     *      @OA\Parameter(
     *          name="itinerary_id",
     *          in="path",
     *          required=true,
     *          description="ID de l'itinéraire à retirer",
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Retrait réussi",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="message", type="string", example="Itinéraire retiré de votre liste 'A visiter'")
     *          )
     *      ),
     *      @OA\Response(response=401, description="Non authentifié"),
     *      @OA\Response(response=404, description="Itinéraire non trouvé ou pas dans la liste")
     * )
     */
    public function remove(Itinerary $itinerary)
    {
        $user = Auth::user();

        // Utilise detach() pour supprimer l'entrée de la table pivot
        $detached = $user->toVisitItineraries()->detach($itinerary->id);

        if ($detached) {
            return response()->json(['message' => 'Itinéraire retiré de votre liste "À visiter"']);
        } else {
            return response()->json(['message' => 'Cet itinéraire n\'était pas dans votre liste'], 404);
        }
    }

    /**
     * @OA\Get(
     *      path="/user/to-visit",
     *      operationId="getUserFavorites",
     *      tags={"Favoris (À Visiter)"},
     *      summary="Lister les itinéraires favoris de l'utilisateur",
     *      description="Retourne la liste paginée des itinéraires ajoutés à la liste 'À visiter'.",
     *      security={{"sanctum":{}}},
     *      @OA\Parameter(
     *          name="page",
     *          in="query",
     *          description="Numéro de page",
     *          required=false,
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Opération réussie",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Itinerary")),
     *              @OA\Property(property="links", type="object"),
     *              @OA\Property(property="meta", type="object")
     *          )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Non authentifié"
     *      )
     * )
     */
    public function index()
    {
        $user = Auth::user();
        $itineraries = $user->toVisitItineraries()->with(['user', 'destinations'])->latest('user_to_visit_itinerary.created_at')->paginate(15);

        return ItineraryResource::collection($itineraries);
    }
}