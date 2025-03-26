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
 *     @OA\Property(property="title", type="string", maxLength=255, description="Titre de l'itinéraire", example="Weekend à Essaouira"),
 *     @OA\Property(property="category", type="string", maxLength=100, description="Catégorie", example="plage"),
 *     @OA\Property(property="duration", type="integer", minimum=1, description="Durée en jours", example=2),
 *     @OA\Property(property="image", type="string", format="binary", description="Image (optionnel)", nullable=true),
 *     @OA\Property(
 *         property="destinations", type="array", minItems=2, description="Liste des destinations (min 2)",
 *         @OA\Items(type="object", required={"name"},
 *             @OA\Property(property="name", type="string", maxLength=255, example="Médina"),
 *             @OA\Property(property="lodging", type="string", maxLength=255, nullable=true, example="Riad"),
 *             @OA\Property(property="things_to_do", type="string", nullable=true, example="Souks")
 *         )
 *     )
 * )
 */

// --- DÉFINITIONS AJOUTÉES/REMPLACÉES ---
/**
 * @OA\Schema(
 *     schema="RegisterRequest",
 *     title="Register Request Body",
 *     description="Données requises pour l'enregistrement d'un utilisateur",
 *     required={"name", "email", "password", "password_confirmation"},
 *     @OA\Property(property="name", type="string", maxLength=255, example="Test User"),
 *     @OA\Property(property="email", type="string", format="email", maxLength=255, example="test@example.com"),
 *     @OA\Property(property="password", type="string", format="password", minLength=8, example="password123"),
 *     @OA\Property(property="password_confirmation", type="string", format="password", example="password123")
 * )
 */

 /**
 * @OA\Schema(
 *     schema="LoginRequest",
 *     title="Login Request Body",
 *     description="Données requises pour la connexion d'un utilisateur",
 *     required={"email", "password"},
 *     @OA\Property(property="email", type="string", format="email", example="test@example.com"),
 *     @OA\Property(property="password", type="string", format="password", example="password123")
 * )
 */
 // --- FIN DES DÉFINITIONS AJOUTÉES/REMPLACÉES ---

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