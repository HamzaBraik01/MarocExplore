<?php

namespace App\Policies;

use App\Models\Itinerary;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ItineraryPolicy
{
    public function create(User $user): bool
    {
        // Si n'importe quel utilisateur authentifié peut créer un itinéraire :
        return true;

        // Si vous aviez des règles plus spécifiques (ex: rôle, statut vérifié),
        // vous les mettriez ici. Par exemple :
        // return $user->hasRole('contributor');
        // return $user->isVerified();
    }

    public function view(?User $user, Itinerary $itinerary): bool
    {
        // Pour l'instant, tout le monde peut voir n'importe quel itinéraire
        return true;

        // Plus tard, vous pourriez ajouter des règles, ex:
        // return $itinerary->is_public || ($user && $user->id === $itinerary->user_id);
    }
    // Détermine si l'utilisateur peut mettre à jour le modèle.
    public function update(User $user, Itinerary $itinerary): bool
    {
        return $user->id === $itinerary->user_id;
    }

    // Détermine si l'utilisateur peut supprimer le modèle.
    public function delete(User $user, Itinerary $itinerary): bool
    {
        return $user->id === $itinerary->user_id;
    }

    // Ajoutez d'autres méthodes si nécessaire (view, create, etc.)
}