<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_itineraries_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('itineraries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Clé étrangère vers users
            $table->string('title');
            $table->string('category'); // plage, montagne, etc.
            $table->integer('duration'); // En jours, par exemple
            $table->string('image_path')->nullable(); // Chemin vers l'image stockée
            $table->timestamps();
        });
    }
    // ... down() method
};