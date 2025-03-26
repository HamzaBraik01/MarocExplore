<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Itinerary;
use App\Http\Requests\StoreItineraryRequest;
use App\Http\Requests\UpdateItineraryRequest;
use App\Http\Resources\ItineraryResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // Pour la gestion des images
use Illuminate\Support\Facades\DB; // Pour les transactions
use Illuminate\Support\Facades\Auth; // Pour l'utilisateur connecté

class ItineraryController extends Controller
{
    public function __construct()
    {
         // Appliquer l'autorisation de la Policy (sauf pour index et show)
        $this->authorizeResource(Itinerary::class, 'itinerary', [
            'except' => ['index', 'show'],
        ]);
    }

    // Lister tous les itinéraires (avec filtres)
    public function index(Request $request)
    {
        $query = Itinerary::query()->with(['user', 'destinations']); // Eager loading

        // Filtrage par catégorie
        $query->when($request->filled('category'), function ($q) use ($request) {
            $q->where('category', $request->input('category'));
        });

        // Filtrage par durée (exemple: durée exacte)
        $query->when($request->filled('duration'), function ($q) use ($request) {
            $q->where('duration', $request->input('duration'));
            // Ou pour une durée max: $q->where('duration', '<=', $request->input('duration'));
        });

         // Recherche par mot-clé dans le titre
        $query->when($request->filled('search'), function ($q) use ($request) {
            $q->where('title', 'like', '%' . $request->input('search') . '%');
        });

        // Tri (par défaut par date de création, ou popularité)
        if ($request->input('sortBy') === 'popularity') {
            // Assurez-vous d'avoir la relation `favoritedByUsers` définie dans le modèle Itinerary
            $query->withCount('favoritedByUsers')->orderBy('favorited_by_users_count', 'desc');
        } else {
            $query->latest(); // Tri par date de création (plus récent d'abord)
        }


        $itineraries = $query->paginate(15); // Paginer les résultats

        return ItineraryResource::collection($itineraries);
    }

    // Créer un nouvel itinéraire
    public function store(StoreItineraryRequest $request)
    {
        $validatedData = $request->validated();
        $imagePath = null;

        // Gestion de l'upload d'image
        if ($request->hasFile('image')) {
            // Stocker dans 'public/itineraries' et obtenir le chemin relatif
            $imagePath = $request->file('image')->store('itineraries', 'public');
        }

        // Utiliser une transaction pour assurer l'intégrité
        $itinerary = DB::transaction(function () use ($validatedData, $imagePath, $request) {
            // Créer l'itinéraire
            $itinerary = $request->user()->itineraries()->create([
                'title' => $validatedData['title'],
                'category' => $validatedData['category'],
                'duration' => $validatedData['duration'],
                'image_path' => $imagePath,
            ]);

            // Créer les destinations associées
            foreach ($validatedData['destinations'] as $destinationData) {
                $itinerary->destinations()->create([
                    'name' => $destinationData['name'],
                    'lodging' => $destinationData['lodging'] ?? null,
                    'things_to_do' => $destinationData['things_to_do'] ?? null,
                ]);
            }

            return $itinerary;
        });

        // Charger les relations pour la réponse
        $itinerary->load(['user', 'destinations']);

        return new ItineraryResource($itinerary); // Status 201 par défaut
    }

    // Afficher un itinéraire spécifique
    public function show(Itinerary $itinerary)
    {
        // Charger les relations nécessaires
        $itinerary->load(['user', 'destinations']);
        return new ItineraryResource($itinerary);
    }

    // Mettre à jour un itinéraire
    public function update(UpdateItineraryRequest $request, Itinerary $itinerary)
    {
        $validatedData = $request->validated();
        $imagePath = $itinerary->image_path; // Garder l'ancienne image par défaut

        // Gestion de la mise à jour/suppression de l'image
        if ($request->hasFile('image')) {
             // Supprimer l'ancienne image si elle existe
            if ($itinerary->image_path) {
                Storage::disk('public')->delete($itinerary->image_path);
            }
             // Stocker la nouvelle image
            $imagePath = $request->file('image')->store('itineraries', 'public');
        } elseif ($request->boolean('remove_image')) { // Ajouter une option pour supprimer l'image sans en ajouter une nouvelle
             if ($itinerary->image_path) {
                Storage::disk('public')->delete($itinerary->image_path);
            }
            $imagePath = null;
        }


        $updatedItinerary = DB::transaction(function () use ($itinerary, $validatedData, $imagePath, $request) {
            // Mettre à jour les champs de l'itinéraire
            $itinerary->update([
                'title' => $validatedData['title'] ?? $itinerary->title,
                'category' => $validatedData['category'] ?? $itinerary->category,
                'duration' => $validatedData['duration'] ?? $itinerary->duration,
                'image_path' => $imagePath, // Mettre à jour le chemin de l'image
            ]);

            // Gestion de la mise à jour des destinations (stratégie : remplacer toutes les destinations)
            if ($request->has('destinations')) {
                // 1. Supprimer les anciennes destinations
                $itinerary->destinations()->delete();
                // 2. Créer les nouvelles destinations
                foreach ($validatedData['destinations'] as $destinationData) {
                    $itinerary->destinations()->create([
                        'name' => $destinationData['name'],
                        'lodging' => $destinationData['lodging'] ?? null,
                        'things_to_do' => $destinationData['things_to_do'] ?? null,
                    ]);
                }
                // Note: Une stratégie plus complexe pourrait identifier les destinations à MAJ, ajouter, supprimer.
            }

            return $itinerary;
        });


        // Recharger les relations pour la réponse
        $updatedItinerary->load(['user', 'destinations']);

        return new ItineraryResource($updatedItinerary);
    }

    // Supprimer un itinéraire
    public function destroy(Itinerary $itinerary)
    {
         // L'autorisation est déjà gérée par authorizeResource ou la policy

         // Supprimer l'image associée si elle existe
         if ($itinerary->image_path) {
            Storage::disk('public')->delete($itinerary->image_path);
         }

        $itinerary->delete(); // Supprime l'itinéraire et les destinations en cascade (grâce à onDelete('cascade'))

        return response()->json(['message' => 'Itinéraire supprimé avec succès'], 200);
        // Ou: return response()->noContent(); // Status 204
    }
}