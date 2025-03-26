<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_user_to_visit_itinerary_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_to_visit_itinerary', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('itinerary_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            // Assurer l'unicité de la paire user/itinerary
            $table->unique(['user_id', 'itinerary_id']);
        });
    }
    // ... down() method
};