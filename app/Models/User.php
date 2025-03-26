<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens; // <-- Important
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @OA\Schema(
 *     schema="User",
 *     title="User",
 *     description="Représente un utilisateur (informations publiques pour l'API)",
 *     required={"id", "name", "email"},
 *     @OA\Property(property="id", type="integer", readOnly=true, description="ID unique de l'utilisateur", example=1),
 *     @OA\Property(property="name", type="string", description="Nom de l'utilisateur", example="John Doe"),
 *     @OA\Property(property="email", type="string", format="email", readOnly=true, description="Adresse email (généralement non modifiable via cette API)", example="john.doe@example.com")
 * )
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable; // <-- Important

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed', // <-- Utiliser le casting 'hashed' (Laravel 10+)
    ];

    // Un utilisateur crée plusieurs itinéraires
    public function itineraries(): HasMany
    {
        return $this->hasMany(Itinerary::class);
    }

    // Les itinéraires qu'un utilisateur veut visiter (liste à visiter)
    public function toVisitItineraries(): BelongsToMany
    {
        // Nom de la table pivot, clés étrangères
        return $this->belongsToMany(Itinerary::class, 'user_to_visit_itinerary', 'user_id', 'itinerary_id')
                    ->withTimestamps(); // Pour savoir quand il a été ajouté
    }
}