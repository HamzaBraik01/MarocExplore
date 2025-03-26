<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * @OA\Schema(
 *     schema="StoreItineraryRequest",
 *     title="Store Itinerary Request Body",
 *     description="Structure des données pour la création d'un itinéraire",
 *     required={"title", "category", "duration", "destinations"},
 *     @OA\Property(
 *         property="title",
 *         type="string",
 *         maxLength=255,
 *         description="Titre de l'itinéraire",
 *         example="Weekend à Essaouira"
 *     ),
 *     @OA\Property(
 *         property="category",
 *         type="string",
 *         maxLength=100,
 *         description="Catégorie (plage, montagne, ville, etc.)",
 *         example="plage"
 *     ),
 *     @OA\Property(
 *         property="duration",
 *         type="integer",
 *         minimum=1,
 *         description="Durée en jours",
 *         example=2
 *     ),
 *     @OA\Property(
 *         property="image",
 *         type="string",
 *         format="binary",
 *         description="Fichier image (optionnel, utiliser `multipart/form-data`)",
 *         nullable=true
 *     ),
 *     @OA\Property(
 *         property="destinations",
 *         type="array",
 *         minItems=2,
 *         description="Liste des destinations (au moins 2 requises)",
 *         @OA\Items(
 *             type="object",
 *             required={"name"},
 *             @OA\Property(property="name", type="string", maxLength=255, description="Nom de la destination", example="Médina d'Essaouira"),
 *             @OA\Property(property="lodging", type="string", maxLength=255, nullable=true, description="Lieu de logement", example="Riad local"),
 *             @OA\Property(property="things_to_do", type="string", nullable=true, description="Choses à faire/voir", example="Remparts, port, souks")
 *         )
 *     )
 * )
 */
class StoreItineraryRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Tout utilisateur authentifié peut créer un itinéraire
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:100'], // Ajuster la taille max
            'duration' => ['required', 'integer', 'min:1'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'], // Validation de l'image
            'destinations' => ['required', 'array', 'min:2'], // Doit être un tableau avec au moins 2 éléments
            'destinations.*.name' => ['required', 'string', 'max:255'],
            'destinations.*.lodging' => ['nullable', 'string', 'max:255'],
            'destinations.*.things_to_do' => ['nullable', 'string'],
        ];
    }

    public function messages() // Optionnel : messages d'erreur personnalisés
    {
        return [
            'destinations.required' => 'Au moins deux destinations sont requises.',
            'destinations.min' => 'Au moins deux destinations sont requises.',
            'destinations.*.name.required' => 'Le nom de chaque destination est requis.',
        ];
    }
}
