<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ItineraryController;
use App\Http\Controllers\Api\UserToVisitController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/itineraries', [ItineraryController::class, 'index']);
Route::get('/itineraries/{itinerary}', [ItineraryController::class, 'show']);

// Routes nécessitant une authentification
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']); // Pour obtenir l'utilisateur connecté
    // CRUD Itinéraires (protégé)
    Route::post('/itineraries', [ItineraryController::class, 'store']);
    Route::put('/itineraries/{itinerary}', [ItineraryController::class, 'update']); // Utiliser PUT ou PATCH
    Route::patch('/itineraries/{itinerary}', [ItineraryController::class, 'update']);
    Route::delete('/itineraries/{itinerary}', [ItineraryController::class, 'destroy']);

    // Gestion de la liste "À visiter"
    Route::prefix('user/to-visit')->group(function () {
        Route::get('/', [UserToVisitController::class, 'index']); // Liste des favoris de l'utilisateur
        Route::post('/{itinerary}', [UserToVisitController::class, 'add']); // Ajouter aux favoris
        Route::delete('/{itinerary}', [UserToVisitController::class, 'remove']); // Retirer des favoris
    });
});