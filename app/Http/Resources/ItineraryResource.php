<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Storage;

class ItineraryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'category' => $this->category,
            'duration' => $this->duration,
            'image_url' => $this->image_path ? url(\Storage::url($this->image_path)) : null, // Générer l'URL complète si l'image existe
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
            'user' => new UserResource($this->whenLoaded('user')), // Inclure l'utilisateur (si chargé)
            'destinations' => DestinationResource::collection($this->whenLoaded('destinations')), // Inclure les destinations (si chargées)
            'is_favorited' => $this->when(Auth::check(), function () { // Indiquer si l'utilisateur connecté l'a en favori
                return Auth::user()->toVisitItineraries()->where('itinerary_id', $this->id)->exists();
            }, false),
             'favorites_count' => $this->whenCounted('favoritedByUsers'), // Inclure le compte des favoris si demandé
        ];
    }
}