<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="UpdateItineraryRequest",
 *     title="Update Itinerary Request Body",
 *     description="Structure des données pour la mise à jour d'un itinéraire (champs optionnels)",
 *     @OA\Property(
 *         property="title",
 *         type="string",
 *         maxLength=255,
 *         description="Nouveau titre de l'itinéraire",
 *         example="Weekend prolongé à Essaouira"
 *     ),
 *     @OA\Property(
 *         property="category",
 *         type="string",
 *         maxLength=100,
 *         description="Nouvelle catégorie",
 *         example="plage"
 *     ),
 *     @OA\Property(
 *         property="duration",
 *         type="integer",
 *         minimum=1,
 *         description="Nouvelle durée en jours",
 *         example=3
 *     ),
 *     @OA\Property(
 *         property="image",
 *         type="string",
 *         format="binary",
 *         description="Nouvelle image (optionnel, utiliser `multipart/form-data`)",
 *         nullable=true
 *     ),
 *     @OA\Property(
 *         property="remove_image",
 *         type="boolean",
 *         description="Mettre à true pour supprimer l'image existante (si supporté)",
 *         example=false
 *     ),
 *     @OA\Property(
 *         property="destinations",
 *         type="array",
 *         minItems=2,
 *         description="Nouvelle liste complète des destinations (au moins 2 requises). Remplace les anciennes.",
 *         @OA\Items(
 *             type="object",
 *             required={"name"},
 *             @OA\Property(property="name", type="string", maxLength=255),
 *             @OA\Property(property="lodging", type="string", maxLength=255, nullable=true),
 *             @OA\Property(property="things_to_do", type="string", nullable=true)
 *         )
 *     )
 * )
 */

class UpdateItineraryRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Vérifier si l'utilisateur connecté est le propriétaire de l'itinéraire
        // 'itinerary' est le nom du paramètre de route
        $itinerary = $this->route('itinerary');
        return $itinerary && $this->user()->can('update', $itinerary); // Utilise une Policy (recommandé)
         // Ou simplement: return $itinerary && $itinerary->user_id == $this->user()->id;
    }

    public function rules(): array
    {
         // 'sometimes' : valider seulement si présent dans la requête
        return [
            'title' => ['sometimes', 'required', 'string', 'max:255'],
            'category' => ['sometimes', 'required', 'string', 'max:100'],
            'duration' => ['sometimes', 'required', 'integer', 'min:1'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'], // Permet de mettre à jour l'image
            // La validation des destinations pour la mise à jour est plus complexe
            // Vous pourriez avoir besoin de valider des ID existants, de nouvelles destinations, etc.
            // Simplification : on s'attend à recevoir la liste *complète* des destinations à chaque update.
            'destinations' => ['sometimes', 'array', 'min:2'],
            'destinations.*.id' => ['nullable', 'integer', 'exists:destinations,id'], // Pour identifier les destinations existantes à MAJ
            'destinations.*.name' => ['required', 'string', 'max:255'],
            'destinations.*.lodging' => ['nullable', 'string', 'max:255'],
            'destinations.*.things_to_do' => ['nullable', 'string'],
        ];
    }
}