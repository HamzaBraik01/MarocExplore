<?php
// app/Models/Itinerary.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @OA\Schema(
 *     schema="Itinerary",
 *     title="Itinerary",
 *     description="Représente un itinéraire touristique",
 *     required={"title", "category", "duration", "user_id"},
 *     @OA\Property(property="id", type="integer", readOnly=true, description="ID unique de l'itinéraire", example=1),
 *     @OA\Property(property="user_id", type="integer", description="ID de l'utilisateur créateur", example=1),
 *     @OA\Property(property="title", type="string", maxLength=255, description="Titre de l'itinéraire", example="Aventure dans l'Atlas"),
 *     @OA\Property(property="category", type="string", maxLength=100, description="Catégorie (montagne, plage, ville, etc.)", example="montagne"),
 *     @OA\Property(property="duration", type="integer", description="Durée en jours", example=5),
 *     @OA\Property(property="image_url", type="string", format="url", nullable=true, description="URL complète de l'image", example="http://localhost/storage/itineraries/image.jpg"),
 *     @OA\Property(property="created_at", type="string", format="date-time", readOnly=true, description="Date de création"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", readOnly=true, description="Date de dernière mise à jour"),
 *     @OA\Property(property="is_favorited", type="boolean", readOnly=true, description="Indique si l'utilisateur connecté a mis cet itinéraire en favori (si authentifié)", example=false),
 *     @OA\Property(property="favorites_count", type="integer", readOnly=true, description="Nombre total de favoris pour cet itinéraire", example=12),
 *     @OA\Property(property="user", type="object", ref="#/components/schemas/User", description="Informations sur l'utilisateur créateur"),
 *     @OA\Property(
 *         property="destinations",
 *         type="array",
 *         description="Liste des destinations de l'itinéraire",
 *         @OA\Items(ref="#/components/schemas/Destination")
 *     )
 * )
 */
class Itinerary extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'category',
        'duration',
        'image_path',
    ];

    // Un itinéraire appartient à un utilisateur
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Un itinéraire a plusieurs destinations
    public function destinations(): HasMany
    {
        return $this->hasMany(Destination::class);
    }

    // Les utilisateurs qui ont ajouté cet itinéraire à leur liste "à visiter"
    public function favoritedByUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_to_visit_itinerary', 'itinerary_id', 'user_id')
                    ->withTimestamps();
    }
}