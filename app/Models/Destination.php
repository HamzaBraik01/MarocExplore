<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
/**
 * @OA\Schema(
 *     schema="Destination",
 *     title="Destination",
 *     description="Représente une destination au sein d'un itinéraire",
 *     required={"name"},
 *     @OA\Property(property="id", type="integer", readOnly=true, description="ID unique de la destination", example=10),
 *     @OA\Property(property="name", type="string", maxLength=255, description="Nom de la destination", example="Place Jemaa el-Fna"),
 *     @OA\Property(property="lodging", type="string", maxLength=255, nullable=true, description="Lieu de logement suggéré", example="Riad Al Mamoun"),
 *     @OA\Property(property="things_to_do", type="string", nullable=true, description="Endroits à visiter, activités, plats", example="Visiter les souks, manger sur la place, charmeurs de serpents")
 * )
 */
class Destination extends Model
{
    use HasFactory;

    protected $fillable = [
        'itinerary_id',
        'name',
        'lodging',
        'things_to_do',
        // 'order', // Si vous ajoutez l'ordre
    ];

    // Une destination appartient à un itinéraire
    public function itinerary(): BelongsTo
    {
        return $this->belongsTo(Itinerary::class);
    }
}