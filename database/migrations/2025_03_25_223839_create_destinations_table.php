<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_destinations_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('destinations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('itinerary_id')->constrained()->onDelete('cascade'); // Clé étrangère vers itineraries
            $table->string('name');
            $table->string('lodging')->nullable(); // Lieu de logement
            $table->text('things_to_do')->nullable(); // Liste d'endroits/activités/plats (JSON ou texte simple)
            // Optionnel: $table->integer('order')->default(0); // Pour ordonner les destinations
            $table->timestamps();
        });
    }
    // ... down() method
};